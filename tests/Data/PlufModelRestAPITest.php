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
use Pluf\Dispatcher;
use Pluf\Module;
use Pluf\HTTP\Request;
use Pluf;

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
    public function getInvalidRequestWithRandomPrefix()
    {
        $res = Dispatcher::getInstance()->setViews(Module::loadControllers())->dispatch(new Request('/helloword/HelloWord'));
        $this->assertNotNull($res);
        $this->assertEquals(404, $res->getStatusCode());
    }

    /**
     *
     * @test
     */
    public function getValidRequestWithRandomPrefix()
    {
        $query = Pluf::getConfig('view_api_prefix') . '/helloword/HelloWord';

        $this->assertNotNull(Dispatcher::getInstance()->setViews(Module::loadControllers())
            ->dispatch(new Request($query)));

        $this->assertEquals(200, Dispatcher::getInstance()->setViews(Module::loadControllers())
            ->dispatch(new Request($query))
            ->getStatusCode());
    }
}

