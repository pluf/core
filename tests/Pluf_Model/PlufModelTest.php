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

include_once dirname(__FILE__) . '/TestModels.php';

class PlufModelTest extends TestCase
{

    protected function setUp ()
    {
        Pluf::start(dirname(__FILE__) . '/../conf/pluf.config.php');
        $m = require dirname(__FILE__) . '/relations.php';
        $GLOBALS['_PX_models'] = array_merge($m, $GLOBALS['_PX_models']);
        $GLOBALS['_PX_config']['pluf_use_rowpermission'] = false;
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $m1 = new TestModel();
        $m2 = new RelatedToTestModel();
        $m3 = new RelatedToTestModel2();
        $m4 = new TestModelRecurse();
        $schema->model = $m1;
        $schema->dropTables();
        $schema->createTables();
        $schema->model = $m2;
        $schema->dropTables();
        $schema->createTables();
        $schema->model = $m3;
        $schema->dropTables();
        $schema->createTables();
        $schema->model = $m4;
        $schema->dropTables();
        $schema->createTables();
    }

    protected function tearDown ()
    {
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $m1 = new TestModel();
        $schema->model = $m1;
        $schema->dropTables();
        $m2 = new RelatedToTestModel();
        $schema->model = $m2;
        $schema->dropTables();
        $m3 = new RelatedToTestModel2();
        $schema->model = $m3;
        $schema->dropTables();
        $m4 = new TestModelRecurse();
        $schema->model = $m4;
        $schema->dropTables();
    }

    public function testSetModelField ()
    {
        $model = new TestModel();
        $model->title = 'myvalue';
        $this->assertEquals('myvalue', $model->title);
    }

    public function testTestModelRecurse ()
    {
        $model = new TestModelRecurse();
        $model->title = 'myvalue';
        $this->assertEquals('myvalue', $model->title);
        $model->create();
        $model2 = new TestModelRecurse();
        $model2->title = 'child';
        $model2->parentid = $model;
        $this->assertEquals(true, $model2->create());
        $a = $model->get_children_list();
        $this->assertEquals($a[0]->title, 'child');
    }

    public function testCreateTestModel ()
    {
        $model = new TestModel();
        $model->title = 'my title';
        $model->description = 'A small desc.';
        $this->assertEquals(true, $model->create());
        $this->assertEquals(1, (int) $model->id);
    }

    public function testGetTestModel ()
    {
        $model = new TestModel();
        $model->title = 'my title';
        $model->description = 'A small desc.';
        $model->create();
        $m = new TestModel(1);
        $this->assertEquals('my title', $m->title);
    }

    public function testUpdateTestModel ()
    {
        $model = new TestModel();
        $model->title = 'my title';
        $model->description = 'A small desc.';
        $model->create();
        $model = new TestModel(1);
        $model->description = 'A small desc 2.';
        $this->assertEquals(true, $model->update());
        $this->assertEquals('A small desc 2.', $model->description);
    }

    public function testDeleteTestModel ()
    {
        $model = new TestModel();
        $model->title = 'my title';
        $model->description = 'A small desc.';
        $model->create();
        $this->assertEquals(1, $model->id);
        $this->assertEquals(true, $model->delete());
        $this->assertEquals('', $model->id);
        $this->assertEquals('', $model->title);
        $model = new TestModel();
        $this->assertEquals(false, $model->get(1));
    }

    public function testGetListTestModel ()
    {
        for ($i = 0; $i < 10; $i ++) {
            $model = new TestModel();
            $model->title = 'title ' . $i;
            $model->description = 'A small desc ' . $i;
            $model->create();
        }
        $model->title = 'update to have 11 records and 10 head';
        $model->update();
        $m = new TestModel();
        $models = $m->getList();
        $this->assertEquals(10, count($models));
        $this->assertEquals('title 0', $models[0]->title);
        $this->assertEquals('title 5', $models[5]->title);
    }

    public function testGetCountModel ()
    {
        for ($i = 0; $i < 10; $i ++) {
            $model = new TestModel();
            $model->title = 'title ' . $i;
            $model->description = 'A small desc ' . $i;
            $model->create();
        }
        $model->title = 'update to have 11 records and 10 head';
        $model->update();
        $m = new TestModel();
        $this->assertEquals(10, $m->getCount());
    }

    public function testRelatedTestModel ()
    {
        $model = new TestModel();
        $model->title = 'title';
        $model->description = 'A small desc ';
        $this->assertEquals(true, $model->create());
        $m = new RelatedToTestModel();
        $m->testmodel = $model;
        $m->dummy = 'stupid values';
        $this->assertEquals(true, $m->create());
        $rel = $model->get_relatedtotestmodel_list();
        $this->assertEquals('stupid values', $rel[0]->dummy);
        $mod = $m->get_testmodel();
        $this->assertEquals('title', $mod->title);
    }

    public function testLimitRelatedTestModel ()
    {
        $model = new TestModel();
        $model->title = 'title';
        $model->description = 'A small desc ';
        $this->assertEquals(true, $model->create());
        $m = new RelatedToTestModel();
        $m->testmodel = $model;
        $m->dummy = 'stupid values';
        $this->assertEquals(true, $m->create());
        $m = new RelatedToTestModel();
        $m->testmodel = $model;
        $m->dummy = 'stupid values 2';
        $this->assertEquals(true, $m->create());
        $m = new RelatedToTestModel();
        $m->testmodel = $model;
        $m->dummy = 'stupid values 3';
        $this->assertEquals(true, $m->create());
        $rel = $model->get_relatedtotestmodel_list(
                array(
                        'filter' => "dummy='stupid values 2'"
                ));
        $this->assertEquals('stupid values 2', $rel[0]->dummy);
        $this->assertEquals(1, count($rel));
        $rel = $model->get_relatedtotestmodel_list();
        $this->assertEquals(3, count($rel));
    }

