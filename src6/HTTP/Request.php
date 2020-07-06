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
namespace Pluf\HTTP;

use Pluf\DiContainerTrait;
use Pluf\Pluf\Tenant;
use ArrayAccess;
use Iterator;
use Serializable;

/*
 * The request object.
 *
 * It is given as first arguments to the view as first argument.
 * 
 * @author maso
 *
 */
class Request implements ArrayAccess, Iterator, Serializable
{

    use DiContainerTrait;

    static ?Request $current = null;

    public $POST = array();

    public $GET = array();

    public $PUT = array();

    public $REQUEST = array();

    public $COOKIE = array();

    public $FILES = array();

    public $HEADERS = array();

    public $query = '';

    public $query_string = '';

    public $method = '';

    public $uri = '';

    public $view = '';

    public $remote_addr = '';

    public $http_host = '';

    public $SERVER = array();

    public $uid = '';

    public $time = '';

    public $agent = '';

    /**
     * Protocol
     *
     * @see $_SERVER['HTTP_HOST']
     * @var boolean
     */
    public $https = false;

    /*
     * TODO: maso, 2020: put the following items into the context
     */
    public $user = null;

    public $match = [];

    public $params = [];

    public ?Tenant $tenant = null;

    function __construct($query)
    {
        $http = new \Pluf\HTTP();
        $http->removeTheMagic();

        $this->POST = &$_POST;
        $this->GET = &$_GET;
        $this->REQUEST = &$_REQUEST;
        $this->COOKIE = &$_COOKIE;
        $this->FILES = &$_FILES;
        $this->query = $query;
        $this->query_string = (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : '';
        $this->method = (isset($_SERVER['REQUEST_METHOD'])) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        $this->uri = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '/';
        $this->path_info = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : '/';
        $this->remote_addr = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : 'localhost';
        $this->http_host = (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '';
        $this->SERVER = &$_SERVER;
        $this->uid = uniqid(microtime(true), true);
        // request time
        $this->time = (isset($_SERVER['REQUEST_TIME'])) ? $_SERVER['REQUEST_TIME'] : time();
        // XXX: maso, 2019: check the documents
        $this->microtime = (isset($_SERVER['REQUEST_TIME_FLOAT'])) ? $_SERVER['REQUEST_TIME_FLOAT'] : microtime(true);

        $this->https = isset($_SERVER['HTTPS']);
        $this->agent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';

        /*
         * Load PUT parameters and merge with POST
         */
        if ($this->method == 'PUT') {
            $put_vars = array();
            parse_str(file_get_contents("php://input"), $put_vars);
            $this->PUT = $put_vars;
            $this->POST = array_merge($this->POST, $put_vars);
            $this->REQUEST = array_merge($this->REQUEST, $put_vars);
        }
        /*
         * Load request header
         */
        $this->HEADERS = array();
        if (function_exists('apache_request_headers')) {
            $this->HEADERS = apache_request_headers();
        }
    }

    /**
     * Gets time of the request
     *
     * NOTE: returns the current time in the number of seconds since the Unix Epoch (January 1 1970 00:00:00 GMT).
     *
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * The timestamp of the start of the request, with microsecond precision.
     *
     * @return float
     */
    public function getMicrotime()
    {
        return $this->microtime;
    }

    /**
     * Calculates the size of the request
     *
     * @return int size of the request
     */
    public function getSize()
    {
        $size = 0;
        // TODO: maso, 2019: file size
        // Note: hadi, 2019: base on this: https://www.geeksforgeeks.org/php-_files-array-http-file-upload-variables/
        foreach ($this->FILES as $file) {
            $size += array_key_exists('size', $file) ? $file['size'] : 0;
        }
        // Parameter size
        $size += strlen(serialize($this->REQUEST));
        // Header size
        $size += strlen(serialize($this->HEADERS));
        return $size;
    }

    public function setTenant(?Tenant $tenant = null): void
    {
        $this->tenant = $tenant;
    }

    public function getTenant(): ?Tenant
    {
        if (isset($this->tenant)) {
            return $this->tenant;
        }
        return Tenant::getCurrent();
    }

    public function isGet(): bool
    {
        return $this->method == 'GET';
    }

    public function isPost(): bool
    {
        return $this->method == 'POST';
    }

    public function isPut(): bool
    {
        return $this->method == 'PUT';
    }

    public function isDelete(): bool
    {
        return $this->method == 'DELETE';
    }

    public static function getCurrent(): ?Request
    {
        return self::$current;
    }

    public static function setCurrent(?Request $request = null): void
    {
        // Legacy model,
        $GLOBALS['_PX_request'] = $request;
        self::$current = $request;
    }

    // -------------------------------------------------------------------------
    // Context Manager
    // -------------------------------------------------------------------------
    private array $context = [];

    private $position = 0;

    function __get($key)
    {
        // get local value
        if (isset($this->context[$key])) {
            return $this->context[$key];
        }
        return null;
    }

    function __set($key, $value)
    {
        // set local value
        $this->context[$key] = $value;
    }

    public function next()
    {
        ++ $this->position;
    }

    public function valid()
    {
        return isset($this->context[$this->position]);
    }

    public function offsetGet($offset)
    {
        return isset($this->context[$offset]) ? $this->context[$offset] : null;
    }

    public function serialize()
    {
        return serialize($this->context);
    }

    public function current()
    {
        return $this->context[$this->position];
    }

    public function unserialize($serialized)
    {
        $this->context = unserialize($serialized);
    }

    public function offsetExists($offset)
    {
        unset($this->context[$offset]);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function offsetUnset($offset)
    {
        unset($this->context[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        return isset($this->context[$offset]) ? $this->context[$offset] : null;
    }

    public function key()
    {
        return $this->position;
    }
}
