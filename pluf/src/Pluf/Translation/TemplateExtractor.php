<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
#
# Plume Framework is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume Framework is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

/**
 * Class to extract the translation strings from the template.
 * 
 * Based on Pluf_Template_Compiler with code:
 *   Copyright (C) 2006 Laurent Jouanneau.
 */
class Pluf_Translation_TemplateExtractor
{
    
    /** 
     * Store the literal blocks. 
     **/
    protected $_literals;
    
    /** 
     * Variables. 
     */
    protected $_vartype = array(T_CHARACTER, T_CONSTANT_ENCAPSED_STRING, 
                                T_DNUMBER, T_ENCAPSED_AND_WHITESPACE, 
                                T_LNUMBER, T_OBJECT_OPERATOR, T_STRING, 
                                T_WHITESPACE, T_ARRAY);

    /** 
     * Assignation operators. 
     */
    protected $_assignOp = array(T_AND_EQUAL, T_DIV_EQUAL, T_MINUS_EQUAL, 
                                 T_MOD_EQUAL, T_MUL_EQUAL, T_OR_EQUAL, 
                                 T_PLUS_EQUAL, T_PLUS_EQUAL, T_SL_EQUAL, 
                                 T_SR_EQUAL, T_XOR_EQUAL);

    /** 
     * Operators. 
     */
    protected  $_op = array(T_BOOLEAN_AND, T_BOOLEAN_OR, T_EMPTY, T_INC, 
                            T_ISSET, T_IS_EQUAL, T_IS_GREATER_OR_EQUAL, 
                            T_IS_IDENTICAL, T_IS_NOT_EQUAL, T_IS_NOT_IDENTICAL,
                            T_IS_SMALLER_OR_EQUAL, T_LOGICAL_AND, T_LOGICAL_OR,
                            T_LOGICAL_XOR, T_SR, T_SL, T_DOUBLE_ARROW);

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
    protected $_modifier = array('upper' => 'strtoupper', 
                                 'lower' => 'strtolower',
                                 'escxml' => 'htmlspecialchars', 
                                 'escape' => 'Pluf_Template_htmlspecialchars',
                                 'strip_tags' => 'strip_tags', 
                                 'escurl' => 'rawurlencode',
                                 'capitalize' => 'ucwords',
                                 // Not var_export because of recursive issues.
                                 'debug' => 'print_r', 
                                 'fulldebug' => 'var_export',
                                 'count' => 'count',
                                 'nl2br' => 'nl2br',
                                 'trim' => 'trim',
                                 'unsafe' => 'Pluf_Template_unsafe',
                                 'safe' => 'Pluf_Template_unsafe',
                                 'date' => 'Pluf_Template_dateFormat',
                                 'time' => 'Pluf_Template_timeFormat',
                                 );

    /**
     * After the compilation is completed, this contains the list of
     * modifiers used in the template. The GetCompiledTemplate method
     * will add a series of Pluf::loadFunction at the top to preload
     * these modifiers.
     */
    public $_usedModifiers = array();

