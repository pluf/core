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

class Pluf_Tests_Cache_Apc extends UnitTestCase {

    private $_config;

    private $_arrayData = array(
        'hello' => 'world',
        'foo'   => false,
        0 => array('foo', 'bar')
    );

    public function __construct()
    {
        parent::__construct('Test the APC cache API.');
    }

    public function setUp()
    {
        $this->_config = $GLOBALS['_PX_config']; // backup
        $GLOBALS['_PX_config']['cache_engine']  = 'Pluf_Cache_Apc';
        $GLOBALS['_PX_config']['cache_timeout'] = 5;
        $GLOBALS['_PX_config']['cache_apc_keyprefix'] = 'pluf_unittest_';
    }

    public function tearDown()
    {
        $GLOBALS['_PX_config'] = $this->_config;
    }

    public function testBasic()
    {
        $cache = Pluf_Cache::factory();
        $success = $cache->set('test1', 'foo1');
        $this->assertTrue($success);
        $this->assertEqual('foo1', $cache->get('test1'));
    }

    public function testGetUnknownKey()
    {
        $cache = Pluf_Cache::factory();
        $this->assertNull(null, $cache->get('unknown'));
    }

    public function testGetDefault()
    {
        $cache = Pluf_Cache::factory();
        $this->assertEqual('default', $cache->get('unknown', 'default'));
    }

    public function testSerialized()
    {
        $cache = Pluf_Cache::factory();
        $success = $cache->set('array', $this->_arrayData);
        $this->assertTrue($success);
        $this->assertCopy($this->_arrayData, $cache->get('array'));

        $obj = new stdClass();
        $obj->foo = 'bar';
        $obj->hello = 'world';
        $success = $cache->set('object', $obj);
        $this->assertTrue($success);
        $this->assertCopy($obj, $cache->get('object'));

        unset($obj);
        $this->assertIsA($cache->get('object'), 'stdClass');
        $this->assertEqual('world', $cache->get('object')->hello);
    }

    public function testCompress()
    {
        $GLOBALS['_PX_config']['cache_apc_compress'] = true;
        $cache = Pluf_Cache::factory();
        $success = $cache->set('compressed', $this->_arrayData);
        $this->assertTrue($success);
        $this->assertCopy($this->_arrayData, $cache->get('compressed'));
    }
}
