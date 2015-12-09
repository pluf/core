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
 * Emulates a client to call your views during unit testing.
 * 
 * Usage:
 * <code>
 * $client = new Pluf_Test_Client('./path/to/app-views.php');
 * $response = $client->get('/the/page/', array('var'=>'toto'));
 * $response is now the Pluf_HTTP_Response
 * </code>
 *
 */
class Pluf_Test_Client
{
    public $views = '';
    public $dispatcher = '';
    public $cookies = array();

    public function __construct($views)
    {
        $this->views = $views;
        $this->dispatcher = new Pluf_Dispatcher();
        $this->dispatcher->loadControllers($this->views);
        $this->clean(false);
    }

    protected function clean($keepcookies=true)
    {
        $_REQUEST = array();
        if (!$keepcookies) {
            $_COOKIE = array();
            $this->cookies = array();
        }
        $_SERVER = array();
        $_GET = array();
        $_POST = array();
        $_FILES = array();
        $_SERVER['REQUEST_METHOD'] = '';
        $_SERVER['REQUEST_URI'] = '';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_HOST'] = 'localhost';
    }

    protected function dispatch($page)
    {
        $GLOBALS['_PX_tests_templates'] = array();
        $_SERVER['REQUEST_URI'] = $page;
        foreach ($this->cookies as $cookie => $data) {
            $_COOKIE[$cookie] = $data;
        }
        ob_implicit_flush(False);
        list($request, $response) = $this->dispatcher->dispatch($page);
        ob_start();
        $response->render();
        $content = ob_get_contents(); 
        ob_end_clean();
        $response->content = $content;
        $response->request = $request;
        if (isset($GLOBALS['_PX_tests_templates'])) {
            if (count($GLOBALS['_PX_tests_templates']) == 1) {
                $response->template = $GLOBALS['_PX_tests_templates'][0];
            } else {
                $response->template = $GLOBALS['_PX_tests_templates'];
            }
        }
        foreach ($response->cookies as $cookie => $data) {
            $_COOKIE[$cookie] = $data;
            $this->cookies[$cookie] = $data;
        }
        return $response;
    }

    public function get($page, $params=array()) 
    {
        $this->clean();
        $_GET = $params;
        $_REQUEST = $params;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $response = $this->dispatch($page);
        $code = $response->status_code;
        if ($code == 302) {
            list($page, $params) = $this->parseRedirect($response->headers['Location']);
            $response = $this->get($page, $params);
        }
        return $response;
    }


    public function post($page, $params=array(), $files=array()) 
    {
        $this->clean();
        $_POST = $params;
        $_REQUEST = $params;
        $_FILES = $files; //FIXME need to match the correct array structure
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $response = $this->dispatch($page);
        if ($response->status_code == 302) {
            list($page, $params) = $this->parseRedirect($response->headers['Location']);
            return $this->get($page, $params);
        }
        return $response;
    }

    public function parseRedirect($location)
    {
        $page = parse_url($location, PHP_URL_PATH);
        $query = parse_url($location, PHP_URL_QUERY);
        $params = array();
        if (strlen($query)) {
            parse_str($query, $params);
        }
        return array($page, $params);
    }
}

