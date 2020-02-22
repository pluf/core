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
use Pluf\Paginator;
use Pluf\Bootstrap;
use Pluf\HTTP\Request;

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Pluf_Paginator_GeoPaginatorTest extends TestCase
{

    /**
     *
     * @before
     */
    protected function setUpTest()
    {
        Bootstrap::start(__DIR__ . '/../conf/config.php');
        $dbEngine = Bootstrap::f('db_engine');
        if (strcasecmp($dbEngine, 'MySQL') !== 0) {
            $this->markTestSkipped('Test could be run only on MySql database');
        }

        $schema = new \Pluf\DB\Schema(Bootstrap::db());

        $m1 = new PaginatorMyGeoModel();
        $schema->model = $m1;
        $schema->dropTables();
        $schema->createTables();
        for ($i = 0; $i < 11; $i ++) {
            $m = new PaginatorMyGeoModel();
            $m->title = 'My title ' . $i;
            $m->location = 'POINT(' . $i . ' ' . $i . ')';
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
        $m1 = new PaginatorMyGeoModel();
        $schema->model = $m1;
        $schema->dropTables();
    }

    /**
     * Test filter from request
     *
     * @test
     */
    public function testSetFromRequestFilter()
    {
        $_REQUEST = array(
            '_px_fk' => 'location',
            '_px_fv' => 'POLYGON ((0 0, 12 0, 12 12, 0 12, 0 0))'
        );
        $request = new Request('/test');

        $pag = new Paginator(new PaginatorMyGeoModel());
        $fields = array(
            'id',
            'title',
            'location'
        );
        $pag->configure($fields, $fields);
        $pag->list_filters = $fields;
        $pag->items_per_page = 30;
        $pag->setFromRequest($request);

        $result = $pag->render_object();
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('items', $result));
        $this->assertEquals(11, sizeof($result['items']));
    }
}
