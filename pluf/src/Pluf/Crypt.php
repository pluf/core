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
 * Simple encryption class.
 *
 * Very simple encryption class to perform simple encryption. It can
 * be used for example when you request a valid email address to
 * register. The validation link can contain the encrypted email.
 *
 * DO NOT EVER USE IT FOR REALLY IMPORTANT DATA!!!
 *
 * Credit Anonymous on http://www.php.net/mcrypt
 */
class Pluf_Crypt
{
    public $key = '';

    /**
     * Construct the encryption object.
     *
     * @param string The encryption key ('')
     */ 
    function __construct($key='')
    {
        $this->key = $key;
    }

    /**
     * Encrypt a string with a key.
     *
     * If the key is not given, $this->key is used. If $this->key is
     * empty an exception is raised.
     *
     * @param string String to encode
     * @param string Encryption key ('')
     * @return string Encoded string
     */
    function encrypt($string, $key='')
    { 
        if ($key == '') {
            $key = $this->key;
        }
        if ($key == '') {
            throw new Exception('No encryption key provided.');
        }
        $result = ''; 
        $strlen = strlen($string);
        $keylen = strlen($key);
        for($i=0; $i<$strlen; $i++) { 
            $char = substr($string, $i, 1); 
            $keychar = substr($key, ($i % $keylen)-1, 1); 
            $char = chr(ord($char)+ord($keychar));
            $result.=$char; 
        }
        $result = base64_encode($result);
        return str_replace(array('+','/','='), array('-','_','~'), $result);
    }

    /**
     * Decrypt a string with a key.
     *
     * If the key is not given, $this->key is used. If $this->key is
     * empty an exception is raised.
     *
     * @param string String to decode
     * @param string Encryption key ('')
     * @return string Decoded string
     */
    function decrypt($string, $key='') 
    {
        if ($key == '') {
            $key = $this->key;
        }
        if ($key == '') {
            throw new Exception('No encryption key provided.');
        }
        $result = '';
        $string = str_replace(array('-','_','~'), array('+','/','='), $string);
        $string = base64_decode($string);
        $strlen = strlen($string);
        $keylen = strlen($key);
        for($i=0; $i<$strlen; $i++) { 
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % $keylen)-1, 1);
            $char = chr(ord($char)-ord($keychar));
            $result.=$char;
        }
        return $result;
    }
}
