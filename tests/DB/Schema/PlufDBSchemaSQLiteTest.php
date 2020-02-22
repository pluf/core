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
namespace Pluf\PlufTest\Schema;

use PHPUnit\Framework\TestCase;
use Pluf\Bootstrap;
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../apps');

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class PlufDBSchemaSQLiteTest extends TestCase
{

    /**
     *
     * @beforeClass
     */
    public static function initTest()
    {
        Bootstrap::start(array(
            'installed_apps' => array(
                'Pluf'
            ),
            'db_version' => '5.0',
            'db_engine' => 'SQLite',
            'db_login' => 'testpluf',
            'db_password' => 'testpluf',
            'db_server' => 'localhost',
            'db_database' => dirname(__FILE__) . '/../tmp/dp.test.sqlite.db',
            'db_table_prefix' => 'pluf_unit_tests_'
        ));
    }

    /**
     *
     * @afterCalss
     */
    public static function finishTest()
    {
        // $this->db->close();
        // unset($this->db);
    }

    /**
     *
     * @test
     */
    public function testGenerateSchema3()
    {
        $model = new \Pluf\Test\Model();
        $schema = \Pluf\DB\Schema(Bootstrap::db());
        $schema->model = $model;
        $gen = $schema->getGenerator();
        $sql = $gen->getSqlCreate($model);

        // CREATE TABLE pluf_unit_tests_testmodel (
        // id integer primary key autoincrement,
        // title varchar(100) default '',
        // description text not null default ''
        // );
        $this->assertEquals(true, strpos($sql['pluf_unit_tests_test_model'], 'CREATE TABLE') !== false);
        $this->assertEquals(true, strpos($sql['pluf_unit_tests_test_model'], 'integer') !== false);
        $this->assertEquals(true, strpos($sql['pluf_unit_tests_test_model'], 'varchar(100)') !== false);
        $this->assertEquals(true, strpos($sql['pluf_unit_tests_test_model'], 'text') !== false);
        $this->assertEquals(true, strpos($sql['pluf_unit_tests_test_model'], 'test_model') !== false);
    }

    /**
     *
     * @test
     */
    public function testDeleteSchemaTestModel()
    {
        $model = new Test_Model();
        $schema = Pluf::factory('Pluf_DB_Schema', Pluf::db());
        $schema->model = $model;
        $gen = $schema->getGenerator();
        $del = $gen->getSqlDelete($model);
        $this->assertEquals('DROP TABLE IF EXISTS pluf_unit_tests_test_model', $del[0]);
    }

    /**
     *
     * @test
     */
    public function testGenerateSchema()
    {
        $model = new Test_Model();
        $schema = Pluf::factory('Pluf_DB_Schema', Pluf::db());
        $schema->model = $model;
        $gen = $schema->getGenerator();
        $this->assertEquals(true, $schema->dropTables());
        $sql = $gen->getSqlCreate($model);
        foreach ($sql as $k => $query) {
            Pluf::db()->execute($query);
        }
        $sql = $gen->getSqlIndexes($model);
        foreach ($sql as $k => $query) {
            Pluf::db()->execute($query);
        }
        $model->title = 'my title';
        $model->description = 'A small desc.';
        $this->assertEquals(true, $model->create());
        $this->assertEquals(1, (int) $model->id);
        $del = $gen->getSqlDelete($model);
        foreach ($del as $sql) {
            Pluf::db()->execute($sql);
        }
    }

    /**
     *
     * @test
     */
    public function testGenerateSchema2()
    {
        $model = new Test_Model();
        $schema = Pluf::factory('Pluf_DB_Schema', Pluf::db());
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
