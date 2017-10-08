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
class User_Password_Token_MainTest extends TestCase
{

    /**
     * @beforeClass
     */
    public static function createDataBase()
    {
        Pluf::start(array(
            'general_domain' => 'localhost',
            'general_admin_email' => array(
                'root@localhost'
            ),
            'general_from_email' => 'test@localhost',
            'installed_apps' => array(),
            'middleware_classes' => array(),
            'debug' => true,
            'test_unit' => true,
            
            'languages' => array(
                'fa',
                'en'
            ),
            'tmp_folder' => dirname(__FILE__) . '/../tmp',
            'template_folders' => array(
                dirname(__FILE__) . '/../templates'
            ),
            'template_tags' => array(),
            'time_zone' => 'Asia/Tehran',
            'encoding' => 'UTF-8',
            
            'secret_key' => '5a8d7e0f2aad8bdab8f6eef725412850',
            'user_signup_active' => true,
            'user_avatra_max_size' => 2097152,
            'auth_backends' => array(
                'Pluf_Auth_ModelBackend'
            ),
            'pluf_use_rowpermission' => true,
            'db_engine' => 'MySQL',
            'db_version' => '5.5.33',
            'db_login' => 'root',
            'db_password' => '',
            'db_server' => 'localhost',
            'db_database' => 'test',
            'db_table_prefix' => '_test_user_',
            
            'mail_backend' => 'mail',
            'user_profile_class' => 'User_Profile'
        ));
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $models = array(
            'Pluf_Group',
            'Pluf_User',
            'Pluf_Permission',
            'Pluf_Message',
            'Pluf_RowPermission',
            'User_PasswordToken'
        );
        foreach ($models as $model) {
            $schema->model = Pluf::factory($model);
            $schema->dropTables();
            if (true !== ($res = $schema->createTables())) {
                throw new Exception($res);
            }
        }
        
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
    }

    /**
     * @afterClass
     */
    public static function removeDatabses()
    {
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $models = array(
            'User_PasswordToken',
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

    /**
     * @test
     */
    public function testCreateToken()
    {
        $user = new Pluf_User();
        $user = $user->getUser('test');
        
        // creates new
        $token = new User_PasswordToken();
        $token->user = $user;
        $token->create();
        $this->assertNotNull($token->id);
        
        // get tokne user
        $tokenStored = new User_PasswordToken($token->id);
        $value = $tokenStored->id;
        $this->assertEquals($value, $user->id);
        $value = $tokenStored->token;
        $this->assertNotNull($value);
    }

    public function testCreateTokenForMail()
    {
        // Create user
        $user = new Pluf_User();
        $user->login = 'test' . rand();
        $user->first_name = 'test';
        $user->last_name = 'test';
        $user->email = 'test' . rand() . '@example.com';
        $user->setPassword('test');
        $user->active = true;
        if (true !== $user->create()) {
            throw new Exception();
        }
        
        $view = new User_Views_Password();
        
        // Create token by email
        $match = array();
        global $_REQUEST;
        global $_SERVER;
        $_REQUEST = array(
            'email' => $user->email
        );
        $_SERVER['REQUEST_URI'] = 'http://localhost/test';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REMOTE_ADDR'] = 'localhost';
        $request = new Pluf_HTTP_Request('/');
        $request->user = new Pluf_User();
        
        $res = $view->password($request, $match);
        $this->assertNotNull($res);
        
        $token = new User_PasswordToken();
        $sql = new Pluf_SQL('user=%s', array(
            $user->id
        ));
        $token = $token->getOne($sql->gen());
        $this->assertNotNull($token);
    }

    public function testCreateTokenForLogin()
    {
        // Create user
        $user = new Pluf_User();
        $user->login = 'test' . rand();
        $user->first_name = 'test';
        $user->last_name = 'test';
        $user->email = 'test' . rand() . '@example.com';
        $user->setPassword('test');
        $user->active = true;
        if (true !== $user->create()) {
            throw new Exception();
        }
        
        $view = new User_Views_Password();
        
        // Create token by email
        $match = array();
        global $_REQUEST;
        global $_SERVER;
        $_REQUEST = array(
            'login' => $user->login
        );
        $_SERVER['REQUEST_URI'] = 'http://localhost/test';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REMOTE_ADDR'] = 'localhost';
        $request = new Pluf_HTTP_Request('/');
        $request->user = new Pluf_User();
        
        $res = $view->password($request, $match);
        $this->assertNotNull($res);
        
        $token = new User_PasswordToken();
        $sql = new Pluf_SQL('user=%s', array(
            $user->id
        ));
        $token = $token->getOne($sql->gen());
        $this->assertNotNull($token);
    }

    public function testCreateDoubleTokenForLogin()
    {
        // Create user
        $user = new Pluf_User();
        $user->login = 'test' . rand();
        $user->first_name = 'test';
        $user->last_name = 'test';
        $user->email = 'test' . rand() . '@example.com';
        $user->setPassword('test');
        $user->active = true;
        if (true !== $user->create()) {
            throw new Exception();
        }
        
        $view = new User_Views_Password();
        
        // Create token by email
        $match = array();
        global $_REQUEST;
        global $_SERVER;
        $_REQUEST = array(
            'login' => $user->login
        );
        $_SERVER['REQUEST_URI'] = 'http://localhost/test';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REMOTE_ADDR'] = 'localhost';
        $request = new Pluf_HTTP_Request('/');
        $request->user = new Pluf_User();
        
        for($i = 1; $i < 4; $i++){
            $res = $view->password($request, $match);
            $this->assertNotNull($res);
            
            $token = new User_PasswordToken();
            $sql = new Pluf_SQL('user=%s', array(
                $user->id
            ));
            $token = $token->getOne($sql->gen());
            $this->assertNotNull($token);
        }
    }
}


