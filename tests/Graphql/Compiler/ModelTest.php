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
namespace Pluf\Test\Graphql\Compiler;

use PHPUnit\Framework\TestCase;
use Pluf\Graphql\Compiler;
use Pluf\NoteBook\Book;
use Pluf;

class ModelTest extends TestCase
{

    /**
     *
     * @beforeClass
     */
    public static function installApplication1()
    {
        Pluf::start(__DIR__ . '/../../conf/config.php');
        $m = new \Pluf\Migration();
        $m->install();
    }

    /**
     *
     * @afterClass
     */
    public static function removeDatabses1()
    {
        Pluf::start(__DIR__ . '/../../conf/config.php');
        $m = new \Pluf\Migration();
        $m->uninstall();
    }

    /**
     *
     * @test
     */
    public function testRenderAndLoad()
    {
        $types = [
            // model item
            '\Pluf\NoteBook\Book' => null,
            '\Pluf\NoteBook\Item' => null,
            '\Pluf\NoteBook\Tag' => null
        ];
        foreach ($types as $rootType => $itemType) {
            $class_name = 'Pluf_GraphQl_TestRender_' . rand();
            $filename = Pluf::f('tmp_folder', '/tmp') . '/' . $class_name . '.phps';
            if (file_exists($filename)) {
                unlink($filename);
            }
            $compiler = new Compiler($rootType, $itemType);
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
        $filename = Pluf::f('tmp_folder', '/tmp') . '/' . $class_name . '.phps';
        if (file_exists($filename)) {
            unlink($filename);
        }
        $compiler = new Compiler(Book::class);
        $compiler->write($class_name, $filename);
        $this->assertTrue(file_exists($filename));

        include $filename;
        class_exists($class_name);

        $rootValue = new Book();
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




