<?php
namespace Pluf\Template;

use Pluf\Bootstrap;
use Pluf\Signal;
use Pluf\Exception;

/**
 * Class to compile a template file into the corresponding PHP script
 * to be run by the Template class.
 *
 * Compiler dataflow
 *
 * The important elements of the compiler are the include extends
 * block and superblock directives. They cannot be handled in a linear
 * way like the rest of the elements, they are more like nodes.
 *
 *
 * @credit Copyright (C) 2006 Laurent Jouanneau.
 */
class Compiler
{

    /**
     * Store the literal blocks.
     */
    protected $_literals;

    /**
     * Variables.
     */
    protected $_vartype = array(
        T_PROTECTED,
        T_CONSTANT_ENCAPSED_STRING,
        T_DNUMBER,
        T_ENCAPSED_AND_WHITESPACE,
        T_LNUMBER,
        T_OBJECT_OPERATOR,
        T_STRING,
        T_WHITESPACE,
        T_ARRAY,
        T_CLASS,
        T_PRIVATE,
        T_LIST
    );

    /**
     * Assignation operators.
     */
    protected $_assignOp = array(
        T_AND_EQUAL,
        T_DIV_EQUAL,
        T_MINUS_EQUAL,
        T_MOD_EQUAL,
        T_MUL_EQUAL,
        T_OR_EQUAL,
        T_PLUS_EQUAL,
        T_PLUS_EQUAL,
        T_SL_EQUAL,
        T_SR_EQUAL,
        T_XOR_EQUAL
    );

    /**
     * Operators.
     */
    protected $_op = array(
        T_BOOLEAN_AND,
        T_BOOLEAN_OR,
        T_EMPTY,
        T_INC,
        T_ISSET,
        T_IS_EQUAL,
        T_IS_GREATER_OR_EQUAL,
        T_IS_IDENTICAL,
        T_IS_NOT_EQUAL,
        T_IS_NOT_IDENTICAL,
        T_IS_SMALLER_OR_EQUAL,
        T_LOGICAL_AND,
        T_LOGICAL_OR,
        T_LOGICAL_XOR,
        T_SR,
        T_SL,
        T_DOUBLE_ARROW
    );

    /**
     * Authorized elements in variables.
     */
    protected $_allowedInVar;

    /**
     * Authorized elements in expression.
     */
    protected $_allowedInExpr;

    /**
     * Authorized elements in assignation.
     */
    protected $_allowedAssign;

    /**
     * Output filters.
     */
    protected $_modifier = array(
        'upper' => 'strtoupper',
        'lower' => 'strtolower',
        'count' => 'count',
        'md5' => 'md5',
        'sha1' => 'sha1',
        'escxml' => 'htmlspecialchars',
        'escape' => 'Pluf_Template_htmlspecialchars',
        'strip_tags' => 'strip_tags',
        'escurl' => 'rawurlencode',
        'capitalize' => 'ucwords',
        // Not var_export because of recursive issues.
        'debug' => 'print_r',
        // 'dump' => 'Pluf_Template_varExport',
        'fulldebug' => 'var_export',
        'nl2br' => 'Pluf_Template_nl2br',
        'trim' => 'trim',
        'ltrim' => 'ltrim',
        'rtrim' => 'rtrim',

        'unsafe' => '\Pluf\Template::unsafe',
        'safe' => '\Pluf\Template::unsafe',
        'date' => '\Pluf\Template::dateFormat',
        'time' => '\Pluf\Template::timeFormat',
        'dateago' => '\Pluf\Template::dateAgo',
        'timeago' => '\Pluf\Template::timeAgo',
        'email' => '\Pluf\Template::safeEmail',
        'first' => '\Pluf\Template::first',
        'last' => '\Pluf\Template::last'
    );

    /**
     * After the compilation is completed, this contains the list of
     * modifiers used in the template.
     * The GetCompiledTemplate method
     * will add a series of Pluf::loadFunction at the top to preload
     * these modifiers.
     */
    public $_usedModifiers = array();

    /**
     * Default allowed extra tags/functions.
     *
     *
     * These default tags are merged with the 'template_tags' defined
     * in the configuration of the application.
     */
    protected $allowedTags = array();

