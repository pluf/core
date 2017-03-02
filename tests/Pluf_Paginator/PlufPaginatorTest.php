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


require_once dirname(__FILE__).'/../Pluf_Form/TestFormModel.php';


/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class PlufPaginatorTest extends PHPUnit_Framework_TestCase {
    
    protected function setUp()
    {
        Pluf::start(dirname(__FILE__).'/../conf/pluf.config.php');
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $m1 = new TestFormModel();
        $schema->model = $m1;
        $schema->dropTables();
        $schema->createTables();
        for ($i=1; $i<11; $i++) {
            $m = new TestFormModel();
            $m->title = 'My title '.$i;
            $m->description = 'My description '.$i;
            $m->create();
        }
    }

    protected function tearDown()
    {
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $m1 = new TestFormModel();
        $schema->model = $m1;
        $schema->dropTables();
    }

    public function testSimplePaginate()
    {
//         $model = new TestFormModel();
//         $pag = Pluf::factory('Pluf_Paginator', $model);
//         $pag->action = '/permission/'; 
//         $pag->items_per_page = 5;
//         $render = file_get_contents(dirname(__FILE__).'/rendersimplepaginate.html');
//         $this->assertEquals($render, $pag->render());
//         $this->assertEquals($pag->page_number, 2);
    }
}

?>