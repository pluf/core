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
 * The request object. 
 *
 * It is given as first arguments to the view as first argument.
 */
class Pluf_HTTP_Request
{
    public $POST = array();
    public $GET = array();
    public $REQUEST = array();
    public $COOKIE = array();
    public $FILES = array();
    public $HEADERS = array();
    public $query = '';
    public $method = '';
    public $uri = '';
    public $view = '';
    public $remote_addr = '';
    public $http_host = '';
    public $SERVER = array();
    public $uid = '';
    public $time = '';
    
    /**
     * Current user
     * 
     * @var Pluf_User
     */
    public $user = null;
    
    /**
     * Current tenant
     * 
     * @var Pluf_Tenant
     */
    public $tenant = null;

    function __construct($query)
    {
        $http = new Pluf_HTTP();
        $http->removeTheMagic();
        $this->POST =& $_POST;
        $this->GET =& $_GET;
        $this->REQUEST =& $_REQUEST;
        $this->COOKIE =& $_COOKIE;
        $this->FILES =& $_FILES;
        $this->query = $query;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->remote_addr = $_SERVER['REMOTE_ADDR'];
        $this->http_host = (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '';
        $this->SERVER =& $_SERVER;
        $this->uid = $GLOBALS['_PX_uniqid']; 
        $this->time = (isset($_SERVER['REQUEST_TIME'])) ? $_SERVER['REQUEST_TIME'] : time();
        /*
         * Load request header
         */
        $this->HEADERS = array();
        if(function_exists('apache_request_headers')){
            apache_request_headers();
        }
    }
}
