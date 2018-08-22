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

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../apps');

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class PlufModelTest extends TestCase
{

    /**
     * @beforeClass
     */
    public static function createDataBase()
    {
        $conf = include __DIR__ . '/../conf/config.php';
        $conf['installed_apps'] = array(
            'Test'
        );
        Pluf::start($conf);
        $m = new Pluf_Migration(array(
            'Test'
        ));
        $m->install();
    }

    /**
     * @afterClass
     */
    public static function removeDatabses()
    {
        $m = new Pluf_Migration(array(
            'Test'
        ));
        $m->unInstall();
    }

    /**
     * @test
     */
    public function testSetModelField()
    {
        $model = new Test_Model();
        $model->title = 'myvalue';
        $this->assertEquals('myvalue', $model->title);
    }

    /**
     * @test
     */
    public function testTestModelRecurse()
    {
        $model = new Test_ModelRecurse();
        $model->title = 'myvalue';
        $this->assertEquals('myvalue', $model->title);
        $model->create();
        
        $model2 = new Test_ModelRecurse();
        $model2->title = 'child';
        $model2->parent_id = $model;
        $this->assertEquals(true, $model2->create());
        
        $a = $model->get_children_list();
        $this->assertEquals($a[0]->title, 'child');
    }

    /**
     * @test
     */
    public function testCreateTestModel()
    {
        $model = new Test_Model();
        $model->title = 'my title';
        $model->description = 'A small desc.';
        $this->assertEquals(true, $model->create());
        $this->assertEquals(1, (int) $model->id);
    }

    /**
     * @test
     */
    public function testGetTestModel()
    {
        $model = new Test_Model();
        $model->title = 'my title';
        $model->description = 'A small desc.';
        $model->create();
        $m = new Test_Model(1);
        $this->assertEquals('my title', $m->title);
    }

    /**
     * @test
     */
    public function testUpdateTestModel()
    {
        $model = new Test_Model();
        $model->title = 'my title';
        $model->description = 'A small desc.';
        $model->create();
        $model = new Test_Model(1);
        $model->description = 'A small desc 2.';
        $this->assertEquals(true, $model->update());
        $this->assertEquals('A small desc 2.', $model->description);
    }

    /**
     * @test
     */
    public function testDeleteTestModel()
    {
        $model = new Test_Model();
        $model->title = 'my title';
        $model->description = 'A small desc.';
        $model->create();
        $this->assertFalse($model->isAnonymous());
        $id = $model->id;
        $this->assertEquals(true, $model->delete());
        $this->assertTrue($model->isAnonymous());
        
        $model = new Test_Model($id);
        $this->assertTrue($model->isAnonymous());
    }

    /**
     * @test
     */
    public function testGetListTestModel()
    {
        for ($i = 0; $i < 10; $i ++) {
            $model = new Test_ModelCount();
            $model->title = 'title ' . $i;
            $model->description = 'A small desc ' . $i;
            $model->create();
        }
        $model->title = 'update to have 11 records and 10 head';
        $model->update();
        $m = new Test_ModelCount();
        $models = $m->getList();
        $this->assertEquals(10, count($models));
        $this->assertEquals('title 0', $models[0]->title);
        $this->assertEquals('title 5', $models[5]->title);
        
        foreach ($models as $item) {
            $this->assertTrue($item->delete());
        }
    }

    /**
     * @test
     */
    public function testGetCountModel()
    {
        for ($i = 0; $i < 10; $i ++) {
            $model = new Test_ModelCount();
            $model->title = 'title ' . $i;
            $model->description = 'A small desc ' . $i;
            $model->create();
        }
        $model->title = 'update to have 11 records and 10 head';
        $model->update();
        $m = new Test_ModelCount();
        $this->assertEquals(10, $m->getCount());
        
        $models = $m->getList();
        foreach ($models as $item) {
            $this->assertTrue($item->delete());
        }
    }

    /**
     * @test
     */
    public function testRelatedTestModel()
    {
        $model = new Test_Model();
        $model->title = 'title';
        $model->description = 'A small desc ';
        $this->assertEquals(true, $model->create());
        
        $m = new Test_RelatedToTestModel();
        $m->testmodel = $model;
        $m->dummy = 'stupid values';
        $this->assertEquals(true, $m->create());
        
        $rel = $model->get_test_relatedtotestmodel_list();
        $this->assertEquals('stupid values', $rel[0]->dummy);
        $mod = $m->get_testmodel();
        $this->assertEquals('title', $mod->title);
    }

    /**
     * @test
     */
    public function testLimitRelatedTestModel()
    {
        $model = new Test_Model();
        $model->title = 'title';
        $model->description = 'A small desc ';
        $this->assertEquals(true, $model->create());
        
        $m = new Test_RelatedToTestModel();
        $m->testmodel = $model;
        $m->dummy = 'stupid values';
        $this->assertEquals(true, $m->create());
        
        $m = new Test_RelatedToTestModel();
        $m->testmodel = $model;
        $m->dummy = 'stupid values 2';
        $this->assertEquals(true, $m->create());
        
        $m = new Test_RelatedToTestModel();
        $m->testmodel = $model;
        $m->dummy = 'stupid values 3';
        $this->assertEquals(true, $m->create());
        $rel = $model->get_test_relatedtotestmodel_list(array(
            'filter' => "dummy='stupid values 2'"
        ));
        $this->assertEquals('stupid values 2', $rel[0]->dummy);
        $this->assertEquals(1, count($rel));
        $rel = $model->get_test_relatedtotestmodel_list();
        $this->assertEquals(3, count($rel));
    }

    /**
     * Test if the delete() call on a model is deleting the model
     * related through a foreignkey.
     *
     * @test
     */
    public function testDeleteRelatedModels()
    {
        // delete models
        $mr = new Test_RelatedToTestModel();
        $items = $mr->getList();
        foreach ($items as $item) {
            $item->delete();
        }
        
        $model = new Test_Model();
        $model->title = 'title';
        $model->description = 'A small desc ';
        $model->create();
        
        $m1 = new Test_RelatedToTestModel();
        $m1->testmodel = $model;
        $m1->dummy = 'stupid values';
        $m1->create();
        
        $m2 = new Test_RelatedToTestModel();
        $m2->testmodel = $model;
        $m2->dummy = 'stupid values';
        $m2->create();
        
        $m3 = new Test_RelatedToTestModel();
        $m3->testmodel = $model;
        $m3->dummy = 'stupid values';
        $m3->create();
        
        $rel = $model->get_test_relatedtotestmodel_list();
        $this->assertEquals(3, count($rel));
        $this->assertEquals(0, count($m2->getDeleteSideEffect()));
        $m2->delete();
        
        $rel = $model->get_test_relatedtotestmodel_list();
        $this->assertEquals(2, count($rel));
        $this->assertEquals(2, count($model->getDeleteSideEffect()));
        $model->delete();
        
        $mr = new Test_RelatedToTestModel();
        $rel = $mr->getList();
        $this->assertEquals(0, count($rel));
    }

    /**
     * @test
     */
    public function testManyRelatedTestModel()
    {
        $tm1 = new Test_Model();
        $tm1->title = 'title tm1';
        $tm1->description = 'A small desc tm1';
        $tm1->create();
        $tm2 = new Test_Model();
        $tm2->title = 'title tm2';
        $tm2->description = 'A small desc tm2';
        $tm2->create();
        $tm3 = new Test_Model();
        $tm3->title = 'title tm3';
        $tm3->description = 'A small desc tm3';
        $tm3->create();
        
        $rm1 = new Test_RelatedToTestModel2();
        $rm1->testmodel_1 = $tm1;
        $rm1->testmodel_2 = $tm2;
        $rm1->dummy = 'stupid values rm1';
        $rm1->create();
        
        $rm2 = new Test_RelatedToTestModel2();
        $rm2->testmodel_1 = $tm1;
        $rm2->testmodel_2 = $tm2;
        $rm2->dummy = 'stupid values rm2';
        $rm2->create();
        
        $rm3 = new Test_RelatedToTestModel2();
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

    /**
     * @test
     */
    public function testRelatedToNotCreatedTestModel()
    {
        $m2 = new Test_ManyToManyTwo();
        $m2->two = 'two is the best';
        $rel = $m2->get_test_manytomanyone_list();
        $this->assertNotEquals(false, $rel);
        $this->assertEquals(0, count($rel));
    }

    /**
     * 
     * @expectedException Exception
     * @test
     */
    public function testExceptionOnProperty()
    {
        $model = new Test_Model();
        $model->title = 'title';
        $model->description = 'A small desc ';
        $this->assertEquals(true, $model->create());
        $rel = $model->should_fail;
    }
}

