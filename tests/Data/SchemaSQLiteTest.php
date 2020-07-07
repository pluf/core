<?php
/*
 * This file is part of bootstrap Framework, a simple PHP Application Framework.
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
namespace Pluf\Test\Data;

use PHPUnit\Framework\TestCase;
use Pluf\Options;
use Pluf\Data\ModelDescription;
use Pluf\Data\Schema\SQLiteSchema;
use Pluf;

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class SchemaSQLiteTest extends TestCase
{

    // /**
    // *
    // * @beforeClass
    // */
    // public static function initTest()
    // {
    // Pluf::start(dirname(__FILE__) . '/../conf/config.php');
    // if (Pluf::db()->engine != 'SQLite') {
    // self::markTestSkipped('Only to be run with the SQLite DB engine');
    // }
    // }

    /**
     *
     * @test
     */
    public function testGenerateSchema3()
    {
        Pluf::start([
            'db_dsn' => 'sqlite::memory:',
            'db_user' => null,
            'db_password' => null
        ]);
        $model = new \Pluf\NoteBook\Book();
        $modelDes = ModelDescription::getInstance($model);
        $schema = new SQLiteSchema(new Options([
            'prefix' => 'sqlite_' . rand() . '_'
        ]));

        $sql = $schema->createTableQueries($modelDes);

        // CREATE TABLE pluf_unit_tests_testmodel (
        // id integer primary key autoincrement,
        // title varchar(100) default '',
        // description text not null default ''
        // );

        $tablename = $schema->getTableName($modelDes);
        $this->assertEquals(true, strpos($sql[$tablename], 'CREATE TABLE') !== false);
        $this->assertEquals(true, strpos($sql[$tablename], 'integer') !== false);
        $this->assertEquals(true, strpos($sql[$tablename], 'varchar(100)') !== false);
        $this->assertEquals(true, strpos($sql[$tablename], 'text') !== false);
        $this->assertEquals(true, strpos($sql[$tablename], $tablename) !== false);
    }

    /**
     *
     * @test
     */
    public function testDeleteSchemaTestModel()
    {
        Pluf::start([
            'db_dsn' => 'sqlite::memory:',
            'db_user' => null,
            'db_password' => null
        ]);
        $model = new \Pluf\NoteBook\Book();
        $modelDes = ModelDescription::getInstance($model);
        $schema = new SQLiteSchema(new Options([
            'prefix' => 'sqlite_' . rand() . '_'
        ]));

        $del = $schema->dropTableQueries($modelDes);

        $tablename = $schema->getTableName($modelDes);
        $this->assertEquals('DROP TABLE IF EXISTS ' . $tablename, $del[0]);
    }

    /**
     *
     * @test
     */
    public function testGetValuesOfModelSchema()
    {
        Pluf::start([
            'db_dsn' => 'sqlite::memory:',
            'db_user' => null,
            'db_password' => null,

            'data_schema_engine' => 'sqlite',
            'data_schema_sqlite_prefix' => 'sqlite_' . rand() . '_'
        ]);

        // Check if schema is loaded
        $this->assertNotNull(Pluf::getDataSchema());

        $model = new \Pluf\NoteBook\Book();
        $model->title = 'my title';
        $model->description = 'A small desc.';

        $md = ModelDescription::getInstance($model);

        $vals = Pluf::getDataSchema()->getValues($md, $model);
        $this->assertNotNull($vals);

        $this->assertFalse(isset($vals['id']));
        $this->assertFalse(array_key_exists('id', $vals));
        $this->assertTrue(isset($vals['title']));
        $this->assertTrue(isset($vals['description']));
    }

    /**
     *
     * @test
     */
    public function testGenerateSchema()
    {
        Pluf::start([
            'db_dsn' => 'sqlite::memory:',
            'db_user' => null,
            'db_password' => null,

            'data_schema_engine' => 'sqlite',
            'data_schema_sqlite_prefix' => 'db' . rand() . '_'
        ]);
        $this->assertNotNull(Pluf::getDataSchema());

        $model = new \Pluf\NoteBook\Book();

        Pluf::getDataSchema()->createTables(Pluf::db(), ModelDescription::getInstance($model));

        $model->title = 'my title';
        $model->description = 'A small desc.';
        $this->assertEquals(true, $model->create());
        $this->assertEquals(1, (int) $model->id);

        Pluf::getDataSchema()->dropTables(Pluf::db(), ModelDescription::getInstance($model));
    }
}
