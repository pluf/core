<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume CMS, a website management application.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
#
# Plume CMS is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# Plume CMS is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

error_reporting(E_ALL | E_STRICT);

$path = dirname(__FILE__).'/../../src/';
set_include_path(get_include_path().PATH_SEPARATOR.$path);

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'PHPUnit/Framework/IncompleteTestError.php';

require_once 'Pluf.php';

include_once dirname(__FILE__).'/../Pluf_Model/TestModels.php';

class PlufDBSchemaSQLiteTest extends PHPUnit_Framework_TestCase 
{

    public $db = null;

    protected function setUp()
    {
        Pluf::start(dirname(__FILE__).'/../conf/pluf.config.php');
        $this->db = Pluf::db();
        if ($this->db->engine != 'SQLite') {
            $this->markTestSkipped('Only to be run with the SQLite DB engine');
        }
    }

    protected function tearDown()
    {
        $this->db->close();
        unset($this->db);
    }

    public function testGenerateSchema3()
    {
        $model = new TestModel();
        $schema = Pluf::factory('Pluf_DB_Schema', $this->db);
        $schema->model = $model;
        $gen = $schema->getGenerator();
        $sql = $gen->getSqlCreate($model);
        $create = "CREATE TABLE pluf_unit_tests_testmodel (
id integer primary key autoincrement,
title varchar(100) default '',
description text not null default ''
);";
        $this->assertEquals($create, $sql['pluf_unit_tests_testmodel']);
    }

    public function testDeleteSchemaTestModel()
    {
        $model = new TestModel();
        $schema = Pluf::factory('Pluf_DB_Schema', $this->db);
        $schema->model = $model;
        $gen = $schema->getGenerator();
        $del = $gen->getSqlDelete($model);
        $this->assertEquals('DROP TABLE IF EXISTS pluf_unit_tests_testmodel', $del[0]);
    }


    public function testGenerateSchema()
    {
        $model = new TestModel();
        $schema = Pluf::factory('Pluf_DB_Schema', $this->db);
        $schema->model = $model;
        $gen = $schema->getGenerator();
        $this->assertEquals(true, $schema->dropTables());
        $sql = $gen->getSqlCreate($model);
        foreach ($sql as $k => $query) {
            $this->db->execute($query);
        }
        $sql = $gen->getSqlIndexes($model);
        foreach ($sql as $k => $query) {
            $this->db->execute($query);
        }
        $model->title = 'my title';
        $model->description = 'A small desc.';
        $this->assertEquals(true, $model->create());
        $this->assertEquals(1, (int) $model->id);
        $del = $gen->getSqlDelete($model);
        foreach ($del as $sql) {
            $this->db->execute($sql);
        }
    }

    public function testGenerateSchema2()
    {
        $model = new TestModel();
        $schema = Pluf::factory('Pluf_DB_Schema', $this->db);
        $schema->model = $model;
        $this->assertEquals(true, $schema->dropTables());
        $this->assertEquals(true, $schema->createTables());
        $model->title = 'my title';
        $model->description = 'A small desc.';
        $this->assertEquals(true, $model->create());
        $this->assertEquals(1, (int) $model->id);
        $this->assertEquals(true, $schema->dropTables());
    }
}
