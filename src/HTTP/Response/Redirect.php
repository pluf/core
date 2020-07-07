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

class Redirect extends \Pluf\HTTP\Response
{

    /**
     * Redirect response to a given URL.
     *
     * @param
     *            string URL
     * @param
     *            int Redirect code (302) or 301 for permanent
     */
    function __construct($url, $code = 302)
    {
        $content = sprintf('<a href="%s">Please, click here to be redirected</a>.', $url);
        parent::__construct($content);
        $this->headers['Location'] = $url;
        $this->status_code = $code;
    }
}