    /**
     * During compilation, all the tags are created once so to query
     * their interface easily.
     */
    protected $extraTags = array();

    /**
     * The block stack to see if the blocks are correctly closed.
     */
    protected $blockStack = array();

    /**
     * Current template source file.
     */
    protected $sourceFile;

    /**
     * Current tag.
     */
    protected $currentTag;

    /**
     * Template folders.
     */
    public $templateFolders = array();

    /**
     * Template content.
     * It can be set directly from a string.
     */
    public $templateContent = '';

    /**
     * The extend blocks.
     */
    public $_extendBlocks = array();

    /**
     * The extended template.
     */
    public $_extendedTemplate = '';

    /**
     * Construct the compiler.
     *
     * @param
     *            string Basename of the template file.
     * @param
     *            array Base folders in which the templates files
     *            should be found. (array())
     * @param
     *            bool Load directly the template content. (true)
     */
    function __construct($template_file, $folders = array(), $load = true)
    {
        /**
         * [signal]
         *
         * Pluf_Template_Compiler::construct_template_tags_modifiers
         *
         * [sender]
         *
         * Pluf_Template_Compiler
         *
         * [description]
         *
         * This signal allows an application to dynamically modify the
         * allowed template tags. The order of the merge with the ones
         * configured in the configuration files and the default one
         * is: default -> signal -> configuration file.
         * That is, the configuration file is the highest authority.
         *
         * [parameters]
         *
         * array('tags' => array(),
         * 'modifiers' => array());
         */
        $params = array(
            'tags' => array(),
            'modifiers' => array()
        );
        Signal::send('Pluf_Template_Compiler::construct_template_tags_modifiers', 'Pluf_Template_Compiler', $params);
        $this->allowedTags = array_merge($this->allowedTags, $params['tags'], Bootstrap::f('template_tags', array()));
        $this->_modifier = array_merge($this->_modifier, $params['modifiers'], Bootstrap::f('template_modifiers', array()));
        foreach ($this->allowedTags as $name => $model) {
            $this->extraTags[$name] = new $model();
        }
        $this->sourceFile = $template_file;
        $this->_allowedInVar = array_merge($this->_vartype, $this->_op);
        $this->_allowedInExpr = array_merge($this->_vartype, $this->_op);
        $this->_allowedAssign = array_merge($this->_vartype, $this->_assignOp, $this->_op);
        $this->templateFolders = $folders;
        if ($load) {
            $this->loadTemplateFile($template_file);
        }
    }

    /**
     * Compile the template into a PHP code.
     *
     * @return string PHP code of the compiled template.
     */
    function compile()
    {
        $this->compileBlocks();
        $tplcontent = $this->templateContent;
        // Remove the template comments
        $tplcontent = preg_replace('!{\*(.*?)\*}!s', '', $tplcontent);
        // Remove PHP code
        $tplcontent = preg_replace('!<\?php(.*?)\?>!s', '', $tplcontent);
        // Catch the litteral blocks and put them in the
        // $this->_literals stack
        $_match = array();
        preg_match_all('!{literal}(.*?){/literal}!s', $tplcontent, $_match);
        $this->_literals = $_match[1];
        $tplcontent = preg_replace("!{literal}(.*?){/literal}!s", '{literal}', $tplcontent);
        // Core regex to parse the template
        $result = preg_replace_callback('/{((.).*?)}/s', array(
            $this,
            '_callback'
        ), $tplcontent);
        if (count($this->blockStack)) {
            trigger_error(sprintf('End tag of a block missing: %s', end($this->blockStack)), E_USER_ERROR);
        }
        return $result;
    }

    /**
     * Get a cleaned compile template.
     */
    function getCompiledTemplate()
    {
        $result = $this->compile();
        // Clean the output
        $result = str_replace(array(
            '?><?php',
            '<?php ?>',
            '<?php  ?>'
        ), '', $result);
        // To avoid the triming of the \n after a php closing tag.
        $result = str_replace("?>\n", "?>\n\n", $result);
        return $result;
    }

