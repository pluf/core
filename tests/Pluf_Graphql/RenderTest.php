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


set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../apps');

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Pluf_Graphql_RenderTest extends TestCase
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
        Pluf::start($conf);
        $m = new Pluf_Migration($conf['installed_apps']);
        $m->install();
    }

    /**
     *
     * @afterClass
     */
    public static function removeDatabses1()
    {
        $m = new Pluf_Migration(array(
            'Pluf',
            'Test'
        ));
        $m->uninstall();
    }

    /**
     *
     * @test
     */
    public function testRenderAndRun()
    {
        $rootValue = new Test_Model();
        $rootValue->id = 1;
        $rootValue->title = 'title';
        $rootValue->description = 'description';

        $gl = new Pluf_Graphql();
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
        $rootValue = new Test_Model();
        $rootValue->id = 1;
        $rootValue->title = 'title';
        $rootValue->description = 'description';

        $conf = include __DIR__ . '/../conf/config.php';
        $conf['installed_apps'] = array(
            'Pluf',
            'Test'
        );
        $conf['debug'] = false;
        Pluf::start($conf);

        for ($i = 0; $i < 2; $i ++) {
            $gl = new Pluf_Graphql();
            $result = $gl->render($rootValue, '{id, title, description}');
            $this->assertTrue(array_key_exists('id', $result));
            $this->assertTrue(array_key_exists('title', $result));
            $this->assertTrue(array_key_exists('description', $result));
        }
    }
}



