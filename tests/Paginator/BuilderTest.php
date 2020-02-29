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
namespace Pluf\PlufTest\Paginator;

use PHPUnit\Framework\TestCase;
use Pluf\Bootstrap;
use Pluf\Paginator;

/**
 * Test paginator builder
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Pluf_Paginator_BuilderTest extends TestCase
{

    /**
     *
     * @before
     */
    protected function setUpTest()
    {
        Bootstrap::start(__DIR__ . '/../conf/config.php');
        $schema = new \Pluf\DB\Schema(Bootstrap::db());
        $m1 = new PaginatorMyModel();
        $schema->model = $m1;
        $schema->dropTables();
        $schema->createTables();
        for ($i = 1; $i < 11; $i ++) {
            $m = new PaginatorMyModel();
            $m->title = 'My title ' . $i;
            $m->description = 'My description ' . $i;
            $m->create();
        }
    }

    /**
     *
     * @after
     */
    protected function tearDownTest()
    {
        $schema = new \Pluf\DB\Schema(Bootstrap::db());
        $m1 = new PaginatorMyModel();
        $schema->model = $m1;
        $schema->dropTables();
    }

    /**
     *
     * @test
     */
    public function testCreateSimplePaginator()
    {
        $builder = new Paginator\Builder(new PaginatorMyModel());
        $pag = $builder->build();
        $this->assertTrue(isset($pag));
    }

    /**
     *
     * @test
     */
    public function testWithSearchFields()
    {
        $sf = array(
            'title'
        );
        $builder = new Paginator\Builder(new PaginatorMyModel());
        $pag = $builder->setSearchFields($sf)->build();
        $this->assertTrue(isset($pag));
        $this->assertEquals($pag->search_fields, $sf);
    }

    /**
     *
     * @test
     */
    public function testWithSearchFieldsAutomated()
    {
        $builder = new Paginator\Builder(new PaginatorMyModel());
        $pag = $builder->build();
        $this->assertTrue(isset($pag));
        $this->assertTrue(in_array('id', $pag->search_fields), 'Id not found in search fields');
        $this->assertTrue(in_array('title', $pag->search_fields), 'Title not found in search fields');
        $this->assertTrue(in_array('description', $pag->search_fields), 'Description not found in search fields');
    }

    /**
     *
     * @test
     */
    public function testWithSortFieldsAutomated()
    {
        $builder = new Paginator\Builder(new PaginatorMyModel());
        $pag = $builder->build();
        $this->assertTrue(isset($pag));
        $this->assertTrue(in_array('id', $pag->sort_fields), 'Id not found in sort fields');
        $this->assertTrue(in_array('title', $pag->sort_fields), 'Title not found in sort fields');
        $this->assertTrue(in_array('description', $pag->sort_fields), 'Description not found in sort fields');
    }

    /**
     *
     * @test
     */
    public function testModelView()
    {
        $builder = new Paginator\Builder(new PaginatorMyModel());
        $pag = $builder->setView('test_view')->build();
        $this->assertTrue(isset($pag));
        $this->assertEquals('test_view', $pag->model_view, 'Id not found in sort fields');
    }

    /**
     *
     * @test
     */
    public function testWhereClause()
    {
        $sql = new \Pluf\SQL('id=%s', array(
            'id' => '1'
        ));
        $builder = new Paginator\Builder(new PaginatorMyModel());
        $pag = $builder->setView('test_view')
            ->setWhereClause($sql)
            ->build();
        $this->assertTrue(isset($pag));
        $this->assertEquals($sql->gen(), $pag->forced_where->gen(), 'Where clause dose not matsh');
    }
}