    /**
     * Parse the extend blocks.
     *
     * If the current template extends another, it finds the extended
     * template and grabs the defined blocks and compile them.
     */
    function compileBlocks()
    {
        $tplcontent = $this->templateContent;
        $this->_extendedTemplate = '';
        // Match extends on the first line of the template
        $_match = array();
        if (preg_match("!{extends\s['\"](.*?)['\"]}!", $tplcontent, $_match)) {
            $this->_extendedTemplate = $_match[1];
        }
        // Get the blocks in the current template
        $cnt = preg_match_all("!{block\s(\S+?)}(.*?){/block}!s", $tplcontent, $_match);
        // Compile the blocks
        for ($i = 0; $i < $cnt; $i ++) {
            if (! isset($this->_extendBlocks[$_match[1][$i]]) or false !== strpos($this->_extendBlocks[$_match[1][$i]], '~~{~~superblock~~}~~')) {
                $compiler = clone ($this);
                $compiler->templateContent = $_match[2][$i];
                $_tmp = $compiler->compile();
                $this->updateModifierStack($compiler);
                if (! isset($this->_extendBlocks[$_match[1][$i]])) {
                    $this->_extendBlocks[$_match[1][$i]] = $_tmp;
                } else {
                    $this->_extendBlocks[$_match[1][$i]] = str_replace('~~{~~superblock~~}~~', $_tmp, $this->_extendBlocks[$_match[1][$i]]);
                }
            }
        }
        if (strlen($this->_extendedTemplate) > 0) {
            // The template of interest is now the extended template
            // as we are not in a base template
            $this->loadTemplateFile($this->_extendedTemplate);
            $this->sourceFile = $this->_extendedTemplate;
            $this->compileBlocks(); // It will recurse to the base template.
        } else {
            // Replace the current blocks by a place holder
            if ($cnt) {
                $this->templateContent = preg_replace("!{block\s(\S+?)}(.*?){/block}!s", "{block $1}", $tplcontent, - 1);
            }
        }
    }

    /**
     * Load a template file.
     *
     * The path to the file to load is relative and the file is found
     * in one of the $templateFolders array of folders.
     *
     * @param
     *            string Relative path of the file to load.
     */
    function loadTemplateFile($file)
    {
        // FIXME: Very small security check, could be better.
        if (strpos($file, '..') !== false) {
            throw new Exception(sprintf('Template file contains invalid characters: %s', $file));
        }
        foreach ($this->templateFolders as $folder) {
            if (file_exists($folder . '/' . $file)) {
                $this->templateContent = file_get_contents($folder . '/' . $file);
                return;
            }
        }
        // File not found in all the folders.
        throw new Exception(sprintf('Template file not found: %s', $file));
    }

    function _callback($matches)
    {
        list (, $tag, $firstcar) = $matches;
        if (! preg_match('/^\$|[\'"]|[a-zA-Z\/]$/', $firstcar)) {
            trigger_error(sprintf('Invalid tag syntax: %s', $tag), E_USER_ERROR);
            return '';
        }
        $this->currentTag = $tag;
        if (in_array($firstcar, array(
            '$',
            '\'',
            '"'
        ))) {
            if ('blocktrans' !== end($this->blockStack)) {
                return '<?php \Pluf\Template::safeEcho(' . $this->_parseVariable($tag) . '); ?>';
            } else {
                $tok = explode('|', $tag);
                $this->transStack[substr($tok[0], 1)] = $this->_parseVariable($tag);
                return '%%' . substr($tok[0], 1) . '%%';
            }
        } else {
            $m = array();
            if (! preg_match('/^(\/?[a-zA-Z0-9_]+)(?:(?:\s+(.*))|(?:\((.*)\)))?$/', $tag, $m)) {
                trigger_error(sprintf('Invalid function syntax: %s', $tag), E_USER_ERROR);
                return '';
            }
            if (count($m) == 4) {
                $m[2] = $m[3];
            }
            if (! isset($m[2]))
                $m[2] = '';
            if ($m[1] == 'ldelim')
                return '{';
            if ($m[1] == 'rdelim')
                return '}';
            if ($m[1] != 'include') {
                return '<?php ' . $this->_parseFunction($m[1], $m[2]) . '?>';
            } else {
                return $this->_parseFunction($m[1], $m[2]);
            }
        }
    }

