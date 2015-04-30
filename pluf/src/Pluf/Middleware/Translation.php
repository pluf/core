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
 * Translation middleware.
 *
 * Load the translation of the website based on the useragent.
 */
class Pluf_Middleware_Translation
{

    /**
     * Process the request.
     *
     * @param Pluf_HTTP_Request The request
     * @return bool false
     */
    function process_request(&$request)
    {
        // Find which language to use. By priority:
        // A session value with 'pluf_language'
        // A cookie with 'pluf_language'
        // The browser information.
        $lang = false;
        if (!empty($request->session)) {
            $lang = $request->session->getData('pluf_language', false);
            if ($lang and !in_array($lang, Pluf::f('languages', array('en')))) {
                $lang = false;
            }
        }
        if ($lang === false and !empty($request->COOKIE[Pluf::f('lang_cookie', 'pluf_language')])) {
            $lang = $request->COOKIE[Pluf::f('lang_cookie', 'pluf_language')];
            if ($lang and !in_array($lang, Pluf::f('languages', array('en')))) {
                $lang = false;
            }
        }
        if ($lang === false) {
            // will default to 'en'
            $lang = Pluf_Translation::getAcceptedLanguage(Pluf::f('languages', array('en')));
        }
        Pluf_Translation::loadSetLocale($lang);
        $request->language_code = $lang;
        return false;
    }

    /**
     * Process the response of a view.
     *
     * @param Pluf_HTTP_Request The request
     * @param Pluf_HTTP_Response The response
     * @return Pluf_HTTP_Response The response
     */
    function process_response($request, $response)
    {
        $vary_h = array();
        if (!empty($response->headers['Vary'])) {
            $vary_h = preg_split('/\s*,\s*/', $response->headers['Vary'],
                                 -1, PREG_SPLIT_NO_EMPTY);
        }
        if (!in_array('accept-language', $vary_h)) {
            $vary_h[] = 'accept-language';
        }
        $response->headers['Vary'] = implode(', ', $vary_h);
        $response->headers['Content-Language'] = $request->language_code;
        return $response;
    }
}