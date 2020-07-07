<?php
/*
 * This file is part of bootstrap Framework, a simple PHP Application Framework.
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

use Pluf\Template\Compiler;
use Pluf\Test\PlufTestCase;
use Pluf;

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class MethodCompile2Test extends PlufTestCase
{

    protected function setUp()
    {
        Pluf::start(__DIR__ . '/../../conf/config.php');
    }

    public function testCompile()
    {
        $compiler = new Compiler('tpl-extends.html', array(
            dirname(__FILE__)
        ));
        $compiled = file_get_contents(dirname(__FILE__) . '/tpl-extends.compiled.html');
        $this->assertEquals($compiled, $compiler->getCompiledTemplate() . "\n");
    }

    public function testCompileMultiFolders()
    {
        $folders = array(
            dirname(__FILE__) . '/tpl1',
            dirname(__FILE__) . '/tpl2'
        );
        $compiler = new Compiler('tpl-extends.html', $folders);
        $compiled = file_get_contents(dirname(__FILE__) . '/tpl-extends.compiled.html');
        $this->assertEquals($compiled, $compiler->getCompiledTemplate() . "\n");
    }
}

?>