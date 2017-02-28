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
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\IncompleteTestError;
require_once 'Pluf.php';


class LatexEquationTest extends PHPUnit_Framework_TestCase 
{
    public $output = '/tmp/latex';

    protected function setUp()
    {
        Pluf::start(dirname(__FILE__).'/../conf/pluf.config.php');
        @mkdir($this->output);
    }

    protected function tearDown()
    {
        @rmdir($this->output);
    }

    public function testSimpleOutput()
    {
        $math = new Pluf_Text_LaTeX_Equation($this->output);
        $math->bg_color = 'cc0000';
        $this->assertEquals(true, $math->render('E = mc^2 - x_2 + \frac{x_2}{1}'));
        @unlink($math->output_path);
        $this->assertEquals('/tmp/latex/5c88d3a35a859f05a020a544717297b6.png',
                            $math->output_path);
        $this->assertEquals(true, $math->render('x \\implies y'));
        $file = $math->output_path;
        @unlink($file);
        $this->assertEquals('/tmp/latex/a2b69ed6f661f926606289959937f9ee.png',
                            $file);
        $this->assertEquals(true, $math->render('\frac{m_0}{\sqrt{1-\frac{v^2}{c^2}}}'));
        $file = $math->output_path;
        @unlink($file);
        $this->assertEquals('/tmp/latex/3a511bb54f63afb2e4c44afa02e4b662.png',
                            $file);
        $this->assertEquals(true, $math->render('G_{ab}^{(1)} = -\frac{1}{2}\partial^c\partial_c \bar{\gamma}_{ab} + \partial^c\partial_{(b}\bar{\gamma}_{a)c} -\frac{1}{2}\eta_{ab}\partial^c\partial^d\bar{\gamma}_{cd} = 8\pi T_{ab}'));
        $file = $math->output_path;
        @unlink($file);
        $this->assertEquals('/tmp/latex/92a8360d59b243991ff4a3f509c6f3e5.png',
                            $file);
    }
}