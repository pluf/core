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
namespace Pluf\PlufTest\Migration;

use PHPUnit\Framework\TestCase;
use Pluf\Bootstrap;
use Pluf\Migration;

class InitTest extends TestCase
{

    /**
     *
     * @beforeClass
     */
    public static function createDataBase()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REMOTE_ADDR'] = '/';

        $GLOBALS['_PX_uniqid'] = '1234';
    }

    /**
     *
     * @test
     */
    public function shouldInstallEmptyApp()
    {
        $conf = include __DIR__ . '/../conf/config.php';
        $conf['installed_apps'] = array(
            'Smallest'
        );
        Bootstrap::start($conf);
        $m = new Migration(array(
            'Smallest'
        ));

        // insta and uninstall apps
        $m->install();
        $m->unInstall();
        $this->assertTrue(true);
    }

    /**
     *
     * @test
     */
    public function shouldInitEmptyFromConfig()
    {
        $conf = include __DIR__ . '/../conf/config.php';
        $conf['installed_apps'] = array(
            'Smallest'
        );
        Bootstrap::start($conf);
        $m = new Migration(array(
            'Smallest'
        ));
        $m->install();
        $m->init();
        $m->unInstall();
        $this->assertTrue(true);
    }

    /**
     *
     * @test
     */
    public function shouldInstallNoteApp()
    {
        $conf = include __DIR__ . '/../conf/config.php';
        $conf['installed_apps'] = array(
            'Note'
        );
        Bootstrap::start($conf);
        $m = new Migration(array(
            'Note'
        ));
        $m->install();
        $m->unInstall();
    }

    /**
     *
     * @test
     */
    public function shouldInitNoteFromConfig()
    {
        $conf = include __DIR__ . '/../conf/config.php';
        $conf['installed_apps'] = array(
            'Note'
        );
        Bootstrap::start($conf);
        $m = new Migration(array(
            'Note'
        ));
        $m->install();
        $m->init();

        $note = new \Pluf\Note\Book();
        $this->assertTrue(sizeof($note->getList()) > 0, 'Notes are not created');

        $m->unInstall();
    }
}



