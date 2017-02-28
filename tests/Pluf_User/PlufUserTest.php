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

class PlufUserTest extends TestCase
{

    /**
     * @beforeClass
     */
    public static function createDataBase ()
    {
        Pluf::start(dirname(__FILE__) . '/../conf/pluf.config.php');
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $models = array(
                'Pluf_Group',
                'Pluf_User',
                'Pluf_Permission',
                'Pluf_Message',
                'Pluf_RowPermission'
        );
        foreach ($models as $model) {
            $schema->model = Pluf::factory($model);
            $schema->dropTables();
            if (true !== ($res = $schema->createTables())) {
                throw new Exception($res);
            }
        }
        $perms = array();
        for ($i = 1; $i <= 10; $i ++) {
            $perm = new Pluf_Permission();
            $perm->application = 'DummyModel';
            $perm->code_name = 'code-' . $i;
            $perm->name = 'code-' . $i;
            $perm->description = 'code-' . $i;
            $perm->create();
            $perms[] = clone ($perm);
        }
        $groups = array();
        for ($i = 1; $i <= 10; $i ++) {
            $group = new Pluf_Group();
            $group->name = 'Group ' . $i;
            $group->description = 'Group ' . $i;
            $group->create();
            $groups[] = clone ($group);
        }
        $groups[0]->setAssoc($perms[0]);
        $groups[0]->setAssoc($perms[1]);
        $groups[0]->setAssoc($perms[2]);
        $groups[0]->setAssoc($perms[3]);
        $groups[1]->setAssoc($perms[0]); // again perm "1"
        $groups[0]->setAssoc($perms[4]);
        $groups[0]->setAssoc($perms[5]);
        $user = new Pluf_User();
        $user->login = 'test';
        $user->first_name = 'test';
        $user->last_name = 'test';
        $user->email = 'toto@example.com';
        $user->setPassword('test');
        $user->active = true;
        if (true !== $user->create()) {
            throw new Exception();
        }
        $user->setAssoc($groups[0]);
        $user->setAssoc($groups[1]);
        $user->setAssoc($perms[7]);
        $user->setAssoc($perms[8]);
    }

    /**
     * @afterClass
     */
    public static function removeDatabses ()
    {
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $models = array(
                'Pluf_Group',
                'Pluf_User',
                'Pluf_Permission',
                'Pluf_RowPermission',
                'Pluf_Message'
        );
        foreach ($models as $model) {
            $schema->model = Pluf::factory($model);
            $schema->dropTables();
        }
    }

//     /**
//      * @test
//      */
//     public function testGetMessages ()
//     {
//         $user = new Pluf_User(1);
//         $mess = $user->get_pluf_message_list();
//         $this->assertEquals(0, $mess->count());
//     }

    /**
     * @test
     */
    public function testUniqueLogin ()
    {
        $user = new Pluf_User();
        $user->login = 'test';
        $user->first_name = 'test';
        $user->last_name = 'test';
        $user->email = 'toto@example.com';
        $user->setPassword('test');
        $user->active = true;
        // Test user already exists
        try {
            $user->create();
        } catch (Exception $e) {
            return;
        }
        $this->fail();
    }

    /**
     * @test
     */
    public function testValidationUnique ()
    {
        $this->markTestSkipped('Need to rewrite the form handling first.');
        // Test user already exists
        $user = new Pluf_User();
        $user->login = 'test';
        $user->first_name = 'test';
        $user->last_name = 'test';
        $user->email = 'toto@example.com';
        $user->setPassword('test');
        $user->active = true;
        $form = new Pluf_Form_Create();
        $form->defineFromModel($user);
        $new_data = $user->getData();
        $errors = $form->getValidationErrors($new_data);
        $this->assertEquals(1, count($errors));
    }

    /**
     * @test
     */
    public function testGetPermissions ()
    {
        $user = new Pluf_User(1);
        $a = $user->getAllPermissions();
        $this->assertEquals(8, count($a));
    }

    /**
     * @test
     */
    public function testHasPermission ()
    {
        $user = new Pluf_User(1);
        $this->assertEquals(true, $user->hasPerm('DummyModel.code-5'));
        $this->assertEquals(false, $user->hasPerm('DummyModel.code-7'));
        $user->administrator = true;
        $this->assertEquals(true, $user->hasPerm('DummyModel.code-7'));
        $user->active = false;
        $this->assertEquals(false, $user->hasPerm('DummyModel.code-5'));
    }

    /**
     * @test
     */
    public function testHasAppPermissions ()
    {
        $user = new Pluf_User(1);
        $this->assertEquals(true, $user->hasAppPerms('DummyModel'));
        $this->assertEquals(false, $user->hasPerm('DummyModel2'));
        $user->administrator = true;
        $this->assertEquals(true, $user->hasPerm('DummyModel2'));
    }

    /**
     * @test
     */
    public function testRowPermission ()
    {
        $user = new Pluf_User(1);
        $group = new Pluf_Group();
        $group->name = 'testRowPermission';
        $group->description = 'testRowPermission';
        $group->create();
        for ($i = 1; $i <= 5; $i ++) {
            $mess = new Pluf_Message();
            $mess->user = $user;
            $mess->message = 'Dummy object to test against: ' . $i;
            $mess->create();
        }
        $perm = new Pluf_Permission();
        $perm->application = 'Pluf_RowPermission';
        $perm->code_name = 'test1';
        $perm->name = 'test1';
        $perm->description = 'test1';
        $perm->create();
        // Permission through group
        $mess = new Pluf_Message(1);
        Pluf_RowPermission::add($group, $mess, $perm);
        $this->assertEquals(false, 
                $user->hasPerm('Pluf_RowPermission.test1', $mess));
        $user->setAssoc($group);
        $user->getAllPermissions(true); // reset the cache
        $this->assertEquals(true, 
                $user->hasPerm('Pluf_RowPermission.test1', $mess));
        $user->delAssoc($group);
        $user->getAllPermissions(true); // reset the cache
        $this->assertEquals(false, 
                $user->hasPerm('Pluf_RowPermission.test1', $mess));
        $user->setAssoc($group);
        $user->getAllPermissions(true); // reset the cache
        $this->assertEquals(true, 
                $user->hasPerm('Pluf_RowPermission.test1', $mess));
        Pluf_RowPermission::remove($group, $mess, $perm);
        $user->getAllPermissions(true); // reset the cache
        $this->assertEquals(false, 
                $user->hasPerm('Pluf_RowPermission.test1', $mess));
        // Permission through direct user
        Pluf_RowPermission::add($user, $mess, $perm);
        $user->getAllPermissions(true); // reset the cache
        $this->assertEquals(true, 
                $user->hasPerm('Pluf_RowPermission.test1', $mess));
        Pluf_RowPermission::remove($user, $mess, $perm);
        $user->getAllPermissions(true); // reset the cache
        $this->assertEquals(false, 
                $user->hasPerm('Pluf_RowPermission.test1', $mess));
        // Using string for the permission.
        Pluf_RowPermission::add($user, $mess, 'Pluf_RowPermission.test1');
        $user->getAllPermissions(true); // reset the cache
        $this->assertEquals(true, 
                $user->hasPerm('Pluf_RowPermission.test1', $mess));
        Pluf_RowPermission::remove($user, $mess, 'Pluf_RowPermission.test1');
        $user->getAllPermissions(true); // reset the cache
        $this->assertEquals(false, 
                $user->hasPerm('Pluf_RowPermission.test1', $mess));
    }
}

?>