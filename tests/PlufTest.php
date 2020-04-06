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
namespace Pluf\Test;

require_once 'Pluf.php';

use PHPUnit\Framework\TestCase;
use Pluf;

class PlufTest extends TestCase
{

    /**
     *
     * @before
     */
    public function setUpTest()
    {
        $conf = include __DIR__ . '/conf/config.php';
        $conf['test-var'] = false;
        Pluf::start($conf);
    }

    /**
     *
     * @test
     */
    public function testF()
    {
        $this->assertEquals(Pluf::f('test-var'), false);
    }

    /**
     *
     * @test
     */
    public function testFactory()
    {
        $pluf = Pluf::factory('Pluf');
        $this->assertEquals(get_class($pluf), 'Pluf');

        $pluf = Pluf::factory(Pluf::class);
        $this->assertEquals(get_class($pluf), Pluf::class);
    }

    /**
     *
     * @test
     */
    public function testFileExists()
    {
        $this->assertTrue(Pluf::fileExists('Pluf.php') !== false);
    }

    /**
     *
     * @test
     */
    public function testLoadClass()
    {
        Pluf::loadClass('Pluf_Model');
        $this->assertEquals(true, class_exists('Pluf_Model'));
    }

    /**
     *
     * @test
     */
    public function testLoadFunction()
    {
        Pluf::loadFunction('Pluf_HTTP_handleMagicQuotes');
        $this->assertEquals(true, function_exists('Pluf_HTTP_handleMagicQuotes'));
    }

    /**
     *
     * @test
     */
    public function phpConceptsTest()
    {
        /*
         * We are about to accept the folloing RFC :
         * https://wiki.php.net/rfc/class_name_literal_on_object
         *
         * How ever the oldest RFC is accepted and used in the code
         */
        $this->assertEquals(Pluf::class, 'Pluf');
        // PHP8
        // $obj = new Pluf_Dispatcher();
        // $this->assertEquals($obj::class, 'Pluf_Dispatcher');
    }

    /**
     *
     * @test
     */
    public function testLoadCache()
    {
        Pluf::start([
            'cache_engine' => 'array'
        ]);
        $this->assertTrue(Pluf::getCache() instanceof \Pluf\Cache\ArrayCache);
        
        Pluf::start([
            'cache_engine' => 'file',
            'cache_file_timeout' => 1234
        ]);
        $this->assertTrue(Pluf::getCache() instanceof Pluf\Cache\File);
        $this->assertEquals(1234, Pluf::getCache()->getTimeout());
    }

    /**
     *
     * @test
     */
    public function testLoadOptions()
    {
        Pluf::start([
            'xxx' => 'yyy',
            'a_xxx' => 'yyy'
        ]);
        $this->assertEquals('yyy', Pluf::getConfig('xxx'));
        $this->assertTrue(Pluf::getConfigByPrefix('a_') instanceof Pluf\Options);
    }

    /**
     *
     * @test
     */
    public function testLoadDbConnection()
    {
        Pluf::start([
            'db_dsn' => 'sqlite::memory:'
        ]);
        $this->assertTrue(Pluf::db() instanceof Pluf\Db\Connection);
    }
}
