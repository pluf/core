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
require_once 'Pluf.php';

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../apps');

/**
 * Single tenant test
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Pluf_Migration_MtnitTest extends TestCase
{

    /**
     *
     * @test
     */
    public function shouldInstallEmptyApp()
    {
        $conf = include __DIR__ . '/../conf/config.php';
        $conf['multitenant'] = true;
        Pluf::start($conf);
        $m = new Pluf_Migration(array(
            'Pluf',
            'Empty'
        ));

        $this->assertTrue($m->install());
        $this->assertTrue($m->unInstall());
    }

    /**
     *
     * @test
     */
    public function shouldInitEmptyFromConfig()
    {
        $conf = include __DIR__ . '/../conf/config.php';
        $conf['installed_apps'] = array(
            'Empty'
        );
        Pluf::start($conf);
        $m = new Pluf_Migration(array(
            'Pluf',
            'Empty'
        ));
        $this->assertTrue($m->install());

        $tenant = new Pluf_Tenant();
        $tenant->title = 'Default Tenant';
        $tenant->description = 'Auto generated tenant';
        $tenant->subdomain = Pluf::f('tenant_default', 'main');
        $tenant->domain = Pluf::f('general_domain', 'donate.com');
        $tenant->create();
        $this->assertTrue($m->init($tenant));

        $this->assertTrue($m->uninstall());
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
        $conf['db_table_prefix'] = 'pluf_unit_tests_' . rand() . '_';
        Pluf::start($conf);
        $m = new Pluf_Migration(array(
            'Pluf',
            'Note'
        ));
        $this->assertTrue($m->install());
        $this->assertTrue($m->uninstall());
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
        $conf['db_table_prefix'] = 'pluf_unit_tests_' . rand() . '_';
        Pluf::start($conf);
        $m = new Pluf_Migration(array(
            'Pluf',
            'Note'
        ));
        $this->assertTrue($m->install());

        $tenant = new Pluf_Tenant();
        $tenant->title = 'Default Tenant';
        $tenant->description = 'Auto generated tenant';
        $tenant->subdomain = Pluf::f('tenant_default', 'main');
        $tenant->domain = Pluf::f('general_domain', 'donate.com');
        $tenant->create();
        $this->assertTrue($m->init($tenant));

        // 1- Switch Tenant to the new one
        Pluf_Tenant::setCurrent($tenant);

        // 2- Create new instance of book
        $note = new Note_Book();
        $this->assertTrue(sizeof($note->getList()) > 0, 'Notes are not created');

        $this->assertTrue($m->unInstall());
    }
}



