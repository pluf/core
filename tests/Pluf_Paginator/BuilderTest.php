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
require_once dirname(__FILE__) . '/MyModel.php';
/**
 * Test paginator builder
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Pluf_Paginator_BuilderTest extends TestCase
{
    
    /**
     * @before
     */
    protected function setUp()
    {
        Pluf::start(dirname(__FILE__) . '/../conf/pluf.config.php');
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $m1 = new Pluf_Paginator_MyModel();
        $schema->model = $m1;
        $schema->dropTables();
        $schema->createTables();
        for ($i = 1; $i < 11; $i ++) {
            $m = new Pluf_Paginator_MyModel();
            $m->title = 'My title ' . $i;
            $m->description = 'My description ' . $i;
            $m->create();
        }
    }

    protected function tearDown()
    {
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $m1 = new Pluf_Paginator_MyModel();
        $schema->model = $m1;
        $schema->dropTables();
    }
    
    /**
     * @test
     */
    public function testCreateSimplePaginator()
    {
        $builder = new Pluf_Paginator_Builder(new Pluf_Paginator_MyModel());
        $pag = $builder->build();
        $this->assertTrue(isset($pag));
    }
    
    /**
     * @test
     */
    public function testWithSearchFieldsQueryPaginator()
    {
        $sf = array(
            'title'
        );
        $builder = new Pluf_Paginator_Builder(new Pluf_Paginator_MyModel());
        $pag = $builder->setSearchFields($sf)->build();
        $this->assertTrue(isset($pag));
        $this->assertEquals($pag->search_fields, $sf);
    }
}
