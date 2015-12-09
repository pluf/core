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
 * Cross Site Request Forgery Middleware.
 *
 * This class provides a middleware that implements protection against
 * request forgeries from other sites. This middleware must be before
 * the Pluf_Middleware_Session middleware.
 *
 * Based on concepts from the Django CSRF middleware.
 */
class Pluf_Middleware_Csrf
{
    public static function makeToken($session_key)
    {
        return md5(Pluf::f('secret_key').$session_key);
    }

    /**
     * Process the request.
     *
     * When processing the request, if a POST request with a session,
     * we will check that the token is available and valid.
     *
     * @param Pluf_HTTP_Request The request
     * @return bool false
     */
    function process_request(&$request)
    {
        if ($request->method != 'POST') {
            return false;
        }
        $cookie_name = Pluf::f('session_cookie_id', 'sessionid');
        if (!isset($request->COOKIE[$cookie_name])) {
            // no session, nothing to do
            return false;
        }
        try {
            $data = Pluf_Middleware_Session::_decodeData($request->COOKIE[$cookie_name]);
        } catch (Exception $e) {
            // no valid session
            return false;
        }
        if (!isset($data['Pluf_Session_key'])) {
            // no session key
            return false;
        }
        $token = self::makeToken($data['Pluf_Session_key']);
        if (!isset($request->POST['csrfmiddlewaretoken'])) {
            return new Pluf_HTTP_Response_Forbidden($request);
        }
        if ($request->POST['csrfmiddlewaretoken'] != $token) {
            return new Pluf_HTTP_Response_Forbidden($request);
        }
        return false;
    }

    /**
     * Process the response of a view.
     *
     * If we find a POST form, add the token to it.
     *
     * @param Pluf_HTTP_Request The request
     * @param Pluf_HTTP_Response The response
     * @return Pluf_HTTP_Response The response
     */
    function process_response($request, $response)
    {
        $cookie_name = Pluf::f('session_cookie_id', 'sessionid');
        if (!isset($request->COOKIE[$cookie_name])) {
            // no session, nothing to do
            return $response;
        }
        if (!isset($response->headers['Content-Type'])) {
            return $response;
        }
        try {
            $data = Pluf_Middleware_Session::_decodeData($request->COOKIE[$cookie_name]);
        } catch (Exception $e) {
            // no valid session
            return $response;
        }
        if (!isset($data['Pluf_Session_key'])) {
            // no session key
            return $response;
        }
        $ok = false;
        $cts = array('text/html', 'application/xhtml+xml');
        foreach ($cts as $ct) {
            if (false !== strripos($response->headers['Content-Type'], $ct)) {
                $ok = true;
                break;
            }
        }
        if (!$ok) {
            return $response;
        }
        $token = self::makeToken($data['Pluf_Session_key']);
        $extra = '<div style="display:none;"><input type="hidden" name="csrfmiddlewaretoken" value="'.$token.'" /></div>';
        $response->content = preg_replace('/(<form\W[^>]*\bmethod=(\'|"|)POST(\'|"|)\b[^>]*>)/i', '$1'.$extra, $response->content);
        return $response;
    }
}