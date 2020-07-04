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
namespace Pluf\Test\Template\Compiler;

require_once 'Pluf.php';
use PHPUnit\Framework\TestCase;
use Pluf\Template\Compiler;
use Pluf;

class MethodCompileTest extends TestCase
{

    protected function setUp()
    {
        Pluf::start(__DIR__ . '/../../conf/config.php');
    }

    public function testCompile()
    {
        $compiler = new Compiler('tpl-test1.html', array(
            dirname(__FILE__)
        ));
        $this->assertEquals('<?php \Pluf\Template::safeEcho($t->_vars->toto); ?>' . "\n\n", $compiler->getCompiledTemplate());
    }

    public function testCompile2()
    {
        $compiler = new Compiler('tpl-test2.html', array(
            dirname(__FILE__)
        ));
        $this->assertEquals('<?php \Pluf\Template::safeEcho($t->_vars->toto); ?>' . "\n\n", $compiler->getCompiledTemplate());
    }

    public function testCompile3()
    {
        $compiler = new Compiler('tpl-test3.html', array(
            dirname(__FILE__)
        ));
        $res = file_get_contents(dirname(__FILE__) . '/tpl-test3.compiled.html');
        $this->assertEquals($res, $compiler->getCompiledTemplate());
    }

    public function testCompile5()
    {
        $compiler = new Compiler('tpl-test5.html', array(
            dirname(__FILE__)
        ));
        $this->assertEquals("<?php \$t->_vars->string3 = \$t->_vars->string2.\$t->_vars->string1; ?>", $compiler->getCompiledTemplate());
    }

    public function testCompileString()
    {
        $compiler = new Compiler('tpl-teststring.html', array(
            dirname(__FILE__)
        ));
        $this->assertEquals('<?php \Pluf\Template::safeEcho("this is a string"); ?>' . "\n\n", $compiler->getCompiledTemplate());
    }

    public function testCompileStringModifier()
    {
        $compiler = new Compiler('tpl-teststring-modifier.html', array(
            dirname(__FILE__)
        ));
        $this->assertEquals('<?php \Pluf\Template::safeEcho(\Pluf\Template::unsafe("this is a string")); ?>' . "\n\n", $compiler->getCompiledTemplate());
    }

    public function testCompileRemoveComments()
    {
        $compiler = new Compiler('dummy', array(), false);
        $compiler->templateContent = 'you {* this is a comment *} boum';
        $this->assertEquals('you  boum', $compiler->getCompiledTemplate());
    }

    public function testCompileRemovePhpCode()
    {
        $compiler = new Compiler('dummy', array(), false);
        $compiler->templateContent = 'you <?php exit(); ' . "\n\n" . ' ?> boum';
        $this->assertEquals('you  boum', $compiler->getCompiledTemplate());
    }

    public function testCompileSimpleAssignInLoop()
    {
        $compiler = new Compiler('dummy', array(), false);
        $compiler->templateContent = '{assign $counter = 1}
{foreach $lines as $line}
{$counter}
{assign $counter = $counter+1}
{/foreach}';
        $this->assertEquals('<?php $t->_vars->counter = 1; ?>

<?php foreach ($t->_vars->lines as $t->_vars->line): ?>

<?php \Pluf\Template::safeEcho($t->_vars->counter); ?>

<?php $t->_vars->counter = $t->_vars->counter+1; ?>

<?php endforeach; ?>', $compiler->getCompiledTemplate());
    }
}

