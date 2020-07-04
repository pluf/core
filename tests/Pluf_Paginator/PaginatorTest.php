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
use Pluf\HTTP\Request;

require_once 'Pluf.php';

require_once dirname(__FILE__) . '/MyModel.php';

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Pluf_Paginator_PaginatorTest extends TestCase
{

    /**
     *
     * @before
     */
    protected function setUpTest()
    {
        Pluf::start(__DIR__ . '/../conf/config.php');
        $engine = Pluf::db();
        $schema = $engine->getSchema();

        $m1 = new Pluf_Paginator_MyModel();

        Pluf_Migration::dropTables($engine, $schema, $m1);
        Pluf_Migration::createTables($engine, $schema, $m1);

        for ($i = 1; $i < 11; $i ++) {
            $m = new Pluf_Paginator_MyModel();
            $m->title = 'My title ' . $i;
            $m->description = 'My description ' . $i;
            $m->int_field = $i;
            $m->float_field = $i;
            $m->create();
        }
    }

    /**
     *
     * @after
     */
    protected function tearDownTest()
    {
        $engine = Pluf::db();
        $schema = $engine->getSchema();

        $m1 = new Pluf_Paginator_MyModel();

        Pluf_Migration::dropTables($engine, $schema, $m1);
    }

    /**
     * Test simple pagination
     *
     * @test
     */
    public function testSimplePaginate()
    {
        $pag = new Pluf_Paginator(new Pluf_Paginator_MyModel());
        $pag->items_per_page = 5;
        $this->assertTrue(is_array($pag->render_object()));
        $this->assertTrue(array_key_exists('items', $pag->render_object()));
    }

    /**
     * Test single sort order
     *
     * @test
     */
    public function testSingleSortPaginate()
    {
        $pag = new Pluf_Paginator(new Pluf_Paginator_MyModel());
        $fields = array(
            'id',
            'title',
            'description'
        );
        $pag->configure($fields, $fields);
        $pag->list_filters = $fields;
        $pag->items_per_page = 5;
        $pag->sort_order = array(
            'id',
            'ASC'
        );

        $this->assertTrue(is_array($pag->render_object()));
        $this->assertTrue(array_key_exists('items', $pag->render_object()));
    }

    /**
     * Test multi sort order
     *
     * @test
     */
    public function testMultiSortPaginate()
    {
        $pag = new Pluf_Paginator(new Pluf_Paginator_MyModel());
        $fields = array(
            'id',
            'title',
            'description'
        );
        $pag->configure($fields, $fields);
        $pag->list_filters = $fields;
        $pag->items_per_page = 5;
        $pag->sort_order = array(
            array(
                'id',
                'ASC'
            ),
            array(
                'title',
                'ASC'
            )
        );

        $this->assertTrue(is_array($pag->render_object()));
        $this->assertTrue(array_key_exists('items', $pag->render_object()));
    }

    /**
     * Test multi sort order
     *
     * @test
     */
    public function testSortOrderFunctionPaginate()
    {
        $item1 = new Pluf_Paginator_MyModel();
        $item1->title = 'a';
        $item1->description = 'description';
        $item1->create();

        $item2 = new Pluf_Paginator_MyModel();
        $item2->title = 'b';
        $item2->description = 'description';
        $item2->create();

        $pag = new Pluf_Paginator(new Pluf_Paginator_MyModel());
        $fields = array(
            'id',
            'title',
            'description'
        );
        $pag->configure($fields, $fields);
        $pag->list_filters = $fields;
        $pag->items_per_page = 5;

        $pag->sort_order = array(
            array(
                'id',
                'ASC'
            )
        );
        $result = $pag->render_object();
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('items', $result));
        // check order
        for ($i = 1; $i < sizeof($result['items']); $i ++) {
            $a = $result['items'][$i];
            $b = $result['items'][$i - 1];
            $this->assertTrue($a->id > $b->id);
        }

        $pag->sort_order = array(
            array(
                'id',
                'DESC'
            )
        );
        $result = $pag->render_object();
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('items', $result));
        // check order
        for ($i = 1; $i < sizeof($result['items']); $i ++) {
            $a = $result['items'][$i];
            $b = $result['items'][$i - 1];
            $this->assertFalse($a->id > $b->id);
        }

        $item1->delete();
        $item2->delete();
    }

    /**
     * Load from request
     *
     * @test
     */
    public function testSetFromRequestSort()
    {
        $_REQUEST = array(
            '_px_sk' => 'id',
            '_px_so' => 'd'
        );
        $request = new Request('/test');

        $pag = new Pluf_Paginator(new Pluf_Paginator_MyModel());
        $fields = array(
            'id',
            'title',
            'description'
        );
        $pag->configure($fields, $fields);
        $pag->list_filters = $fields;
        $pag->items_per_page = 5;
        $pag->setFromRequest($request);

        $result = $pag->render_object();
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('items', $result));
    }

    /**
     * Load from request with multi sort
     *
     * @test
     */
    public function testSetFromRequestMultiSort()
    {
        $_REQUEST = array(
            '_px_sk' => array(
                'id',
                'title'
            ),
            '_px_so' => array(
                'd',
                'a'
            )
        );
        $request = new Request('/test');

        $pag = new Pluf_Paginator(new Pluf_Paginator_MyModel());
        $fields = array(
            'id',
            'title',
            'description'
        );
        $pag->configure($fields, $fields);
        $pag->list_filters = $fields;
        $pag->items_per_page = 5;
        $pag->setFromRequest($request);

        $result = $pag->render_object();
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('items', $result));
    }

    /**
     * Test filter from request
     *
     * @test
     */
    public function testSetFromRequestFilter()
    {
        $_REQUEST = array(
            '_px_fk' => 'id',
            '_px_fv' => '1'
        );
        $request = new Request('/test');

        $pag = new Pluf_Paginator(new Pluf_Paginator_MyModel());
        $fields = array(
            'id',
            'title',
            'description'
        );
        $pag->configure($fields, $fields);
        $pag->list_filters = $fields;
        $pag->items_per_page = 5;
        $pag->setFromRequest($request);

        $result = $pag->render_object();
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('items', $result));
    }

    /**
     * Test filter function
     *
     * @test
     */
    public function testSetFromRequestFilterFunction()
    {
        $item1 = new Pluf_Paginator_MyModel();
        $item1->title = 'a';
        $item1->description = 'description';
        $item1->create();

        $_REQUEST = array(
            '_px_fk' => 'id',
            '_px_fv' => $item1->id
        );
        $request = new Request('/test');

        $pag = new Pluf_Paginator(new Pluf_Paginator_MyModel());
        $fields = array(
            'id',
            'title',
            'description'
        );
        $pag->configure($fields, $fields);
        $pag->list_filters = $fields;
        $pag->items_per_page = 5;
        $pag->setFromRequest($request);

        $result = $pag->render_object();
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('items', $result));
        $this->assertTrue(sizeof($result['items']) === 1);

        $item1->delete();
    }

    /**
     * Test multi filter from request
     *
     * @test
     */
    public function testSetFromRequestMultiFilter()
    {
        $item1 = new Pluf_Paginator_MyModel();
        $item1->title = 'a';
        $item1->description = 'description';
        $item1->create();

        $item2 = new Pluf_Paginator_MyModel();
        $item2->title = 'b';
        $item2->description = 'description';
        $item2->create();

        $_REQUEST = array(
            '_px_fk' => array(
                'id',
                'title'
            ),
            '_px_fv' => array(
                $item1->id,
                $item1->title
            )
        );
        $request = new Request('/test');

        $pag = new Pluf_Paginator(new Pluf_Paginator_MyModel());
        $fields = array(
            'id',
            'title',
            'description'
        );
        $pag->configure($fields, $fields);
        $pag->list_filters = $fields;
        $pag->items_per_page = 5;
        $pag->setFromRequest($request);

        $result = $pag->render_object();
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('items', $result));
        $this->assertEquals(sizeof($result['items']), 1);

        $item1->delete();
        $item2->delete();
    }

    /**
     * Test same filter keys
     *
     * @test
     */
    public function testSetFromRequestSameFilter()
    {
        $item1 = new Pluf_Paginator_MyModel();
        $item1->title = 'a';
        $item1->description = 'description';
        $item1->create();

        $item2 = new Pluf_Paginator_MyModel();
        $item2->title = 'b';
        $item2->description = 'description';
        $item2->create();

        $_REQUEST = array(
            '_px_fk' => array(
                'id',
                'id'
            ),
            '_px_fv' => array(
                $item1->id,
                $item2->id
            )
        );
        $request = new Request('/test');

        $pag = new Pluf_Paginator(new Pluf_Paginator_MyModel());
        $fields = array(
            'id',
            'title',
            'description'
        );
        $pag->configure($fields, $fields);
        $pag->list_filters = $fields;
        $pag->items_per_page = 5;
        $pag->setFromRequest($request);

        $result = $pag->render_object();
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('items', $result));
        $this->assertTrue(sizeof($result['items']) === 2);

        $item1->delete();
        $item2->delete();
    }

    /**
     * Test search in items
     *
     * @test
     */
    public function testSearchPaginate()
    {
        $pag = new Pluf_Paginator(new Pluf_Paginator_MyModel());
        // $fields = array(
        // 'id',
        // 'title',
        // 'description'
        // );
        // $pag->configure($fields, $fields);
        // $pag->list_filters = $fields;
        $pag->items_per_page = 5;
        $pag->search_string = 'test';

        $this->assertTrue(is_array($pag->render_object()));
        $this->assertTrue(array_key_exists('items', $pag->render_object()));
    }

    /**
     * Test search function when query is set from request
     *
     * @test
     */
    public function testSearchSetFromRequest()
    {
        $item1 = new Pluf_Paginator_MyModel();
        $item1->title = 'my test title';
        $item1->description = 'description about my test item';
        $item1->int_field = 100;
        $item1->float_field = 200.0;
        $item1->create();

        $_REQUEST = array(
            '_px_q' => 'test'
        );
        $request = new Request('/test');

        $pag = new Pluf_Paginator(new Pluf_Paginator_MyModel());
        $fields = array(
            'id',
            'title',
            'description',
            'int_field',
            'float_field'
        );
        $pag->configure($fields, $fields);
        $pag->list_filters = $fields;
        $pag->items_per_page = 5;
        $pag->setFromRequest($request);

        $result = $pag->render_object();
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('items', $result));
        $this->assertTrue(sizeof($result['items']) === 1);

        $item1->delete();
    }

    /**
     * Test filter key validation
     *
     * @test
     * @expectedException Pluf_Exception_BadRequest
     */
    public function testValidationForFilterKeys()
    {
        $item1 = new Pluf_Paginator_MyModel();
        $item1->title = 'a';
        $item1->description = 'description';
        $item1->create();

        $item2 = new Pluf_Paginator_MyModel();
        $item2->title = 'b';
        $item2->description = 'description';
        $item2->create();

        $_REQUEST = array(
            '_px_fk' => array(
                'id',
                'title;'
            ),
            '_px_fv' => array(
                $item1->id,
                $item1->title
            )
        );
        $request = new Request('/test');

        $pag = new Pluf_Paginator(new Pluf_Paginator_MyModel());
        $fields = array(
            'id',
            'title',
            'description'
        );
        $pag->configure($fields, $fields);
        $pag->list_filters = $fields;
        $pag->items_per_page = 5;
        $pag->setFromRequest($request);

        $pag->render_object();

        $item1->delete();
        $item2->delete();
    }

    /**
     * Test sort key validation
     *
     * @test
     * @expectedException Pluf_Exception_BadRequest
     */
    public function testValidationForSortKeys()
    {
        $pag = new Pluf_Paginator(new Pluf_Paginator_MyModel());
        $fields = array(
            'id',
            'title',
            'description'
        );
        $pag->configure($fields, $fields);
        $pag->list_filters = $fields;
        $pag->items_per_page = 5;
        $pag->sort_order = array(
            array(
                'id',
                'ASC'
            ),
            array(
                'title',
                'ASC'
            )
        );

        $item1 = new Pluf_Paginator_MyModel();
        $item1->title = 'a';
        $item1->description = 'description';
        $item1->create();

        $item2 = new Pluf_Paginator_MyModel();
        $item2->title = 'b';
        $item2->description = 'description';
        $item2->create();

        $_REQUEST = array(
            '_px_sk' => array(
                'id',
                'title$'
            ),
            '_px_so' => array(
                'a',
                'a'
            )
        );
        $request = new Request('/test');

        $pag = new Pluf_Paginator(new Pluf_Paginator_MyModel());
        $fields = array(
            'id',
            'title',
            'description'
        );
        $pag->configure($fields, $fields);
        $pag->list_filters = $fields;
        $pag->items_per_page = 5;
        $pag->setFromRequest($request);

        $pag->render_object();

        $item1->delete();
        $item2->delete();
    }
}
