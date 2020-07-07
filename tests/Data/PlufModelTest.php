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
use Pluf\Relation\ManyToManyTwo;
use Pluf\Relation\Model;
use Pluf\Relation\ModelCount;
use Pluf\Relation\ModelRecurse;
use Pluf\Relation\RelatedToTestModel;
use Pluf\Relation\RelatedToTestModel2;
use Pluf;

class PlufModelTest extends TestCase
{

    /**
     *
     * @beforeClass
     */
    public static function createDataBase()
    {
        $conf = include __DIR__ . '/../conf/config.php';
        $conf['installed_apps'] = [
            'Relation'
        ];
        Pluf::start($conf);

        $m = new \Pluf\Migration();
        $m->install();
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
    public function testSetModelField()
    {
        $model = new Model();
        $model->title = 'myvalue';
        $this->assertEquals('myvalue', $model->title);
    }

    /**
     *
     * @test
     */
    public function testTestModelRecurse()
    {
        $model = new ModelRecurse();
        $model->title = 'myvalue';
        $this->assertEquals('myvalue', $model->title);
        $model->create();

        $model2 = new ModelRecurse();
        $model2->title = 'child';
        $model2->parent_id = $model;
        $model2->create();
        $this->assertFalse($model2->isAnonymous());

        $a = $model->get_children_list();
        $this->assertEquals($a[0]->title, 'child');
    }

    /**
     *
     * @test
     */
    public function testCreateTestModel()
    {
        $model = new Model();
        $model->title = 'my title';
        $model->description = 'A small desc.';
        $model->create();
        $this->assertFalse($model->isAnonymous());
    }

    /**
     *
     * @test
     */
    public function testGetTestModel()
    {
        $model = new Model();
        $model->title = 'my title';
        $model->description = 'A small desc.';
        $model->create();

        $m = new Model($model->id);
        $this->assertEquals($model->title, $m->title);
    }

    /**
     *
     * @test
     */
    public function testUpdateTestModel()
    {
        $model = new Model();
        $model->title = 'my title';
        $model->description = 'A small desc.';
        $model->create();
        $model = new Model(1);
        $model->description = 'A small desc 2.';
        $this->assertEquals(true, $model->update());
        $this->assertEquals('A small desc 2.', $model->description);
    }

    /**
     *
     * @test
     */
    public function testDeleteTestModel()
    {
        $model = new Model();
        $model->title = 'my title';
        $model->description = 'A small desc.';
        $model->create();
        $this->assertFalse($model->isAnonymous());
        $id = $model->id;
        $this->assertEquals(true, $model->delete());
        $this->assertTrue($model->isAnonymous());

        $model = new Model($id);
        $this->assertTrue($model->isAnonymous());
    }

    /**
     *
     * @test
     */
    public function testGetListTestModel()
    {
        for ($i = 0; $i < 10; $i ++) {
            $model = new ModelCount();
            $model->title = 'title ' . $i;
            $model->description = 'A small desc ' . $i;
            $model->create();
        }
        $model->title = 'update to have 11 records and 10 head';
        $model->update();
        $m = new ModelCount();
        $models = $m->getList();
        $this->assertEquals(10, count($models));
        $this->assertEquals('title 0', $models[0]->title);
        $this->assertEquals('title 5', $models[5]->title);

        foreach ($models as $item) {
            $this->assertTrue($item->delete());
        }
    }

    /**
     *
     * @test
     */
    public function testGetCountModel()
    {
        for ($i = 0; $i < 10; $i ++) {
            $model = new ModelCount();
            $model->title = 'title ' . $i;
            $model->description = 'A small desc ' . $i;
            $model->create();
        }
        $model->title = 'update to have 11 records and 10 head';
        $model->update();
        $m = new ModelCount();
        $this->assertEquals(10, $m->getCount());

        $models = $m->getList();
        foreach ($models as $item) {
            $this->assertTrue($item->delete());
        }
    }

    /**
     *
     * @test
     */
    public function testRelatedTestModel()
    {
        $model = new Model();
        $model->title = 'title';
        $model->description = 'A small desc ';
        $model->create();
        $this->assertFalse($model->isAnonymous());

        $m = new RelatedToTestModel();
        $m->testmodel = $model;
        $m->dummy = 'stupid values';
        $m->create();
        $this->assertFalse($m->isAnonymous());

        $rel = $model->get_related_list();
        $this->assertEquals('stupid values', $rel[0]->dummy);
        $mod = $m->get_testmodel();
        $this->assertEquals('title', $mod->title);
    }

    /**
     *
     * @test
     */
    public function testLimitRelatedTestModel()
    {
        $model = new Model();
        $model->title = 'title';
        $model->description = 'A small desc ';
        $model->create();
        $this->assertFalse($model->isAnonymous());

        $m = new RelatedToTestModel();
        $m->testmodel = $model;
        $m->dummy = 'stupid values';
        $m->create();
        $this->assertFalse($m->isAnonymous());

        $m = new RelatedToTestModel();
        $m->testmodel = $model;
        $m->dummy = 'stupid values 2';
        $m->create();
        $this->assertFalse($m->isAnonymous());

        $m = new RelatedToTestModel();
        $m->testmodel = $model;
        $m->dummy = 'stupid values 3';
        $m->create();
        $this->assertFalse($m->isAnonymous());

        $rel = $model->get_related_list([
            'filter' => [
                [
                    'dummy',
                    '=',
                    'stupid values 2'
                ]
            ] // "dummy='stupid values 2'"
        ]);
        $this->assertEquals('stupid values 2', $rel[0]->dummy);
        $this->assertEquals(1, count($rel));
        $rel = $model->get_related_list();
        $this->assertEquals(3, count($rel));
    }

    /**
     *
     * @test
     */
    public function testManyRelatedTestModel()
    {
        $tm1 = new Model();
        $tm1->title = 'title tm1';
        $tm1->description = 'A small desc tm1';
        $tm1->create();

        $tm2 = new Model();
        $tm2->title = 'title tm2';
        $tm2->description = 'A small desc tm2';
        $tm2->create();

        $tm3 = new Model();
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

        $tm1_2 = new Model($tm1->id);
        $this->assertEquals($tm1->id, $tm1_2->id);

        $tm1bis = $rm2->get_testmodel_1();
        $this->assertEquals('title tm1', $tm1bis->title);
    }

    /**
     *
     * @test
     */
    public function testRelatedToNotCreatedTestModel()
    {
        $m2 = new ManyToManyTwo();
        $m2->two = 'two is the best';
        $rel = $m2->get_ones_list();
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
        $model = new Model();
        // $model->title = 'title';
        $model->description = 'A small desc ';
        $this->assertEquals(true, $model->create());
    }
}

