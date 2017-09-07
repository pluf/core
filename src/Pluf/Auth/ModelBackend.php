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


/**
 * Backend to authenticate against the Pluf_User model.
 */
class Pluf_Auth_ModelBackend
{
    /**
     * Given a user id, retrieve it.
     *
     * In the case of the Pluf_User backend, the $user_id is the login.
     * 
     * @return Pluf_User
     */
    public static function getUser($user_id)
    {
        $user_model = Pluf::f('pluf_custom_user','Pluf_User');
        $sql = new Pluf_SQL('login=%s', array($user_id));
        return Pluf::factory($user_model)->getOne($sql->gen());
    }

    /**
     * Given an array with the authentication data, auth the user and return it.
     */
    public static function authenticate($auth_data)
    {
        $password = $auth_data['password'];
        $login = $auth_data['login'];
        $user = self::getUser($login);
        if (!$user) {
            return false;
        }
        if (!$user->active) {
            return false;
        }
        return ($user->checkPassword($password)) ? $user : false;
    }
}

