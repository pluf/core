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

class LatexOutputTest extends TestCase
{

    public $output = '/tmp/latex';

    protected function setUp ()
    {
        Pluf::start(dirname(__FILE__) . '/../conf/pluf.config.php');
        @mkdir($this->output);
    }

    protected function tearDown ()
    {
        @rmdir($this->output);
    }

    public function testSimpleOutput ()
    {
        $math = new Pluf_Text_LaTeX_Texvc('x_2', 
                array(
                        'output_dir' => $this->output
                ));
        $file = $math->render();
        @unlink($file);
        $this->assertEquals('/tmp/latex/8f43fce8dbdf3c4f8d0ac91f0de1d43d.png', 
                $file);
        $math->fragment = 'x \\implies y';
        $file = $math->render();
        @unlink($file);
        $this->assertEquals('/tmp/latex/cacfa4f67dd97bc8e402f36f13b0c265.png', 
                $file);
        $math->fragment = '\frac{m_0}{\sqrt{1-\frac{v^2}{c^2}}}';
        $file = $math->render();
        @unlink($file);
        $this->assertEquals('/tmp/latex/9e5f1ea82d57fe3c3eb2c324489b773b.png', 
                $file);
        $math->fragment = 'G_{ab}^{(1)} = -\frac{1}{2}\partial^c\partial_c \bar{\gamma}_{ab} + \partial^c\partial_{(b}\bar{\gamma}_{a)c} -\frac{1}{2}\eta_{ab}\partial^c\partial^d\bar{\gamma}_{cd} = 8\pi T_{ab}';
        $file = $math->render();
        @unlink($file);
        $this->assertEquals('/tmp/latex/1686bec7fd692f30da80145b77041f2b.png', 
                $file);
    }
}