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
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Pluf_Graphql_Compiler_ModelRelationTest extends TestCase
{

    /**
     *
     * @before
     */
    public function installApplication()
    {
        $conf = include __DIR__ . '/../conf/config.php';
        $conf['installed_apps'] = array(
            'Pluf',
            'Test'
        );
        Pluf::start($conf);
        $m = new Pluf_Migration($conf['installed_apps']);
        $m->install();
    }

    /**
     *
     * @afterClass
     */
    public static function removeDatabses()
    {
        $m = new Pluf_Migration(array(
            'Pluf',
            'Test'
        ));
        $m->unInstall();
    }

    /**
     *
     * @test
     */
    public function testRenderAndRun()
    {
        // create data
        $model = new Test_ModelRecurse();
        $model->title = 'myvalue';
        $this->assertEquals('myvalue', $model->title);
        $model->create();

        $model2 = new Test_ModelRecurse();
        $model2->title = 'child';
        $model2->parent_id = $model;
        $this->assertEquals(true, $model2->create());

        $class_name = 'Pluf_GraphQl_Model_Test_' . rand();
        $filename = dirname(__FILE__) . '/../tmp/' . $class_name . '.phps';
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
    }
}




