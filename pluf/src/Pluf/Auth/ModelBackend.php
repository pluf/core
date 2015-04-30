<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
#
# Plume Framework is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume Framework is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

/**
 * Backend to authenticate against the Pluf_User model.
 */
class Pluf_Auth_ModelBackend
{
    /**
     * Given a user id, retrieve it.
     *
     * In the case of the Pluf_User backend, the $user_id is the login.
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

