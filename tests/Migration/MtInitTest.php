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
use Pluf\Tenant;

class MtnitTest extends TestCase
{

    /**
     *
     * @test
     */
    public function shouldInstallEmptyApp()
    {
        $conf = include __DIR__ . '/../conf/config.php';
        $conf['tenant_enable'] = true;
        Bootstrap::start($conf);
        $m = new Migration();

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
        $conf['tenant_enable'] = true;
        Bootstrap::start($conf);

        // Install
        $m = new Migration();
        $m->install();

        // Create a tenant
        $tenant = new Tenant();
        $tenant->title = 'Default Tenant';
        $tenant->description = 'Auto generated tenant';
        $tenant->subdomain = Bootstrap::f('tenant_root', 'main');
        $tenant->domain = Bootstrap::f('tenant_root_domain', 'donate.com');
        $tenant->create();

        // Initialize the tenant
        $m->init($tenant);

        // Uninstall the application
        $m->unInstall();

        $this->assertTrue(true);
    }

    /**
     *
     * @test
     */
    public function shouldInitNoteFromConfig()
    {
        $conf = include __DIR__ . '/../conf/config.php';
        $conf['tenant_enable'] = true;
        Bootstrap::start($conf);
        
        // Install
        $m = new Migration();
        $m->install();

        // Create a tenant
        $tenant = new Tenant();
        $tenant->title = 'Default Tenant';
        $tenant->description = 'Auto generated tenant';
        $tenant->subdomain = Bootstrap::f('tenant_root', 'main');
        $tenant->domain = Bootstrap::f('tenant_root_domain', 'donate.com');
        $tenant->create();

        // Initialize the tenant
        $m->init($tenant);

        $GLOBALS['_PX_request'] = $tenant;
        $note = new \Pluf\Note\Book();
        $this->assertTrue(sizeof($note->getList()) > 0, 'Notes are not created');

        $m->unInstall();
    }
}



