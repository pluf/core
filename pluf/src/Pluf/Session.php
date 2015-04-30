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

class Pluf_Session extends Pluf_Model
{
    public $_model = 'Pluf_Session';
    public $data = array();
    public $cookie_name = 'sessionid';
    public $touched = false;
    public $test_cookie_name = 'testcookie';
    public $test_cookie_value = 'worked';
    public $set_test_cookie = false;
    public $test_cookie = null;

    function _init()
    {
        $this->cookie_name = Pluf::f('session_cookie_id', 'sessionid');
        parent::_init();
    }

    function init()
    {
        $this->_a['table'] = 'sessions';
        $this->_a['model'] = 'Pluf_Session';
        $this->_a['cols'] = array(
                             // It is mandatory to have an "id" column.
                            'id' =>
                            array(
                                  'type' => 'Pluf_DB_Field_Sequence',
                                  //It is automatically added.
                                  'blank' => true, 
                                  ),
			        		'version' =>
			        		array(
			        			'type' => 'Pluf_DB_Field_Integer',
			        			'blank' => true,
			        			),
                            'session_key' =>
                            array(
                                  'type' => 'Pluf_DB_Field_Varchar',
                                  'blank' => false,
                                  'size' => 100,
                                  ),
                            'session_data' => 
                            array(
                                  'type' => 'Pluf_DB_Field_Text',
                                  'blank' => false,
                                  ),
                            'expire' => 
                            array(
                                  'type' => 'Pluf_DB_Field_Datetime',
                                  'blank' => false,
                                  ),
                            );
        $this->_a['idx'] = array(                           
                            'session_key_idx' =>
                            array(
                                  'type' => 'unique',
                                  'col' => 'session_key'
                                  ),
                            );
        $this->_admin = array();
        $this->_a['views'] = array();
    }

    /**
     * Set some data in the session object.
     *
     * @param string Key
     * @param mixed Value (null), if null, it is removing the value 
     *              from the session
     */
    function setData($key, $value=null)
    {
        if (is_null($value)) {
            unset($this->data[$key]);
        } else {
            $this->data[$key] = $value;
        }
        $this->touched = true;
    }

    function getData($key=null, $default='')
    {
        if (is_null($key)) {
            return parent::getData();
        }
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return $default;
        }
    }

    function clear()
    {
        $this->data = array();
        $this->touched = true;
    }
        
    
    /**
     * Generate a new session key.
     */
    function getNewSessionKey()
    {
        while (1) {
            $key = md5(microtime().rand(0, 123456789).rand(0, 123456789)
                       .Pluf::f('secret_key'));
            $sess = $this->getList(array('filter' => 'session_key=\''.$key.'\''));
            if (count($sess) == 0) {
                break;
            }
        }
        return $key;
    }

    /**
     * Presave/create function to encode data into session_data.
     */
    function preSave($create=false)
    {
        $this->session_data = serialize($this->data);
        if ($this->session_key == '') {
            $this->session_key = $this->getNewSessionKey();
        }
        $this->expire = gmdate('Y-m-d H:i:s', time()+31536000);
    }

    /**
     * Restore function to decode the session_data into $this->data.
     */
    function restore()
    {
        $this->data = unserialize($this->session_data);
    }

    /**
     * Create a test cookie.
     */
    public function createTestCookie()
    {
        $this->set_test_cookie = true;
    }

    public function getTestCookie()
    {
        return ($this->test_cookie == $this->test_cookie_value);
    }

    public function deleteTestCookie()
    {
        $this->set_test_cookie = true;
        $this->test_cookie_value = null;
    }
}
