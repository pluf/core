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
use Pluf\Cache;
use Pluf\Options;

class ApceTest extends TestCase
{

    /**
     *
     * @test
     */
    public function createNewInstance()
    {
        $cache = Cache::getInstance(new Options([
            'engine' => 'apcu'
        ]));
        $this->assertNotNull($cache);
    }

    /**
     *
     * @test
     */
    public function testBasic()
    {
        $options = new Options([
            'timeout' => 300
        ]);
        $cache = new Cache\Apcu($options);
        $this->assertNotNull($cache);

        $var = 'foo1';
        $key = 'test1';

        $cache->set($key, $var);
        $this->assertEquals($var, $cache->get($key));
    }

    /**
     *
     * @test
     */
    public function testGetUnknownKey()
    {
        $options = new Options([
            'timeout' => 300
        ]);
        $cache = new Cache\Apcu($options);
        $this->assertNotNull($cache);

        $this->assertNull($cache->get('unknown'));
    }

    /**
     *
     * @test
     */
    public function testGetDefault()
    {
        $options = new Options([
            'timeout' => 300
        ]);
        $cache = new Cache\Apcu($options);
        $this->assertNotNull($cache);

        $this->assertEquals('default', $cache->get('unknown', 'default'));
    }

    /**
     *
     * @test
     */
    public function testSerialized()
    {
        $arrayData = [
            'hello' => 'world',
            'foo' => false,
            0 => array(
                'foo',
                'bar'
            )
        ];
        $options = new Options([
            'timeout' => 300
        ]);
        $cache = new Cache\Apcu($options);
        $this->assertNotNull($cache);

        $cache->set('array', $arrayData);
        $this->assertEquals($arrayData, $cache->get('array'));

        $obj = new stdClass();
        $obj->foo = 'bar';
        $obj->hello = 'world';
        $success = $cache->set('object', $obj);
        $this->assertTrue($success);
        $this->assertEquals($obj, $cache->get('object'));

        unset($obj);
        $this->assertInstanceOf(stdClass::class, $cache->get('object'));
        $this->assertEquals('world', $cache->get('object')->hello);
    }

    /**
     *
     * @test
     */
    public function testSerializedObject()
    {
        $object = new MyObject();
        $options = new Options([
            'timeout' => 300
        ]);
        $cache = new Cache\Apcu($options);
        $this->assertNotNull($cache);

        $cache->set('obj', $object);
        $this->assertEquals($object, $cache->get('obj'));
    }
}

class MyObject
{

    private $var1 = 'hi';

    protected $var2 = 'me';

    public $v3 = [
        'hello' => 'world',
        'foo' => false,
        0 => array(
            'foo',
            'bar'
        )
    ];

    function getVar1()
    {
        return $this->var1;
    }

    function getVar2()
    {
        return $this->var2;
    }
}
