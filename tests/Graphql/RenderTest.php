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
namespace Pluf\Test\Graphql;

use PHPUnit\Framework\TestCase;
use Pluf\Graphql;
use Pluf\NoteBook\Book;
use Pluf;

class RenderTest extends TestCase
{

    /**
     *
     * @beforeClass
     */
    public static function installApplication1()
    {
        // Load config
        Pluf::start(__DIR__ . '/../conf/config.php');
        $migration = new \Pluf\Migration();
        $migration->install();
    }

    /**
     *
     * @afterClass
     */
    public static function removeDatabses1()
    {
        Pluf::start(__DIR__ . '/../conf/config.php');
        $m = new \Pluf\Migration();
        $m->uninstall();
    }

    /**
     *
     * @test
     */
    public function testRenderAndRun()
    {
        $rootValue = new Book();
        $rootValue->id = 1;
        $rootValue->title = 'title';
        $rootValue->description = 'description';

        $gl = new Graphql();
        $result = $gl->render($rootValue, '{id, title, description}');
        $this->assertTrue(array_key_exists('id', $result));
        $this->assertTrue(array_key_exists('title', $result));
        $this->assertTrue(array_key_exists('description', $result));
    }

    /**
     *
     * @test
     */
    public function testRenderAndRunNonDebug()
    {
        $rootValue = new Book();
        $rootValue->id = 1;
        $rootValue->title = 'title';
        $rootValue->description = 'description';

        $conf = include __DIR__ . '/../conf/config.php';
        $conf['installed_apps'] = array(
            'bootstrap',
            'Test'
        );
        $conf['debug'] = false;
        Pluf::start($conf);

        for ($i = 0; $i < 2; $i ++) {
            $gl = new Graphql();
            $result = $gl->render($rootValue, '{id, title, description}');
            $this->assertTrue(array_key_exists('id', $result));
            $this->assertTrue(array_key_exists('title', $result));
            $this->assertTrue(array_key_exists('description', $result));
        }
    }
}



