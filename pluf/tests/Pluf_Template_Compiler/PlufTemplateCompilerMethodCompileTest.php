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

error_reporting(E_ALL | E_STRICT);

$path = dirname(__FILE__).'/../../src/';
set_include_path(get_include_path().PATH_SEPARATOR.$path);

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'PHPUnit/Framework/IncompleteTestError.php';

require_once 'Pluf.php';

class PlufTemplateCompilerMethodCompileTest extends PHPUnit_Framework_TestCase {
    
    protected function setUp()
    {
        Pluf::start(dirname(__FILE__).'/../conf/pluf.config.php');
    }

    public function testCompile()
    {
        $compiler = new Pluf_Template_Compiler('tpl-test1.html', array(dirname(__FILE__)));
        $this->assertEquals('<?php Pluf_Template_safeEcho($t->_vars[\'toto\']); ?>'."\n\n", 
                            $compiler->getCompiledTemplate());
    }

    public function testCompile2()
    {
        $compiler = new Pluf_Template_Compiler('tpl-test2.html', array(dirname(__FILE__)));
        $this->assertEquals('<?php Pluf_Template_safeEcho($t->_vars[\'toto\']); ?>'."\n\n", 
                            $compiler->getCompiledTemplate());
    }

    public function testCompile3()
    {
        $compiler = new Pluf_Template_Compiler('tpl-test3.html', array(dirname(__FILE__)));
        $res = file_get_contents(dirname(__FILE__).'/tpl-test3.compiled.html');
        $this->assertEquals($res, $compiler->getCompiledTemplate());
    }

    public function testCompile5()
    {
        $compiler = new Pluf_Template_Compiler('tpl-test5.html', array(dirname(__FILE__)));
        $this->assertEquals("<?php \$t->_vars['string3'] = \$t->_vars['string2'].\$t->_vars['string1']; ?>", $compiler->getCompiledTemplate());
    }

    public function testCompileString()
    {
        $compiler = new Pluf_Template_Compiler('tpl-teststring.html', array(dirname(__FILE__)));
        $this->assertEquals('<?php Pluf_Template_safeEcho("this is a string"); ?>'."\n\n", $compiler->getCompiledTemplate());
    }

    public function testCompileStringModifier()
    {
        $compiler = new Pluf_Template_Compiler('tpl-teststring-modifier.html', array(dirname(__FILE__)));
        $this->assertEquals('<?php Pluf::loadFunction(\'Pluf_Template_unsafe\');  Pluf_Template_safeEcho(Pluf_Template_unsafe("this is a string")); ?>'."\n\n", $compiler->getCompiledTemplate());
    }

    public function testCompileRemoveComments()
    {
        $compiler = new Pluf_Template_Compiler('dummy', array(), false);
        $compiler->templateContent = 'you {* this is a comment *} boum';
        $this->assertEquals('you  boum', $compiler->getCompiledTemplate());
    }

    public function testCompileRemovePhpCode()
    {
        $compiler = new Pluf_Template_Compiler('dummy', array(), false);
        $compiler->templateContent = 'you <?php exit(); '."\n\n".' ?> boum';
        $this->assertEquals('you  boum', $compiler->getCompiledTemplate());
    }

    public function testCompileSimpleBlockTrans()
    {
        $compiler = new Pluf_Template_Compiler('dummy', array(), false);
        $compiler->templateContent = '{blocktrans}Youpla boum {$toto} is here.{/blocktrans}';
        $this->assertEquals('<?php ob_start(); ?>Youpla boum %1$s is here.<?php $_b_t_s=ob_get_contents(); ob_end_clean(); echo(sprintf(__($_b_t_s), Pluf_Template_safeEcho($t->_vars[\'toto\'], false))); ?>',
                            $compiler->getCompiledTemplate());
    }

    public function testCompileSimpleBlockTransPlural()
    {
        $compiler = new Pluf_Template_Compiler('dummy', array(), false);
        $compiler->templateContent = '{blocktrans $count, $toto}Youpla boum {$toto} is here.{plural}Youpla boum {$counter} {$toto} are here.{/blocktrans}';
        $this->assertEquals('<?php $_b_t_c=$t->_vars[\'count\']; ob_start(); ?>Youpla boum %2$s is here.<?php $_b_t_s=ob_get_contents(); ob_end_clean(); ob_start(); ?>Youpla boum %1$d %2$s are here.<?php $_b_t_p=ob_get_contents(); ob_end_clean(); echo(sprintf(_n($_b_t_s, $_b_t_p, $_b_t_c), $_b_t_c, Pluf_Template_safeEcho($t->_vars[\'toto\'], false))); ?>',
                            $compiler->getCompiledTemplate());
    }

    public function testCompileSimpleBlockTransPluralMoreArgs()
    {
        $compiler = new Pluf_Template_Compiler('dummy', array(), false);
        $compiler->templateContent = '{blocktrans $count, $toto, $titi, $tata}Youpla boum {$toto} {$tata} is here.{plural}Youpla boum {$counter} {$toto} are here.{/blocktrans}';
        $this->assertEquals('<?php $_b_t_c=$t->_vars[\'count\']; ob_start(); ?>Youpla boum %2$s %4$s is here.<?php $_b_t_s=ob_get_contents(); ob_end_clean(); ob_start(); ?>Youpla boum %1$d %2$s are here.<?php $_b_t_p=ob_get_contents(); ob_end_clean(); echo(sprintf(_n($_b_t_s, $_b_t_p, $_b_t_c), $_b_t_c, Pluf_Template_safeEcho($t->_vars[\'toto\'], false), Pluf_Template_safeEcho($t->_vars[\'titi\'], false), Pluf_Template_safeEcho($t->_vars[\'tata\'], false))); ?>',
                            $compiler->getCompiledTemplate());
    }

    public function testCompileSimpleAssignInLoop()
    {
        $compiler = new Pluf_Template_Compiler('dummy', array(), false);
        $compiler->templateContent = '{assign $counter = 1}
{foreach $lines as $line}
{$counter}
{assign $counter = $counter+1}
{/foreach}';
        $this->assertEquals('<?php $t->_vars[\'counter\'] = 1; ?>

<?php foreach ($t->_vars[\'lines\'] as $t->_vars[\'line\']): ?>

<?php Pluf_Template_safeEcho($t->_vars[\'counter\']); ?>

<?php $t->_vars[\'counter\'] = $t->_vars[\'counter\']+1; ?>

<?php endforeach; ?>',
                            $compiler->getCompiledTemplate());
    }



}

