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

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class RenderTest extends TestCase
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
        $rootValue = new \Pluf\Test\Model();
        $rootValue->id = 1;
        $rootValue->title = 'title';
        $rootValue->description = 'description';

        $gl = new \Pluf\Graphql();
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
        $rootValue = new \Pluf\Test\Model();
        $rootValue->id = 1;
        $rootValue->title = 'title';
        $rootValue->description = 'description';

        $conf = include __DIR__ . '/../conf/config.php';
        $conf['installed_apps'] = array(
            'Pluf',
            'Test'
        );
        $conf['debug'] = false;
        Bootstrap::start($conf);

        for ($i = 0; $i < 2; $i ++) {
            $gl = new \Pluf\Graphql();
            $result = $gl->render($rootValue, '{id, title, description}');
            $this->assertTrue(array_key_exists('id', $result));
            $this->assertTrue(array_key_exists('title', $result));
            $this->assertTrue(array_key_exists('description', $result));
        }
    }
}



