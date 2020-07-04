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
namespace Pluf;

/**
 * A simple call used in cryptography
 *
 * Credit Anonymous on http://www.php.net/mcrypt
 */
class Crypt
{

    public $key = '';

    /**
     * Construct the encryption object.
     *
     * @param
     *            string The encryption key ('')
     */
    function __construct($key = '')
    {
        $this->key = $key;
    }

    /**
     * Encrypt a string with a key.
     *
     * If the key is not given, $this->key is used. If $this->key is
     * empty an exception is raised.
     *
     * @param
     *            string String to encode
     * @param
     *            string Encryption key ('')
     * @return string Encoded string
     */
    function encrypt($string, $key = '')
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
        for ($i = 0; $i < $strlen; $i ++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % $keylen) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }
        $result = base64_encode($result);
        return str_replace(array(
            '+',
            '/',
            '='
        ), array(
            '-',
            '_',
            '~'
        ), $result);
    }

    /**
     * Decrypt a string with a key.
     *
     * If the key is not given, $this->key is used. If $this->key is
     * empty an exception is raised.
     *
     * @param
     *            string String to decode
     * @param
     *            string Encryption key ('')
     * @return string Decoded string
     */
    function decrypt($string, $key = '')
    {
        if ($key == '') {
            $key = $this->key;
        }
        if ($key == '') {
            throw new Exception('No encryption key provided.');
        }
        $result = '';
        $string = str_replace(array(
            '-',
            '_',
            '~'
        ), array(
            '+',
            '/',
            '='
        ), $string);
        $string = base64_decode($string);
        $strlen = strlen($string);
        $keylen = strlen($key);
        for ($i = 0; $i < $strlen; $i ++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % $keylen) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }
        return $result;
    }
}
