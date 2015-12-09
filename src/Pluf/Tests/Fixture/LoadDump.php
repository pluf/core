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

class Pluf_Tests_Fixture_LoadDump extends UnitTestCase {
 
    function __construct() 
    {
        parent::__construct('Test fixture load/dump.');
    }

    function setUp()
    {
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $m = new Pluf_Permission();
        $schema->model = $m;
        $schema->dropTables();
        $schema->createTables();
    }

    function tearDown()
    {
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $m = new Pluf_Permission();
        $schema->model = $m;
        $schema->dropTables();
    }


    function testDump()
    {
        $p = new Pluf_Permission();
        $p->name = 'test permission';
        $p->code_name = 'test';
        $p->description = 'Simple test permission.';
        $p->application = 'Pluf';
        $p->create();
        $json = Pluf_Test_Fixture::dump('Pluf_Permission');
        $this->assertEqual('[{"model":"Pluf_Permission","pk":1,"fields":{"id":1,"name":"test permission","code_name":"test","description":"Simple test permission.","application":"Pluf"}}]',
                            $json);
    }

    function testLoad()
    {
        $created = Pluf_Test_Fixture::load('[{"model":"Pluf_Permission","pk":1,"fields":{"id":1,"name":"test permission","code_name":"test","description":"Simple test permission.","application":"Pluf"}}]');
        $this->assertEqual(array(array('Pluf_Permission', '1')), $created);
        $p = new Pluf_Permission(1);
        $this->assertEqual(1, $p->id);        
    }
}