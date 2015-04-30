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
 * Session middleware.
 *
 * Allow a session object in the request and the automatic
 * login/logout of a user if a standard authentication against the
 * Pluf_User model is performed.
 */
class Pluf_Middleware_Session
{

    /**
     * Process the request.
     *
     * When processing the request, if a session is found with
     * Pluf_User creditentials the corresponding user is loaded into
     * $request->user.
     *
     * FIXME: We should logout everybody when the session table is emptied.
     *
     * @param Pluf_HTTP_Request The request
     * @return bool false
     */
    function process_request(&$request)
    {
        $session = new Pluf_Session();
        $user_model = Pluf::f('pluf_custom_user','Pluf_User');
        $user = new $user_model();
        if (!isset($request->COOKIE[$session->cookie_name])) {
            // No session is defined. We set an empty user and empty
            // session.
            $request->user = $user;
            $request->session = $session;
            if (isset($request->COOKIE[$request->session->test_cookie_name])) {
                $request->session->test_cookie = $request->COOKIE[$request->session->test_cookie_name];
            }
            return false;
        }
        try {
            $data = self::_decodeData($request->COOKIE[$session->cookie_name]);
        } catch (Exception $e) {
            $request->user = $user;
            $request->session = $session;
            if (isset($request->COOKIE[$request->session->test_cookie_name])) {
                $request->session->test_cookie = $request->COOKIE[$request->session->test_cookie_name];
            }
            return false;
        }
        $set_lang = false;
        if (isset($data[$user->session_key])) {
            // We can get the corresponding user
            $found_user = new $user_model($data[$user->session_key]);
            if ($found_user->id == $data[$user->session_key]) {
                // User found!
                $request->user = $found_user;
                // If the last login is from 12h or more, set it to
                // now.
                Pluf::loadFunction('Pluf_Date_Compare');
                if (43200 < Pluf_Date_Compare($request->user->last_login)) {
                    $request->user->last_login = gmdate('Y-m-d H:i:s');
                    $request->user->update();
                }
                $set_lang = $found_user->language;
            } else {
                $request->user = $user;
            }
        } else {
            $request->user = $user;
        }
        if (isset($data['Pluf_Session_key'])) {
            $sql = new Pluf_SQL('session_key=%s' ,$data['Pluf_Session_key']);
            $found_session = Pluf::factory('Pluf_Session')->getList(array('filter' => $sql->gen()));
            if (isset($found_session[0])) {
                $request->session = $found_session[0];
            } else {
                $request->session = $session;
            }
        } else {
            $request->session = $session;
        }
        if ($set_lang and false == $request->session->getData('pluf_language', false)) {
            $request->session->setData('pluf_language', $set_lang);
        }
        if (isset($request->COOKIE[$request->session->test_cookie_name])) {
            $request->session->test_cookie = $request->COOKIE[$request->session->test_cookie_name];
        }
        return false;
    }

    /**
     * Process the response of a view.
     *
     * If the session has been modified save it into the database.
     * Add the session cookie to the response.
     *
     * @param Pluf_HTTP_Request The request
     * @param Pluf_HTTP_Response The response
     * @return Pluf_HTTP_Response The response
     */
    function process_response($request, $response)
    {
        if ($request->session->touched) {
            if ($request->session->id > 0) {
                $request->session->update();
            } else {
                $request->session->create();
            }
            $data = array();
            if ($request->user->id > 0) {
                $data[$request->user->session_key] = $request->user->id;
            }
            $data['Pluf_Session_key'] = $request->session->session_key;
            $response->cookies[$request->session->cookie_name] = self::_encodeData($data);
        }
        if ($request->session->set_test_cookie != false) {
            $response->cookies[$request->session->test_cookie_name] = $request->session->test_cookie_value;
        }
        return $response;
    }
    
    /**
     * Encode the cookie data and create a check with the secret key.
     *
     * @param mixed Data to encode
     * @return string Encoded data ready for the cookie
     */
    public static function _encodeData($data)
    {
        if ('' == ($key = Pluf::f('secret_key'))) {
            throw new Exception('Security error: "secret_key" is not set in the configuration file.');
        }
        $data = serialize($data);
        return base64_encode($data).md5(base64_encode($data).$key);
    }

    /**
     * Decode the data and check that the data have not been tampered.
     *
     * If the data have been tampered an exception is raised.
     *
     * @param string Encoded data
     * @return mixed Decoded data
     */
    public static function _decodeData($encoded_data)
    {
        $check = substr($encoded_data, -32);
        $base64_data = substr($encoded_data, 0, strlen($encoded_data)-32);
        if (md5($base64_data.Pluf::f('secret_key')) != $check) {
            throw new Exception('The session data may have been tampered.');
        }
        return unserialize(base64_decode($base64_data));
    }

    public static function processContext($signal, &$params)
    {
        $params['context'] = array_merge($params['context'],
                                         Pluf_Middleware_Session_ContextPreProcessor($params['request']));
    }
}

/**
 * Context preprocessor.
 *
 * Set the $user key.
 *
 * @param Pluf_HTTP_Request Request object
 * @return array Array to merge with the context
 */
function Pluf_Middleware_Session_ContextPreProcessor($request)
{
    return array('user' => $request->user);
}

Pluf_Signal::connect('Pluf_Template_Context_Request::construct',
                     array('Pluf_Middleware_Session', 'processContext'));