    /**
     * Default allowed extra tags/functions. 
     *
     * These default tags are merged with the 'template_tags' defined
     * in the configuration of the application.
     */
    protected $_allowedTags = array(
                                    'url' => 'Pluf_Template_Tag_Url',
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
     * Template content. It can be set directly from a string.
     */
    public $templateContent = '';

    /**
     * Construct the compiler.
     *
     * @param string Basename of the template file.
     * @param array Base folders in which the templates files 
     *              should be found. (array())
     * @param bool Load directly the template content. (true)
     */
    function __construct($template_file, $folders=array(), $load=true)
    {
        $allowedtags = Pluf::f('template_tags', array());
        $this->_allowedTags = array_merge($allowedtags, $this->_allowedTags);
        $modifiers = Pluf::f('template_modifiers', array());
        $this->_modifier = array_merge($modifiers, $this->_modifier);

        foreach ($this->_allowedTags as $name=>$model) {
            $this->_extraTags[$name] = new $model();
        }
        $this->_sourceFile = $template_file;
        $this->_allowedInVar = array_merge($this->_vartype, $this->_op);
        $this->_allowedInExpr = array_merge($this->_vartype, $this->_op);
        $this->_allowedAssign = array_merge($this->_vartype, $this->_assignOp, 
                                            $this->_op);
        $this->templateFolders = $folders;
        if ($load) {
            $this->loadTemplateFile($template_file);
        }
    }

    /**
     * Get blocktrans.
     */
    function getBlockTrans()
    {
        $tplcontent = $this->templateContent;
        $tplcontent = preg_replace('!{\*(.*?)\*}!s', '', $tplcontent);
        $match = array();
        preg_match_all('!{blocktrans(.*?){/blocktrans}!s', $tplcontent, $match);
        $res = array();
        foreach ($match[1] as $m) {
            $res[] = '{blocktrans'.$m.'{/blocktrans}';
        }
        return $res;
    }

    /**
     * Get simple trans call.
     */
    function getSimpleTrans()
    {
        $tplcontent = $this->templateContent;
        $tplcontent = preg_replace('!{\*(.*?)\*}!s', '', $tplcontent);
        $match = array();
        preg_match_all('!{trans(.*?)}!s', $tplcontent, $match);
        $res = array();
        foreach ($match[1] as $m) {
            $res[] = '{trans'.$m.'}';
        }
        return $res;
    }

    /**
     * Compile the template into a file ready to parse with xgettext.
     *
     * @return string PHP code of the compiled template.
     */
    function compile() 
    {
        $result = '';
        $blocktrans = $this->getBlockTrans();
        // Parse the blocktrans
        foreach ($blocktrans as $block) {
            $match = array();
            if (preg_match('!{blocktrans(.*?)}(.*?){plural}(.*?){/blocktrans}!s', $block, $match)) {
                $sing = $match[2];
                $plural = $match[3];
                $sing = preg_replace_callback('/{((.).*?)}/s', 
                                               array($this, '_callbackInTransBlock'), 
                                               $sing);
                $plural = preg_replace_callback('/{((.).*?)}/s', 
                                               array($this, '_callbackInTransBlock'), 
                                               $plural);
                $result .= '_n(\''.addcslashes($sing, "'").'\', \''.addcslashes($plural, "'").'\', $n);'."\n";
            } elseif (preg_match('!{blocktrans}(.*?){/blocktrans}!s', $block, $match)) {
                $sing = preg_replace_callback('/{((.).*?)}/s', 
                                               array($this, '_callbackInTransBlock'), 
                                               $match[1]);
                $result .= '__(\''.addcslashes($sing, "'").'\');'."\n";
            }
        }

        $simpletrans = $this->getSimpleTrans();
        foreach ($simpletrans as $content) {
            $result .= preg_replace_callback('/{((.).*?)}/s', 
                                             array($this, '_callback'), 
                                             $content);
        }
        return '<?php # This is not a valid php code. It is just for gettext use'."\n\n".$result.' ?>';
    }


    /**
     * Load a template file.
     *
     * The path to the file to load is relative and the file is found
     * in one of the $templateFolders array of folders.
     *
     * @param string Relative path of the file to load.
     */
    function loadTemplateFile($file)
    {
        // FIXME: Very small security check, could be better.
        if (strpos($file, '..') !== false) {
            throw new Exception(sprintf(__('Template file contains invalid characters: %s'), $file));
        }
        foreach ($this->templateFolders as $folder) {
            if (file_exists($folder.'/'.$file)) {
                $this->templateContent = file_get_contents($folder.'/'.$file);
                return;
            }
        }
        // File not found in all the folders.
        throw new Exception(sprintf(__('Template file not found: %s'), $file));
    }

    function _callback($matches) 
    {
        list(,$tag, $firstcar) = $matches;
        if ($firstcar != 't') {
            trigger_error(sprintf(__('Invalid tag in translation extractor: %s'), $tag), E_USER_ERROR);
            return '';
        }
        $this->_currentTag = $tag;
        if (!preg_match('/^(\/?[a-zA-Z0-9_]+)(?:(?:\s+(.*))|(?:\((.*)\)))?$/', $tag, $m)) {
            trigger_error(sprintf(__('Invalid function syntax: %s'), $tag), E_USER_ERROR);
            return '';
        }
        if (count($m) == 4){
            $m[2] = $m[3];
        }
        if (!isset($m[2])) $m[2] = '';
        if ($m[1] == 'trans') {
            return $this->_parseFunction($m[1], $m[2]);
        } 
        return '';
    }

    function _callbackInTransBlock($matches) 
    {
        list(,$tag, $firstcar) = $matches;
        if (!preg_match('/^\$|[\'"]|[a-zA-Z\/]$/', $firstcar)) {
            trigger_error(sprintf(__('Invalid tag syntax: %s'), $tag), E_USER_ERROR);
            return '';
        }
        $this->_currentTag = $tag;
        if ($firstcar == '$') {
            $tok = explode('|', $tag);
            $this->_transStack[substr($tok[0], 1)] = $this->_parseVariable($tag);
            return '%%'.substr($tok[0], 1).'%%';
        }
        return '';
    }

    function _parseVariable($expr)
    {
        $tok = explode('|', $expr);
        $res = $this->_parseFinal(array_shift($tok), $this->_allowedInVar);
        // We do not take into account the modifiers.
        return $res;
    }

    function _parseFunction($name, $args)
    {
        switch ($name) {
        case 'trans':
            $argfct = $this->_parseFinal($args, $this->_allowedAssign); 
            $res = '__('.$argfct.');'."\n";
            break;
        default:
            $res = '';
            break;
        }
        return $res;
    }

    /*

    -------
    if:        op, autre, var
    foreach:   T_AS, T_DOUBLE_ARROW, T_VARIABLE, @locale@
    for:       autre, fin_instruction
    while:     op, autre, var
    assign:    T_VARIABLE puis assign puis autre, ponctuation, T_STRING
    echo:      T_VARIABLE/@locale@ puis autre + ponctuation
    modificateur: serie de autre séparé par une virgule

    tous : T_VARIABLE, @locale@

    */

    function _parseFinal($string, $allowed=array(), 
                         $exceptchar=array(';'))
    {
        $tokens = token_get_all('<?php '.$string.'?>');
        $result = '';
        $first = true;
        $inDot = false;
        $firstok = array_shift($tokens);
        $afterAs = false;
        $f_key = '';
        $f_val = '';
        $results = array();

        // il y a un bug, parfois le premier token n'est pas T_OPEN_TAG...
        if ($firstok == '<' && $tokens[0] == '?' && is_array($tokens[1])
            && $tokens[1][0] == T_STRING && $tokens[1][1] == 'php') {
            array_shift($tokens);
            array_shift($tokens);
        }
        foreach ($tokens as $tok) {
            if (is_array($tok)) {
                list($type, $str) = $tok;
                $first = false;
                if($type == T_CLOSE_TAG){
                    continue;
                }
                if ($type == T_AS) {
                    $afterAs = true;
                }
                if ($inDot) {
                    $result .= $str;
                } elseif ($type == T_VARIABLE) {
                    $result .= '$t->_vars[\''.substr($str, 1).'\']';
                } elseif ($type == T_WHITESPACE || in_array($type, $allowed)) {
                    $result .= $str;
                } else {
                    trigger_error(sprintf(__('Invalid syntax: (%s) %s.'), $this->_currentTag, $str), E_USER_ERROR);
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
                } elseif ($tok =='[') {
                    $result.=$tok;
                } elseif ($tok ==']') {
                    $result.=$tok;
                } elseif ($getAsArray && $tok == ',') {
                    $results[]=$result;
                    $result='';
                } else {
                    $result .= $tok;
                }
                $first = false;
            }
        }
        return $result;
    }
}

