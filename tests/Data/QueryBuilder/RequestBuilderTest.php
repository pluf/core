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
namespace Pluf\Test\Data\QueryBuilder;

use PHPUnit\Framework\TestCase;
use Pluf;
use Pluf\HTTP\Request;
use Pluf\Data\QueryBuilder;
use Pluf\Data\Query;
use Pluf\ObjectMapper;
use Pluf\NoteBook\Book;

/**
 * Test paginator builder
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class RequestBuilderTest extends TestCase
{

    /**
     *
     * @beforeClass
     */
    public static function createDataBase()
    {
        $conf = include __DIR__ . '/../../conf/config.php';
        Pluf::start($conf);

        $m = new \Pluf\Migration();
        $m->install();

        $mapper = ObjectMapper::getInstance([
            [
                "title" => "a",
                "description" => "b"
            ],
            [
                "title" => "c",
                "description" => "d"
            ],
            [
                "title" => "e",
                "description" => "f"
            ],
            [
                "title" => "g",
                "description" => "h"
            ]
        ]);
        $repo = Pluf::getDataRepository(Book::class);
        while ($mapper->hasMore()) {
            $item = $mapper->next(Book::class);
            $repo->create($item);
        }
    }

    /**
     *
     * @afterClass
     */
    public static function removeDatabses()
    {
        $m = new \Pluf\Migration();
        $m->uninstall();
    }

    /**
     *
     * @test
     */
    public function testCreateSimplePaginator()
    {
        $request = new Request('/api/v2/nootbook/books');
        $request->REQUEST = [];
        $builder = QueryBuilder::getInstance($request);
        $this->assertNotNull($builder);
        $this->assertTrue($builder instanceof QueryBuilder);

        $query = QueryBuilder::getInstance($request)->build();

        $this->assertNotNull($query);
        $this->assertTrue($query instanceof Query);
    }

    /**
     *
     * @test
     */
    public function testListWithLimit()
    {
        $request = new Request('/api/v2/nootbook/books');
        $request->REQUEST['_px_ps'] = '1';
        $query = QueryBuilder::getInstance($request)->build();
        $repo = Pluf::getDataRepository(Book::class);
        $list = $repo->get($query);
        $this->assertEquals(1, count($list));
    }

    /**
     *
     * @test
     */
    public function testListWithPage()
    {
        $request = new Request('/api/v2/nootbook/books');
        $request->REQUEST['_px_p'] = '0';
        $request->REQUEST['_px_ps'] = '3';
        $query = QueryBuilder::getInstance($request)->build();
        $repo = Pluf::getDataRepository(Book::class);
        $list = $repo->get($query);
        $this->assertEquals(3, count($list));

        $request = new Request('/api/v2/nootbook/books');
        $request->REQUEST['_px_p'] = '1';
        $request->REQUEST['_px_ps'] = '3';
        $query = QueryBuilder::getInstance($request)->build();
        $repo = Pluf::getDataRepository(Book::class);
        $list = $repo->get($query);
        $this->assertEquals(1, count($list));
    }

    /**
     *
     * @test
     */
    public function testWithSearchFields()
    {
        $letters = [
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'g',
            'h'
        ];
        foreach ($letters as $letter) {
            $request = new Request('/api/v2/nootbook/books');
            $request->REQUEST['q'] = $letter;
            $request->REQUEST['_px_p'] = '0';
            $request->REQUEST['_px_ps'] = '30';
            $query = QueryBuilder::getInstance($request)->build();
            $repo = Pluf::getDataRepository(Book::class);
            $list = $repo->get($query);
            $this->assertEquals(1, count($list));
        }
    }

    /**
     *
     * @test
     */
    public function testWithFilter()
    {
        $request = new Request('/api/v2/nootbook/books');
        $request->REQUEST['_px_fk'] = 'title';
        $request->REQUEST['_px_fv'] = 'a';
        $request->REQUEST['_px_p'] = '0';
        $request->REQUEST['_px_ps'] = '30';
        $query = QueryBuilder::getInstance($request)->build();
        $repo = Pluf::getDataRepository(Book::class);
        $list = $repo->get($query);
        $this->assertEquals(1, count($list));
    }

    /**
     *
     * @test
     */
    public function testWithDublicatedFilter()
    {
        $request = new Request('/api/v2/nootbook/books');
        $request->REQUEST['_px_fk'] = [
            'title',
            'title',
            'title'
        ];
        $request->REQUEST['_px_fv'] = [
            'a',
            'b',
            'c'
        ];
        $request->REQUEST['_px_p'] = '0';
        $request->REQUEST['_px_ps'] = '30';
        $query = QueryBuilder::getInstance($request)->optimize()->build();
        $repo = Pluf::getDataRepository(Book::class);
        $list = $repo->get($query);
        $this->assertEquals(2, count($list));
    }

    // /**
    // *
    // * @test
    // */
    // public function testWithSearchFieldsAutomated()
    // {
    // $builder = new Pluf_Paginator_Builder(new Pluf_Paginator_MyModel());
    // $pag = $builder->build();
    // $this->assertTrue(isset($pag));
    // $this->assertTrue(in_array('id', $pag->search_fields), 'Id not found in search fields');
    // $this->assertTrue(in_array('title', $pag->search_fields), 'Title not found in search fields');
    // $this->assertTrue(in_array('description', $pag->search_fields), 'Description not found in search fields');
    // }

    // /**
    // *
    // * @test
    // */
    // public function testWithSortFieldsAutomated()
    // {
    // $builder = new Pluf_Paginator_Builder(new Pluf_Paginator_MyModel());
    // $pag = $builder->build();
    // $this->assertTrue(isset($pag));
    // $this->assertTrue(in_array('id', $pag->sort_fields), 'Id not found in sort fields');
    // $this->assertTrue(in_array('title', $pag->sort_fields), 'Title not found in sort fields');
    // $this->assertTrue(in_array('description', $pag->sort_fields), 'Description not found in sort fields');
    // }

    // /**
    // *
    // * @test
    // */
    // public function testModelView()
    // {
    // $builder = new Pluf_Paginator_Builder(new Pluf_Paginator_MyModel());
    // $pag = $builder->setView('test_view')->build();
    // $this->assertTrue(isset($pag));
    // $this->assertEquals('test_view', $pag->model_view, 'Id not found in sort fields');
    // }

    // /**
    // *
    // * @test
    // */
    // public function testWhereClause()
    // {
    // $sql = new Pluf_SQL('id=%s', array(
    // 'id' => '1'
    // ));
    // $builder = new Pluf_Paginator_Builder(new Pluf_Paginator_MyModel());
    // $pag = $builder->setView('test_view')
    // ->setWhereClause($sql)
    // ->build();
    // $this->assertTrue(isset($pag));
    // $this->assertEquals($sql->gen(), $pag->forced_where->gen(), 'Where clause dose not matsh');
    // }
}
