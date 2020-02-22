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
use Pluf\Bootstrap;

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class PlufMethodStartTest extends TestCase
{

    /**
     *
     * @before
     */
    public function setUpTest()
    {
        // TODO: maso, 2019: update test
    }

    /**
     *
     * @test
     */
    public function testStart()
    {
        Bootstrap::start(__DIR__ . '/../conf/config.php');
        $this->assertEquals(true, isset($GLOBALS['_PX_config']));

        $conf = include __DIR__ . '/../conf/config.php';
        $conf['test'] = false;
        Bootstrap::start($conf);
        $this->assertEquals(false, $GLOBALS['_PX_config']['test']);
    }

    /**
     *
     * @test
     * @expectedException \Pluf\Exception
     */
    public function testStartWithReadableFile()
    {
        Bootstrap::start(__DIR__ . '/../conf/config.php' . 'xxxxx');
    }

    /**
     *
     * @test
     */
    public function testStartWithInlineConfig()
    {
        Bootstrap::start(array(
            'test' => false
        ));
        $this->assertEquals(true, isset($GLOBALS['_PX_config']));
        $this->assertEquals(false, $GLOBALS['_PX_config']['test']);
        $this->assertEquals(false, Bootstrap::f('test', true));
    }
}
