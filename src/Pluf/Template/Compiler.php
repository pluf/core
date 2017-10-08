<?php

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
class Pluf_Template_Compiler
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
        'dump' => 'Pluf_Template_varExport',
        'fulldebug' => 'var_export',
        'nl2br' => 'Pluf_Template_nl2br',
        'trim' => 'trim',
        'ltrim' => 'ltrim',
        'rtrim' => 'rtrim',
        'unsafe' => 'Pluf_Template_unsafe',
        'safe' => 'Pluf_Template_unsafe',
        'date' => 'Pluf_Template_dateFormat',
        'time' => 'Pluf_Template_timeFormat',
        'dateago' => 'Pluf_Template_dateAgo',
        'timeago' => 'Pluf_Template_timeAgo',
        'email' => 'Pluf_Template_safeEmail',
        'first' => 'Pluf_Template_first',
        'last' => 'Pluf_Template_last'
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
    protected $_allowedTags = array(
        'url' => 'Pluf_Template_Tag_Url',
        'aurl' => 'Pluf_Template_Tag_Rurl',
        'media' => 'Pluf_Template_Tag_MediaUrl',
        'amedia' => 'Pluf_Template_Tag_RmediaUrl',
        'aperm' => 'Pluf_Template_Tag_APerm',
        'getmsgs' => 'Pluf_Template_Tag_Messages'
    );

    /**
     * During compilation, all the tags are created once so to query
     * their interface easily.
     */
    protected $_extraTags = array();

    /**
     * The block stack to see if the blocks are correctly closed.
     */
    protected $_blockStack = array();

    /**
     * Special stack for the translation handling in blocktrans.
     */
    protected $_transStack = array();

    protected $_transPlural = false;

    /**
     * Current template source file.
     */
    protected $_sourceFile;

    /**
     * Current tag.
     */
    protected $_currentTag;

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
        Pluf_Signal::send('Pluf_Template_Compiler::construct_template_tags_modifiers', 'Pluf_Template_Compiler', $params);
        $this->_allowedTags = array_merge($this->_allowedTags, $params['tags'], Pluf::f('template_tags', array()));
        $this->_modifier = array_merge($this->_modifier, $params['modifiers'], Pluf::f('template_modifiers', array()));
        foreach ($this->_allowedTags as $name => $model) {
            $this->_extraTags[$name] = new $model();
        }
        $this->_sourceFile = $template_file;
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
        preg_match_all('!{literal}(.*?){/literal}!s', $tplcontent, $_match);
        $this->_literals = $_match[1];
        $tplcontent = preg_replace("!{literal}(.*?){/literal}!s", '{literal}', $tplcontent);
        // Core regex to parse the template
        $result = preg_replace_callback('/{((.).*?)}/s', array(
            $this,
            '_callback'
        ), $tplcontent);
        if (count($this->_blockStack)) {
            trigger_error(sprintf(__('End tag of a block missing: %s'), end($this->_blockStack)), E_USER_ERROR);
        }
        return $result;
    }

    /**
     * Get a cleaned compile template.
     */
    function getCompiledTemplate()
    {
        $result = $this->compile();
        if (count($this->_usedModifiers)) {
            $code = array();
            foreach ($this->_usedModifiers as $modifier) {
                $code[] = 'Pluf::loadFunction(\'' . $modifier . '\'); ';
            }
            $result = '<?php ' . implode("\n", $code) . '?>' . $result;
        }
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
            $this->_sourceFile = $this->_extendedTemplate;
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
            throw new Exception(sprintf(__('Template file contains invalid characters: %s'), $file));
        }
        foreach ($this->templateFolders as $folder) {
            if (file_exists($folder . '/' . $file)) {
                $this->templateContent = file_get_contents($folder . '/' . $file);
                return;
            }
        }
        // File not found in all the folders.
        throw new Exception(sprintf(__('Template file not found: %s'), $file));
    }

    function _callback($matches)
    {
        list (, $tag, $firstcar) = $matches;
        if (! preg_match('/^\$|[\'"]|[a-zA-Z\/]$/', $firstcar)) {
            trigger_error(sprintf(__('Invalid tag syntax: %s'), $tag), E_USER_ERROR);
            return '';
        }
        $this->_currentTag = $tag;
        if (in_array($firstcar, array(
            '$',
            '\'',
            '"'
        ))) {
            if ('blocktrans' !== end($this->_blockStack)) {
                return '<?php Pluf_Template_safeEcho(' . $this->_parseVariable($tag) . '); ?>';
            } else {
                $tok = explode('|', $tag);
                $this->_transStack[substr($tok[0], 1)] = $this->_parseVariable($tag);
                return '%%' . substr($tok[0], 1) . '%%';
            }
        } else {
            if (! preg_match('/^(\/?[a-zA-Z0-9_]+)(?:(?:\s+(.*))|(?:\((.*)\)))?$/', $tag, $m)) {
                trigger_error(sprintf(__('Invalid function syntax: %s'), $tag), E_USER_ERROR);
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
            if (! preg_match('/^(\w+)(?:\:(.*))?$/', $modifier, $m)) {
                trigger_error(sprintf(__('Invalid modifier syntax: (%s) %s'), $this->_currentTag, $modifier), E_USER_ERROR);
                return '';
            }
            $targs = array(
                $res
            );
            if (isset($m[2])) {
                $res = $this->_modifier[$m[1]] . '(' . $res . ',' . $m[2] . ')';
            } else if (isset($this->_modifier[$m[1]])) {
                $res = $this->_modifier[$m[1]] . '(' . $res . ')';
            } else {
                trigger_error(sprintf(__('Unknown modifier: (%s) %s'), $this->_currentTag, $m[1]), E_USER_ERROR);
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
                array_push($this->_blockStack, 'if');
                break;
            case 'else':
                if (end($this->_blockStack) != 'if') {
                    trigger_error(sprintf(__('End tag of a block missing: %s'), end($this->_blockStack)), E_USER_ERROR);
                }
                $res = 'else: ';
                break;
            case 'elseif':
                if (end($this->_blockStack) != 'if') {
                    trigger_error(sprintf(__('End tag of a block missing: %s'), end($this->_blockStack)), E_USER_ERROR);
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
                array_push($this->_blockStack, 'foreach');
                break;
            case 'while':
                $res = 'while(' . $this->_parseFinal($args, $this->_allowedInExpr) . '):';
                array_push($this->_blockStack, 'while');
                break;
            case '/foreach':
            case '/if':
            case '/while':
                $short = substr($name, 1);
                if (end($this->_blockStack) != $short) {
                    trigger_error(sprintf(__('End tag of a block missing: %s'), end($this->_blockStack)), E_USER_ERROR);
                }
                array_pop($this->_blockStack);
                $res = 'end' . $short . '; ';
                break;
            case 'assign':
                $res = $this->_parseFinal($args, $this->_allowedAssign) . '; ';
                break;
            case 'literal':
                if (count($this->_literals)) {
                    $res = '?>' . array_shift($this->_literals) . '<?php ';
                } else {
                    trigger_error(__('End tag of a block missing: literal'), E_USER_ERROR);
                }
                break;
            case '/literal':
                trigger_error(__('Start tag of a block missing: literal'), E_USER_ERROR);
                break;
            case 'block':
                $res = '?>' . $this->_extendBlocks[$args] . '<?php ';
                break;
            case 'superblock':
                $res = '?>~~{~~superblock~~}~~<?php ';
                break;
            case 'trans':
                $argfct = $this->_parseFinal($args, $this->_allowedAssign);
                $res = 'echo(__(' . $argfct . '));';
                break;
            case 'blocktrans':
                array_push($this->_blockStack, 'blocktrans');
                $res = '';
                $this->_transStack = array();
                if ($args) {
                    $this->_transPlural = true;
                    $_args = $this->_parseFinal($args, $this->_allowedAssign, array(
                        ';',
                        '[',
                        ']'
                    ), true);
                    $res .= '$_b_t_c=' . trim(array_shift($_args)) . '; ';
                }
                $res .= 'ob_start(); ';
                break;
            case '/blocktrans':
                $short = substr($name, 1);
                if (end($this->_blockStack) != $short) {
                    trigger_error(sprintf(__('End tag of a block missing: %s'), end($this->_blockStack)), E_USER_ERROR);
                }
                $res = '';
                if ($this->_transPlural) {
                    $res .= '$_b_t_p=ob_get_contents(); ob_end_clean(); echo(';
                    $res .= 'Pluf_Translation::sprintf(_n($_b_t_s, $_b_t_p, $_b_t_c), array(';
                    $_tmp = array();
                    foreach ($this->_transStack as $key => $_trans) {
                        $_tmp[] = '\'' . addslashes($key) . '\' => Pluf_Template_safeEcho(' . $_trans . ', false)';
                    }
                    $res .= implode(', ', $_tmp);
                    unset($_trans, $_tmp);
                    $res .= ')));';
                    $this->_transStack = array();
                } else {
                    $res .= '$_b_t_s=ob_get_contents(); ob_end_clean(); ';
                    if (count($this->_transStack) == 0) {
                        $res .= 'echo(__($_b_t_s)); ';
                    } else {
                        $res .= 'echo(Pluf_Translation::sprintf(__($_b_t_s), array(';
                        $_tmp = array();
                        foreach ($this->_transStack as $key => $_trans) {
                            $_tmp[] = '\'' . addslashes($key) . '\' => Pluf_Template_safeEcho(' . $_trans . ', false)';
                        }
                        $res .= implode(', ', $_tmp);
                        unset($_trans, $_tmp);
                        $res .= '))); ';
                        $this->_transStack = array();
                    }
                }
                $this->_transPlural = false;
                array_pop($this->_blockStack);
                break;
            case 'plural':
                $res = '$_b_t_s=ob_get_contents(); ob_end_clean(); ob_start(); ';
                break;
            case 'include':
                // XXX fixme: Will need some security check, when online
                // editing.
                $argfct = preg_replace('!^[\'"](.*)[\'"]$!', '$1', $args);
                $_comp = new Pluf_Template_Compiler($argfct, $this->templateFolders);
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
                if (! isset($this->_allowedTags[$name])) {
                    trigger_error(sprintf(__('The function tag "%s" is not allowed.'), $name), E_USER_ERROR);
                }
                $argfct = $this->_parseFinal($args, $this->_allowedAssign);
                // $argfct is a string that can be copy/pasted in the PHP code
                // but we need the array of args.
                $res = '';
                if (isset($this->_extraTags[$name])) {
                    if (false == $_end) {
                        if (method_exists($this->_extraTags[$name], 'start')) {
                            $res .= '$_extra_tag = Pluf::factory(\'' . $this->_allowedTags[$name] . '\', $t); $_extra_tag->start(' . $argfct . '); ';
                        }
                        if (method_exists($this->_extraTags[$name], 'genStart')) {
                            $res .= $this->_extraTags[$name]->genStart();
                        }
                    } else {
                        if (method_exists($this->_extraTags[$name], 'end')) {
                            $res .= '$_extra_tag = Pluf::factory(\'' . $this->_allowedTags[$name] . '\', $t); $_extra_tag->end(' . $argfct . '); ';
                        }
                        if (method_exists($this->_extraTags[$name], 'genEnd')) {
                            $res .= $this->_extraTags[$name]->genEnd();
                        }
                    }
                }
                if ($res == '') {
                    trigger_error(sprintf(__('The function tag "{%s ...}" is not supported.'), $oname), E_USER_ERROR);
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
    function _parseFinal($string, $allowed = array(), $exceptchar = array(';'), $getAsArray = false)
    {
        $tokens = token_get_all('<?php ' . $string . '?>');
        $result = '';
        $first = true;
        $inDot = false;
        $firstok = array_shift($tokens);
        $afterAs = false;
        $f_key = '';
        $f_val = '';
        $results = array();
        
        // il y a un bug, parfois le premier token n'est pas T_OPEN_TAG...
        if ($firstok == '<' && $tokens[0] == '?' && is_array($tokens[1]) && $tokens[1][0] == T_STRING && $tokens[1][1] == 'php') {
            array_shift($tokens);
            array_shift($tokens);
        }
        foreach ($tokens as $tok) {
            if (is_array($tok)) {
                list ($type, $str) = $tok;
                $first = false;
                if ($type == T_CLOSE_TAG) {
                    continue;
                }
                if ($type == T_AS) {
                    $afterAs = true;
                }
                if ($type == T_STRING && $inDot) {
                    $result .= $str;
                } elseif ($type == T_VARIABLE) {
                    // $result .= '$t->_vars[\''.substr($str, 1).'\']';
                    $result .= '$t->_vars->' . substr($str, 1);
                } elseif ($type == T_WHITESPACE || in_array($type, $allowed)) {
                    $result .= $str;
                } else {
                    trigger_error(sprintf(__('Invalid syntax: (%s) %s.'), $this->_currentTag, $str . ' tokens' . var_export($tokens, true)), E_USER_ERROR);
                    return '';
                }
            } else {
                if (in_array($tok, $exceptchar)) {
                    trigger_error(sprintf(__('Invalid character: (%s) %s.'), $this->_currentTag, $tok), E_USER_ERROR);
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
                $first = false;
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