    /**
     * Test if the delete() call on a model is deleting the model
     * related through a foreignkey.
     */
    public function testDeleteRelatedModels ()
    {
        $model = new TestModel();
        $model->title = 'title';
        $model->description = 'A small desc ';
        $model->create();
        $m1 = new RelatedToTestModel();
        $m1->testmodel = $model;
        $m1->dummy = 'stupid values';
        $m1->create();
        $m2 = new RelatedToTestModel();
        $m2->testmodel = $model;
        $m2->dummy = 'stupid values';
        $m2->create();
        $m3 = new RelatedToTestModel();
        $m3->testmodel = $model;
        $m3->dummy = 'stupid values';
        $m3->create();
        $rel = $model->get_relatedtotestmodel_list();
        $this->assertEquals(3, count($rel));
        $this->assertEquals(0, count($m2->getDeleteSideEffect()));
        $m2->delete();
        $rel = $model->get_relatedtotestmodel_list();
        $this->assertEquals(2, count($rel));
        $this->assertEquals(2, count($model->getDeleteSideEffect()));
        $model->delete();
        $mr = new RelatedToTestModel();
        $rel = $mr->getList();
        $this->assertEquals(0, count($rel));
    }

    public function testManyRelatedTestModel ()
    {
        $tm1 = new TestModel();
        $tm1->title = 'title tm1';
        $tm1->description = 'A small desc tm1';
        $tm1->create();
        $tm2 = new TestModel();
        $tm2->title = 'title tm2';
        $tm2->description = 'A small desc tm2';
        $tm2->create();
        $tm3 = new TestModel();
        $tm3->title = 'title tm3';
        $tm3->description = 'A small desc tm3';
        $tm3->create();
        
        $rm1 = new RelatedToTestModel2();
        $rm1->testmodel_1 = $tm1;
        $rm1->testmodel_2 = $tm2;
        $rm1->dummy = 'stupid values rm1';
        $rm1->create();
        $rm2 = new RelatedToTestModel2();
        $rm2->testmodel_1 = $tm1;
        $rm2->testmodel_2 = $tm2;
        $rm2->dummy = 'stupid values rm2';
        $rm2->create();
        $rm3 = new RelatedToTestModel2();
        $rm3->testmodel_1 = $tm1;
        $rm3->testmodel_2 = $tm3;
        $rm3->dummy = 'stupid values rm3';
        $rm3->create();
        
        $rel = $tm1->get_first_rttm_list();
        $this->assertEquals(3, count($rel));
        $this->assertEquals('stupid values rm1', $rel[0]->dummy);
        $rel = $tm2->get_first_rttm_list();
        $this->assertEquals(0, count($rel));
        $rel = $tm2->get_second_rttm_list();
        $this->assertEquals(2, count($rel));
        $this->assertEquals('stupid values rm2', $rel[1]->dummy);
        $rel = $tm3->get_second_rttm_list();
        $this->assertEquals(1, count($rel));
        $this->assertEquals('stupid values rm3', $rel[0]->dummy);
        $tm1bis = $rm2->get_testmodel_1();
        $this->assertEquals('title tm1', $tm1bis->title);
    }

    public function testRelatedToNotCreatedTestModel ()
    {
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $m2 = new ManyToManyTwo();
        $schema->model = $m2;
        $this->assertEquals(true, $schema->dropTables());
        $this->assertEquals(true, $schema->createTables());
        $m2->two = 'two is the best';
        $rel = $m2->get_manytomanyone_list();
        $this->assertNotEquals(false, $rel);
        $this->assertEquals(0, count($rel));
        $this->assertEquals(true, $schema->dropTables());
    }

    /**
     * Create the tables and test if association is working.
     */
    public function testManyToManyModels ()
    {
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $m1 = new ManyToManyOne();
        $schema->model = $m1;
        $this->assertEquals(true, $schema->dropTables());
        $this->assertEquals(true, $schema->createTables());
        $m1->one = 'one is the best';
        $this->assertEquals(true, $m1->create());
        $this->assertEquals(1, $m1->id);
        $m2 = new ManyToManyTwo();
        $schema->model = $m2;
        $this->assertEquals(true, $schema->dropTables());
        $this->assertEquals(true, $schema->createTables());
        $m2->two = 'two is the best';
        $this->assertEquals(true, $m2->create());
        $m1->setAssoc($m2);
        $m3 = new ManyToManyTwo();
        $m3->two = 'two bis is the best';
        $this->assertEquals(true, $m3->create());
        $m1->setAssoc($m3);
        $rel = $m1->get_two_list();
        $this->assertEquals(2, count($rel));
        $this->assertEquals('two is the best', $rel[0]->two);
        $this->assertEquals('two bis is the best', $rel[1]->two);
        // Has the many to many relationship is set on ManyToManyOne
        // ManyToManyTwo is accessing the other model through the
        // get_nameofthemodelclass_list syntax
        $rel = $m2->get_manytomanyone_list();
        $this->assertEquals(1, count($rel));
        $this->assertEquals('one is the best', $rel[0]->one);
        $m1->delAssoc($m3);
        $rel = $m1->get_two_list();
        $this->assertEquals(1, count($rel));
    }

    public function testExceptionOnProperty ()
    {
        $model = new TestModel();
        $model->title = 'title';
        $model->description = 'A small desc ';
        $this->assertEquals(true, $model->create());
        try {
            $rel = $model->should_fail;
            // next line should not be called
            $this->assertEquals(true, false);
        } catch (Exception $e) {
            $this->assertEquals('Cannot get property "should_fail".', 
                    $e->getMessage());
        }
    }
}

?>