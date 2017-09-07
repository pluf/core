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
 * Backend to authenticate against a LDAP server.
 *
 * Configuration is done with the 'auth_ldap_*' keys.
 */
class Pluf_Auth_LdapBackend
{
    /**
     * Given a user id, retrieve it.
     *
     * Here we get the against the model database.
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

        // Small security check against the login
        if (preg_match('/[^A-Za-z0-9\-\_]/', $login)) {
            return false;
        }
        
        // We check the user against the LDAP server, if it works we
        // are happy, if not return false.


    	$ldap_dn = Pluf::f('auth_ldap_dn', 'ou=users,dc=example,dc=com');
        $ldap_user = Pluf::f('auth_ldap_user', null);
        $ldap_password = Pluf::f('auth_ldap_password', null);
        $ldap_version = Pluf::f('auth_ldap_version', 3);
        $ldap_user_key = Pluf::f('auth_ldap_user_key', 'uid');
        // If auth_ldap_password_key, it will use crypt hash control
        // to test the login password, else it will bind.
        $ldap_password_key = Pluf::f('auth_ldap_password_key', null);
        $ldap_surname_key = Pluf::f('auth_ldap_surname_key', 'sn');
        $ldap_givenname_key = Pluf::f('auth_ldap_givenname_key', 'cn');
        $ldap_email_key = Pluf::f('auth_ldap_email_key', 'mail');


		$ldap = ldap_connect(Pluf::f('auth_ldap_host', 'localhost'));
		ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 
                               Pluf::f('auth_ldap_version', 3));
		if (!ldap_bind($ldap, $ldap_user, $ldap_password)) {
            Pluf_Log::warn(sprintf('Cannot bind to the ldap server, user:%s, password:***', $ldap_user));
            ldap_close($ldap);
            return false;
        }
        // Go for a search
        $search = ldap_search($ldap, $ldap_dn, 
                              '('.$ldap_user_key.'='.$login.')', 
                              array($ldap_user_key, $ldap_surname_key, 
                                    $ldap_givenname_key, $ldap_email_key));
        $n = ldap_get_entries($ldap, $search);
        if ($n['count'] != 1) {
            ldap_close($ldap);
            return false;
        }
        $entry = ldap_first_entry($ldap, $search);
        // We get all the data first, the bind or hash control is done
        // later. If we control with bind now, we need to search again
        // to have an $entry resource to get the values.
        list($family_name,) = @ldap_get_values($ldap, $entry, $ldap_surname_key);
        list($first_name,) = @ldap_get_values($ldap, $entry, $ldap_givenname_key);
        list($email,) = @ldap_get_values($ldap, $entry, $ldap_email_key);
        $user_dn = ldap_get_dn($ldap, $entry);

        
        if ($ldap_password_key) {
            // Password authentication.
            list($ldap_hash,) = ldap_get_values($ldap, $entry, $ldap_password_key);
            $ldap_hash = substr($ldap_hash, 7);
            $salt = substr($ldap_hash, 0, 12);
            $hash = crypt($password, $salt);
            if ($ldap_hash != $hash) {
                ldap_close($ldap);
                return false;
            }
        } else {
            // Bind authentication
            if (!@ldap_bind($ldap, $user_dn, $password)) {
                ldap_close($ldap);
                return false;
            }
        }                
        // We get the user values as the 
        // Now we get the user and we create it if not available
        $user = self::getUser($login);
        if ($user) {
            ldap_close($ldap);
            return $user;
        }
        // Need to create it
        ldap_close($ldap);
        $user_model = Pluf::f('pluf_custom_user','Pluf_User');
        $user = new $user_model();
        $user->active = true;
        $user->login = $login;
        $user->password = $password;
        $user->last_name = $family_name;
        $user->first_name = $first_name;
        $user->email = $email;
        $user->create();
        return $user;
    }
}

