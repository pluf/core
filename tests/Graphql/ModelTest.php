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
namespace Pluf\PlufTest\Graphql;

use PHPUnit\Framework\TestCase;
use Pluf\Bootstrap;
use Pluf\Migration;
use Pluf\Graphql;

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class ModelTest extends TestCase
{

    /**
     *
     * @beforeClass
     */
    public static function installApplication1()
    {
        $conf = include __DIR__ . '/../conf/config.php';
        $conf['installed_apps'] = array(
            'Pluf',
            'Test'
        );
        Bootstrap::start($conf);
        $m = new Migration($conf['installed_apps']);
        $m->install();
    }

    /**
     *
     * @afterClass
     */
    public static function removeDatabses1()
    {
        $m = new Migration(array(
            'Pluf',
            'Test'
        ));
        $m->unInstall();
    }

    /**
     *
     * @test
     */
    public function testRenderAndLoad()
    {
        $types = [
            // model item
            '\Pluf\Test\Model' => null,
            '\Pluf\Test\ModelRecurse' => null,
            '\Pluf\Test\ModelCount' => null
        ];
        foreach ($types as $rootType => $itemType) {
            $class_name = 'Pluf_GraphQl_TestRender_' . rand();
            $filename = dirname(__FILE__) . '/../tmp/' . $class_name . '.phps';
            if (file_exists($filename)) {
                unlink($filename);
            }
            $compiler = new Graphql\Compiler($rootType, $itemType);
            $compiler->write($class_name, $filename);
            $this->assertTrue(file_exists($filename));

            include $filename;
            class_exists($class_name);
        }
    }

    /**
     *
     * @test
     */
    public function testRenderAndRun()
    {
        $class_name = 'Pluf_GraphQl_Model_Test_' . rand();
        $filename = dirname(__FILE__) . '/../tmp/' . $class_name . '.phps';
        if (file_exists($filename)) {
            unlink($filename);
        }
        $compiler = new Graphql\Compiler('\Pluf\Test\Model');
        $compiler->write($class_name, $filename);
        $this->assertTrue(file_exists($filename));

        include $filename;
        class_exists($class_name);

        $rootValue = new \Pluf\Test\Model();
        $rootValue->id = 1;
        $rootValue->title = 'title';
        $rootValue->description = 'description';

        // get all
        $compiler = new $class_name();
        $result = $compiler->render($rootValue, '{id, title, description}');
        $this->assertFalse(array_key_exists('errors', $result));
        $this->assertTrue(array_key_exists('data', $result));

        $result = $result['data'];
        $this->assertTrue(array_key_exists('id', $result));
        $this->assertEquals($result['id'], $rootValue->id);

        $this->assertTrue(array_key_exists('title', $result));
        $this->assertEquals($result['title'], $rootValue->title);

        $this->assertTrue(array_key_exists('description', $result));
        $this->assertEquals($result['description'], $rootValue->description);

        // get id
        $compiler = new $class_name();
        $result = $compiler->render($rootValue, '{id}');
        $this->assertFalse(array_key_exists('errors', $result));
        $this->assertTrue(array_key_exists('data', $result));

        $result = $result['data'];
        $this->assertTrue(array_key_exists('id', $result));
        $this->assertEquals($result['id'], $rootValue->id);

        // get invalid
        $compiler = new $class_name();
        $result = $compiler->render($rootValue, '{idx}');
        $this->assertTrue(array_key_exists('errors', $result));
    }
}




