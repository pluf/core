<?php

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace Pluf;

use Pluf\Template\Compiler;
use Pluf\Template\Context;
use Pluf\Template\SafeString;

/**
 * Render a template file.
 */
class Template
{

    public $tpl = '';

    public $folders = array();

    public $cache = '';

    public $compiled_template = '';

    public $template_content = '';

    public $context = null;

    public $class = '';

    /**
     * Constructor.
     *
     * If the folder name is not provided, it will default to
     * Pluf::f('template_folders')
     * If the cache folder name is not provided, it will default to
     * Pluf::f('tmp_folder')
     *
     * @param
     *            string Template name.
     * @param
     *            string Template folder paths (null)
     * @param
     *            string Cache folder name (null)
     */
    function __construct($template, $folders = null, $cache = null)
    {
        $this->tpl = $template;
        if (null == $folders) {
            $this->folders = Bootstrap::f('template_folders');
        } else {
            $this->folders = $folders;
        }
        if (null == $cache) {
            $this->cache = Bootstrap::f('tmp_folder');
        } else {
            $this->cache = $cache;
        }
        if (defined('IN_UNIT_TESTS')) {
            if (! isset($GLOBALS['_PX_tests_templates'])) {
                $GLOBALS['_PX_tests_templates'] = array();
            }
        }
        $this->compiled_template = $this->getCompiledTemplateName();
        $b = $this->compiled_template[1];
        $this->class = 'Pluf_Template_' . $b;
        $this->compiled_template = $this->compiled_template[0];
        if (! class_exists($this->class, false)) {
            if (! file_exists($this->compiled_template) or Bootstrap::f('debug')) {
                $compiler = new Compiler($this->tpl, $this->folders);
                $this->template_content = $compiler->getCompiledTemplate();
                $this->write($b);
            }
            include $this->compiled_template;
        }
    }

    /**
     * Render the template with the given context and return the content.
     *
     * @param
     *            Object Context.
     */
    function render($c = null)
    {
        if (defined('IN_UNIT_TESTS')) {
            $GLOBALS['_PX_tests_templates'][] = $this;
        }
        if (null == $c) {
            $c = new Context();
        }
        $this->context = $c;
        ob_start();
        $t = $c;
        try {
            call_user_func(array(
                $this->class,
                'render'
            ), $t);
            // include $this->compiled_template;
        } catch (Exception $e) {
            ob_clean();
            throw $e;
        }
        $a = ob_get_contents();
        ob_end_clean();
        return $a;
    }

    /**
     * Get the full name of the compiled template.
     *
     * Ends with .phps to prevent execution from outside if the cache folder
     * is not secured but to still have the syntax higlightings by the tools
     * for debugging.
     *
     * @return string Full path to the compiled template
     */
    function getCompiledTemplateName()
    {
        // The compiled template not only depends on the file but also
        // on the possible folders in which it can be found.
        $_tmp = var_export($this->folders, true);
        return array(
            $this->cache . '/Pluf_Template-' . md5($_tmp . $this->tpl) . '.phps',
            md5($_tmp . $this->tpl)
        );
    }

    /**
     * Write the compiled template in the cache folder.
     * Throw an exception if it cannot write it.
     *
     * @return bool Success in writing
     */
    function write($name)
    {
        $this->template_content = '<?php class Pluf_Template_' . $name . ' {
public static function render($c) {$t = $c; ?>' . $this->template_content . '<?php } } ';
        // mode "a" to not truncate before getting the lock
        $fp = @fopen($this->compiled_template, 'a');
        if ($fp !== false) {
            // Exclusive lock on writing
            flock($fp, LOCK_EX);
            // We have the unique pointeur, we truncate
            ftruncate($fp, 0);
            // Go back to the start of the file like a +w
            rewind($fp);
            fwrite($fp, $this->template_content, strlen($this->template_content));
            // Lock released, read access is possible
            flock($fp, LOCK_UN);
            fclose($fp);
            @chmod($this->compiled_template, 0777);
            return true;
        }
        throw new Exception(sprintf('Cannot write the compiled template: %s', $this->compiled_template));
    }

    public static function markSafe($string)
    {
        return new SafeString($string, true);
    }
}