    function _parseVariable($expr)
    {
        $tok = explode('|', $expr);
        $res = $this->_parseFinal(array_shift($tok), $this->_allowedInVar);
        foreach ($tok as $modifier) {
            $m = array();
            if (! preg_match('/^(\w+)(?:\:(.*))?$/', $modifier, $m)) {
                trigger_error(sprintf('Invalid modifier syntax: (%s) %s', $this->currentTag, $modifier), E_USER_ERROR);
                return '';
            }
            if (isset($m[2])) {
                $res = $this->_modifier[$m[1]] . '(' . $res . ',' . $m[2] . ')';
            } else if (isset($this->_modifier[$m[1]])) {
                $res = $this->_modifier[$m[1]] . '(' . $res . ')';
            } else {
                trigger_error(sprintf('Unknown modifier: (%s) %s', $this->currentTag, $m[1]), E_USER_ERROR);
                return '';
            }
            if (! in_array($this->_modifier[$m[1]], $this->_usedModifiers)) {
                $this->_usedModifiers[] = $this->_modifier[$m[1]];
            }
        }
        return $res;
    }

    function _parseFunction($name, $args)
    {
        switch ($name) {
            case 'if':
                $res = 'if (' . $this->_parseFinal($args, $this->_allowedInExpr) . '): ';
                array_push($this->blockStack, 'if');
                break;
            case 'else':
                if (end($this->blockStack) != 'if') {
                    trigger_error(sprintf('End tag of a block missing: %s', end($this->blockStack)), E_USER_ERROR);
                }
                $res = 'else: ';
                break;
            case 'elseif':
                if (end($this->blockStack) != 'if') {
                    trigger_error(sprintf('End tag of a block missing: %s', end($this->blockStack)), E_USER_ERROR);
                }
                $res = 'elseif(' . $this->_parseFinal($args, $this->_allowedInExpr) . '):';
                break;
            case 'foreach':
                $res = 'foreach (' . $this->_parseFinal($args, array_merge(array(
                    T_AS,
                    T_DOUBLE_ARROW,
                    T_STRING,
                    T_OBJECT_OPERATOR,
                    T_LIST,
                    $this->_allowedAssign,
                    '[',
                    ']'
                )), array(
                    ';',
                    '!'
                )) . '): ';
                array_push($this->blockStack, 'foreach');
                break;
            case 'while':
                $res = 'while(' . $this->_parseFinal($args, $this->_allowedInExpr) . '):';
                array_push($this->blockStack, 'while');
                break;
            case '/foreach':
            case '/if':
            case '/while':
                $short = substr($name, 1);
                if (end($this->blockStack) != $short) {
                    trigger_error(sprintf('End tag of a block missing: %s', end($this->blockStack)), E_USER_ERROR);
                }
                array_pop($this->blockStack);
                $res = 'end' . $short . '; ';
                break;
            case 'assign':
                $res = $this->_parseFinal($args, $this->_allowedAssign) . '; ';
                break;
            case 'literal':
                if (count($this->_literals)) {
                    $res = '?>' . array_shift($this->_literals) . '<?php ';
                } else {
                    trigger_error('End tag of a block missing: literal', E_USER_ERROR);
                }
                break;
            case '/literal':
                trigger_error('Start tag of a block missing: literal', E_USER_ERROR);
                break;
            case 'block':
                $res = '?>' . $this->_extendBlocks[$args] . '<?php ';
                break;
            case 'superblock':
                $res = '?>~~{~~superblock~~}~~<?php ';
                break;
            case 'include':
                // XXX fixme: Will need some security check, when online
                // editing.
                $argfct = preg_replace('!^[\'"](.*)[\'"]$!', '$1', $args);
                $_comp = new Compiler($argfct, $this->templateFolders);
                $res = $_comp->compile();
                $this->updateModifierStack($_comp);
                break;
            default:
                $_end = false;
                $oname = $name;
                if (substr($name, 0, 1) == '/') {
                    $_end = true;
                    $name = substr($name, 1);
                }
                // Here we should allow custom blocks.

                // Here we start the template tag calls at the template tag
                // {tag ...} is not a block, so it must be a function.
                if (! isset($this->allowedTags[$name])) {
                    trigger_error(sprintf('The function tag "%s" is not allowed.', $name), E_USER_ERROR);
                }
                $argfct = $this->_parseFinal($args, $this->_allowedAssign);
                // $argfct is a string that can be copy/pasted in the PHP code
                // but we need the array of args.
                $res = '';
                if (isset($this->extraTags[$name])) {
                    if (false == $_end) {
                        if (method_exists($this->extraTags[$name], 'start')) {
                            $res .= '$_extra_tag = new ' . $this->allowedTags[$name] . '($t); $_extra_tag->start(' . $argfct . '); ';
                        }
                        if (method_exists($this->extraTags[$name], 'genStart')) {
                            $res .= $this->extraTags[$name]->genStart();
                        }
                    } else {
                        if (method_exists($this->extraTags[$name], 'end')) {
                            $res .= '$_extra_tag = new ' . $this->allowedTags[$name] . '($t); $_extra_tag->end(' . $argfct . '); ';
                        }
                        if (method_exists($this->extraTags[$name], 'genEnd')) {
                            $res .= $this->extraTags[$name]->genEnd();
                        }
                    }
                }
                if ($res == '') {
                    trigger_error(sprintf('The function tag "{%s ...}" is not supported.', $oname), E_USER_ERROR);
                }
        }
        return $res;
    }

