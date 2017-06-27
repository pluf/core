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
class PlufSettingTemplateTest extends TestCase
{

    protected function setUp ()
    {
        Pluf::start(
                array(
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
                                'setting' => 'Setting_Template_Tag_Setting'
                        ),
                        'pluf_use_rowpermission' => true,
                        'mimetype' => 'text/html',
                        'app_views' => dirname(__FILE__) . '/views.php',
                        'db_login' => 'testpluf',
                        'db_password' => 'testpluf',
                        'db_server' => 'localhost',
                        'db_database' => dirname(__FILE__) .
                                 '/../tmp/tmp.sqlite.db',
                                'app_base' => '/testapp',
                                'url_format' => 'simple',
                                'db_table_prefix' => 'bank_unit_tests_',
                                'db_version' => '5.0',
                                'db_engine' => 'SQLite',
                                'bank_debug' => true
                ));
        
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $models = array(
                'Pluf_Configuration'
        );
        foreach ($models as $model) {
            $schema->model = Pluf::factory($model);
            $schema->dropTables();
            if (true !== ($res = $schema->createTables())) {
                throw new Exception($res);
            }
        }
    }

    /**
     * @afterClass
     */
    public static function removeDatabses ()
    {
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $models = array(
                'Pluf_Configuration'
        );
        foreach ($models as $model) {
            $schema->model = Pluf::factory($model);
            $schema->dropTables();
        }
    }

    public function testSetting1 ()
    {
        $folders = array(
                dirname(__FILE__)
        );
        $tmpl = new Pluf_Template('tpl-setting1.html', $folders);
        $this->assertEquals(
                Setting_Service::get('setting1', 'default value'), 
                $tmpl->render());
    }

    public function testSetting2 ()
    {
        $folders = array(
                dirname(__FILE__)
        );
        $value = 'Random val:' . rand();
        Setting_Service::set('setting2', $value);
        $tmpl = new Pluf_Template('tpl-setting2.html', $folders);
        $this->assertEquals($value, $tmpl->render());
    }
}

