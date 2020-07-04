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
namespace Pluf\HTTP\Response;

use Pluf\HTTP\URL;
use Pluf;

/**
 * Can be used as a response to return when a user must be logged to
 * access a page.
 *
 * @deprecated
 */
class RedirectToLogin extends \Pluf\HTTP\Response
{

    /**
     * The $request object is used to know what the post login
     * redirect url should be.
     *
     * If the action url of the login page is not set, it will try to
     * get the url from the login view from the 'login_view'
     * configuration key.
     *
     * @param
     *            Request The request object of the current page.
     * @param
     *            string The full url of the login page (null)
     */
    function __construct($request, $loginurl = null)
    {
        if ($loginurl !== null) {
            $murl = new URL();
            $url = $murl->generate($loginurl, array(
                '_redirect_after' => $request->uri
            ), false);
            $encoded = $murl->generate($loginurl, array(
                '_redirect_after' => $request->uri
            ));
        } else {
            $url = URL::urlForView(Pluf::f('login_view', 'login_view'), array(), array(
                '_redirect_after' => $request->uri
            ), false);
            $encoded = URL::urlForView(Pluf::f('login_view', 'login_view'), array(), array(
                '_redirect_after' => $request->uri
            ));
        }
        $content = sprintf(__('<a href="%s">Please, click here to be redirected</a>.'), $encoded);
        parent::__construct($content);
        $this->headers['Location'] = $url;
        $this->status_code = 302;
    }
}
