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
use PHPUnit\Framework\IncompleteTestError;

require_once 'Pluf.php';

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class PlufTenantTemplateTest extends TestCase
{

    /**
     * 
     * 
     * @before
     * @return void
     */
    protected function setUpTest()
    {
        Pluf::start(array(
            'test' => false,
            'timezone' => 'Europe/Berlin',
            'debug' => true,
            'installed_apps' => array(
                'Pluf'
            ),
            'tmp_folder' => dirname(__FILE__) . '/../tmp',
            'templates_folder' => array(
                dirname(__FILE__) . '/../templates'
            ),

            'template_tags' => array(
                'tenant' => 'Pluf_Template_Tag_Tenant'
            ),
            'pluf_use_rowpermission' => true,
            'mimetype' => 'text/html',
            'app_views' => dirname(__FILE__) . '/views.php',
            'db_login' => 'testpluf',
            'db_password' => 'testpluf',
            'db_server' => 'localhost',
            'db_database' => dirname(__FILE__) . '/../tmp/tmp.sqlite.db',
            'app_base' => '/testapp',
            'url_format' => 'simple',
            'db_table_prefix' => 'pluf_unit_tests_',
            'db_version' => '5.0',
            'db_engine' => 'SQLite',
            'bank_debug' => true
        ));
    }

    public function testId()
    {
        $folders = array(
            dirname(__FILE__)
        );
        $tmpl = new Pluf_Template('tpl-id.html', $folders);
        $this->assertEquals("0", $tmpl->render());
    }

    public function testTitle()
    {
        $tenant = Pluf_Tenant::current();
        $folders = array(
            dirname(__FILE__)
        );
        $tmpl = new Pluf_Template('tpl-title.html', $folders);
        $this->assertEquals($tenant->title, $tmpl->render());
    }

    public function testDomain()
    {
        $tenant = Pluf_Tenant::current();
        $folders = array(
            dirname(__FILE__)
        );
        $tmpl = new Pluf_Template('tpl-domain.html', $folders);
        $this->assertEquals($tenant->domain, $tmpl->render());
    }
}
