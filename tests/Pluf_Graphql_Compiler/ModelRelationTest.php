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
use PHPUnit\Framework\TestCase;

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../apps');

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Pluf_Graphql_Compiler_ModelRelationTest extends TestCase
{

    /**
     *
     * @beforeClass
     */
    public static function installApplication1()
    {
        $conf = include __DIR__ . '/../conf/config.php';
        $conf['installed_apps'] = array(
            'bootstrap',
            'Test'
        );
        Pluf::start($conf);
        $m = new \Pluf\Migration($conf['installed_apps']);
        $m->install();
    }

    /**
     *
     * @afterClass
     */
    public static function removeDatabses1()
    {
        $m = new \Pluf\Migration(array(
            'bootstrap',
            'Test'
        ));
        $m->uninstall();
    }

    /**
     *
     * @test
     */
    public function testForeignkeyRenderAndRun()
    {
        // create data
        $model = new Test_ModelRecurse();
        $model->title = 'myvalue';
        $this->assertEquals('myvalue', $model->title);
        $model->create();

        $model2 = new Test_ModelRecurse();
        $model2->title = 'child 1';
        $model2->parent_id = $model;
        $this->assertEquals(true, $model2->create());

        $model3 = new Test_ModelRecurse();
        $model3->title = 'child 2';
        $model3->parent_id = $model;
        $this->assertEquals(true, $model3->create());

        $class_name = 'Pluf_GraphQl_Model_Test_' . rand();
        $filename = Pluf::f('tmp_folder', '/tmp') . '/' . $class_name . '.phps';
        if (file_exists($filename)) {
            unlink($filename);
        }
        $compiler = new Pluf_Graphql_Compiler('Test_ModelRecurse');
        $compiler->write($class_name, $filename);
        $this->assertTrue(file_exists($filename));
        include $filename;

        $rootValue = new Test_ModelRecurse($model2->id);

        // get all
        $compiler = new $class_name();
        $result = $compiler->render($rootValue, '{id, parent{id}}');
        $this->assertFalse(array_key_exists('errors', $result));

        $result = $result['data'];
        $this->assertTrue(array_key_exists('parent', $result));

        $parnet = $result['parent'];
        $this->assertEquals($parnet['id'], $model->id);

        $rootValue = new Test_ModelRecurse($model->id);
        $result = $compiler->render($rootValue, '{id, title, children{id, title, parent_id, parent{id}}}');
        $this->assertFalse(array_key_exists('errors', $result));
        $result = $result['data'];
        $this->assertTrue(array_key_exists('children', $result));
        $children = $result['children'];
        $this->assertEquals(sizeof($children), 2);
        foreach ($children as $child) {
            $this->assertTrue(array_key_exists('parent_id', $child));
            $this->assertTrue(array_key_exists('parent', $child));
        }
    }

    /**
     *
     * @test
     */
    public function testManyToManyRenderAndRun()
    {
        // create data
        $model = new Test_ManyToManyOne();
        $model->one = 'One item ';
        $this->assertTrue($model->create());

        $model2 = new Test_ManyToManyTwo();
        $model2->two = 'Two item';
        $this->assertEquals(true, $model2->create());

        $model->setAssoc($model2);

        $class_name = 'Pluf_GraphQl_Model_ManyToMany_' . rand();
        $filename = Pluf::f('tmp_folder', '/tmp') . '/' . $class_name . '.phps';
        if (file_exists($filename)) {
            unlink($filename);
        }
        $compiler = new Pluf_Graphql_Compiler('Test_ManyToManyOne');
        $compiler->write($class_name, $filename);
        $this->assertTrue(file_exists($filename));
        include $filename;

        $rootValue = new Test_ManyToManyOne($model->id);

        // get all
        $compiler = new $class_name();
        $result = $compiler->render($rootValue, '{id, twos{id}}');
        $this->assertFalse(array_key_exists('errors', $result));

        $result = $result['data'];
        $this->assertTrue(array_key_exists('twos', $result));

        $parnet = $result['twos'][0];
        $this->assertEquals($parnet['id'], $model->id);

        //
        $result = $compiler->render($rootValue, '{id, twos{id, ones{id}}}');
        $this->assertFalse(array_key_exists('errors', $result));
        $this->assertEquals($result['data']['twos'][0]['ones'][0]['id'], $rootValue->id);
    }
}




