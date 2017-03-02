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

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class PlufTest extends TestCase
{

    /**
     * @before
     */
    public function setUp ()
    {
        Pluf::start(dirname(__FILE__) . '/../conf/pluf.config.php');
    }

    /**
     * @test
     */
    public function testF ()
    {
        $this->assertEquals(Pluf::f('test'), false);
    }

    /**
     * @test
     */
    public function testFactory ()
    {
        $pluf = Pluf::factory('Pluf');
        $this->assertEquals(get_class($pluf), 'Pluf');
    }

    /**
     * @test
     */
    public function testFileExists ()
    {
        $this->assertTrue(Pluf::fileExists('Pluf.php') !== false);
    }

    /**
     * @test
     */
    public function testLoadClass ()
    {
        Pluf::loadClass('Pluf_Model');
        $this->assertEquals(true, class_exists('Pluf_Model'));
    }

    /**
     * @test
     */
    public function testLoadFunction ()
    {
        Pluf::loadFunction('Pluf_HTTP_handleMagicQuotes');
        $this->assertEquals(true, 
                function_exists('Pluf_HTTP_handleMagicQuotes'));
    }
}

?>