    /*
     *
     * -------
     * if: op, autre, var
     * foreach: T_AS, T_DOUBLE_ARROW, T_VARIABLE, @locale@
     * for: autre, fin_instruction
     * while: op, autre, var
     * assign: T_VARIABLE puis assign puis autre, ponctuation, T_STRING
     * echo: T_VARIABLE/@locale@ puis autre + ponctuation
     * modificateur: serie de autre séparé par une virgule
     *
     * tous : T_VARIABLE, @locale@
     *
     */
    function _parseFinal($string, $allowed = array(), $exceptchar = array(
        ';'
    ), $getAsArray = false)
    {
        $tokens = token_get_all('<?php ' . $string . '?>');
        $result = '';
        // $first = true;
        $inDot = false;
        $firstok = array_shift($tokens);
        // $afterAs = false;
        // $f_key = '';
        // $f_val = '';
        $results = array();

        // il y a un bug, parfois le premier token n'est pas T_OPEN_TAG...
        if ($firstok == '<' && $tokens[0] == '?' && is_array($tokens[1]) && $tokens[1][0] == T_STRING && $tokens[1][1] == 'php') {
            array_shift($tokens);
            array_shift($tokens);
        }
        foreach ($tokens as $tok) {
            if (is_array($tok)) {
                list ($type, $str) = $tok;
                // $first = false;
                if ($type == T_CLOSE_TAG) {
                    continue;
                }
                // if ($type == T_AS) {
                // $afterAs = true;
                // }
                if ($type == T_STRING && $inDot) {
                    $result .= $str;
                } elseif ($type == T_VARIABLE) {
                    // $result .= '$t->_vars[\''.substr($str, 1).'\']';
                    $result .= '$t->_vars->' . substr($str, 1);
                } elseif ($type == T_WHITESPACE || in_array($type, $allowed)) {
                    $result .= $str;
                } else {
                    trigger_error(sprintf('Invalid syntax: (%s) %s.', $this->currentTag, $str . ' tokens' . var_export($tokens, true)), E_USER_ERROR);
                    return '';
                }
            } else {
                if (in_array($tok, $exceptchar)) {
                    trigger_error(sprintf('Invalid character: (%s) %s.', $this->currentTag, $tok), E_USER_ERROR);
                } elseif ($tok == '.') {
                    $inDot = true;
                    $result .= '->';
                } elseif ($tok == '~') {
                    $result .= '.';
                } elseif ($tok == '[') {
                    $result .= $tok;
                } elseif ($tok == ']') {
                    $result .= $tok;
                } elseif ($getAsArray && $tok == ',') {
                    $results[] = $result;
                    $result = '';
                } else {
                    $result .= $tok;
                }
                // $first = false;
            }
        }
        if (! $getAsArray) {
            return $result;
        } else {
            if ($result != '') {
                $results[] = $result;
            }
            return $results;
        }
    }

    /**
     * Update the current stack of modifiers from another compiler.
     */
    protected function updateModifierStack($compiler)
    {
        foreach ($compiler->_usedModifiers as $_um) {
            if (! in_array($_um, $this->_usedModifiers)) {
                $this->_usedModifiers[] = $_um;
            }
        }
    }
}
