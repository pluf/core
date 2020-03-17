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
use Pluf\Module;
require_once 'Pluf.php';

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../apps');

class PlufModelRestAPITest extends TestCase
{

    /**
     *
     * @beforeClass
     */
    public static function createDataBase()
    {
        $conf = include __DIR__ . '/../conf/config.php';
        $conf['view_api_prefix'] = '/api/test/prefix' . rand();
        Pluf::start($conf);
        $m = new Pluf_Migration();
        $m->install();
    }

    /**
     *
     * @afterClass
     */
    public static function removeDatabses()
    {
        $m = new Pluf_Migration();
        $m->uninstall();
    }

    /**
     *
     * @test
     */
    public function getInvalidRequestWithRandomPrefix()
    {
        $dispatcher = new Pluf_Dispatcher();
        $res = $dispatcher->dispatch('/helloword/HelloWord', Module::loadControllers());
        $this->assertNotNull($res);
        $this->assertEquals($res[1]->status_code, 404);
    }

    /**
     *
     * @test
     */
    public function getValidRequestWithRandomPrefix()
    {
        $dispatcher = new Pluf_Dispatcher();
        $res = $dispatcher->dispatch(Pluf::f('view_api_prefix') . '/helloword/HelloWord', Module::loadControllers());
        $this->assertNotNull($res);
        $this->assertEquals($res[1]->status_code, 200);
    }
}

