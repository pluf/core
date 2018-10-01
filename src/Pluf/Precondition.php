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
 * Global system preconditions
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 */
class Pluf_Precondition
{

    /**
     * Requires SSL to access the view.
     *
     * It will redirect the user to the same URL but over SSL if the
     * user is not using SSL, if POST request, the data are lost, so
     * handle it with care.
     *
     * @param
     *            Pluf_HTTP_Request
     * @return mixed
     */
    static public function sslRequired ($request)
    {
        if (empty($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off') {
            return new Pluf_HTTP_Response_Redirect(
                    'https://' . $request->http_host . $request->uri);
        }
        return true;
    }
    
    /**
     * Checks if given name is matched with patterin [a-zA-Z_][0-9a-zA-Z_]*
     * 
     * Throws bad request exception if given name does not match.
     * 
     * @param string $name
     * @throws Pluf_Exception_BadRequest
     */
    public static function assertKeyIsValid($name){
        if(preg_match('/^[a-zA-Z_][0-9a-zA-Z_]*$/', $name) !== 1){
            throw new Pluf_Exception_BadRequest('Invalid parameter: <' . $name . '>');
        }
    }

}