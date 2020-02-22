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
namespace PlufTests;

use PHPUnit\Framework\TestCase;
use Pluf\Bootstrap;

class PlufTest extends TestCase
{

    /**
     *
     * @test
     */
    public function testF()
    {
        $conf = include __DIR__ . '/../conf/config.php';
        $conf['test-var'] = false;
        Bootstrap::start($conf);
        $this->assertEquals(Bootstrap::f('test-var'), false);
    }

    /**
     *
     * @test
     */
    public function testPf()
    {
        Bootstrap::start(array(
            'a' => true,
            'a.a' => false,
            'a.b' => false,
            'a.c' => false
        ));

        // no strip
        $configs = Bootstrap::pf('a.');
        $this->assertEquals(false, $configs['a.a']);
        $this->assertEquals(false, $configs['a.b']);
        $this->assertEquals(false, $configs['a.c']);

        // strip
        $configs = Bootstrap::pf('a.', true);
        $this->assertEquals(false, $configs['a']);
        $this->assertEquals(false, $configs['b']);
        $this->assertEquals(false, $configs['c']);
    }
}
