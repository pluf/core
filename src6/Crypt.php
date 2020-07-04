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

use Pluf;

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
    
    
    /**
     * Dump and sign an object.
     *
     * If you want to sign a small string, use directly the
     * sign/unsign function as compression will not help and you will
     * save the overhead of the serialize call.
     *
     * @param
     *            mixed Object
     * @param
     *            string Key (null)
     * @param
     *            bool Compress with gzdeflate (false)
     * @param
     *            string Extra key not to use only the secret_key ('')
     * @return string Signed string
     */
    public static function dumps($obj, $key = null, $compress = false, $extra_key = '')
    {
        $serialized = serialize($obj);
        $is_compressed = false; // Flag for if it's been compressed or not
        if ($compress) {
            $compressed = gzdeflate($serialized, 9);
            if (strlen($compressed) < (strlen($serialized) - 1)) {
                $serialized = $compressed;
                $is_compressed = true;
            }
        }
        $base64d = Utils::urlsafe_b64encode($serialized);
        if ($is_compressed) {
            $base64d = '.' . $base64d;
        }
        if ($key === null) {
            $key = Pluf::f('secret_key');
        }
        return self::sign($base64d, $key . $extra_key);
    }
    
    /**
     * Reverse of dumps, throw an Exception in case of bad signature.
     *
     * @param
     *            string Signed key
     * @param
     *            string Key (null)
     * @param
     *            string Extra key ('')
     * @return mixed The dumped signed object
     */
    public static function loads($s, $key = null, $extra_key = '')
    {
        if ($key === null) {
            $key = Pluf::f('secret_key');
        }
        $base64d = self::unsign($s, $key . $extra_key);
        $decompress = false;
        if ($base64d[0] == '.') {
            // It's compressed; uncompress it first
            $base64d = substr($base64d, 1);
            $decompress = true;
        }
        $serialized = Utils::urlsafe_b64decode($base64d);
        if ($decompress) {
            $serialized = gzinflate($serialized);
        }
        return unserialize($serialized);
    }
    
    /**
     * Sign a string.
     *
     * If the key is not provided, it will use the secret_key
     * available in the configuration file.
     *
     * The signature string is safe to use in URLs. So if the string to
     * sign is too, you can use the signed string in URLs.
     *
     * @param
     *            string The string to sign
     * @param
     *            string Optional key (null)
     * @return string Signed string
     */
    public static function sign($value, $key = null)
    {
        if ($key === null) {
            $key = Pluf::f('secret_key');
        }
        return $value . '.' . self::base64_hmac($value, $key);
    }
    
    /**
     * Unsign a value.
     *
     * It will throw an exception in case of error in the process.
     *
     * @return string Signed string
     * @param
     *            string Optional key (null)
     * @param
     *            string The string
     */
    public static function unsign($signed_value, $key = null)
    {
        if ($key === null) {
            $key = Pluf::f('secret_key');
        }
        $compressed = ($signed_value[0] == '.') ? '.' : '';
        if ($compressed) {
            $signed_value = substr($signed_value, 1);
        }
        if (false === strpos($signed_value, '.')) {
            throw new Exception('Missing signature (no . found in value).');
        }
        list ($value, $sig) = explode('.', $signed_value, 2);
        if (self::base64_hmac($compressed . $value, $key) == $sig) {
            return $compressed . $value;
        } else {
            throw new Exception(sprintf('Signature failed: "%s".', $sig));
        }
    }
    
    /**
     * Calculate the URL safe base64 encoded SHA1 hmac of a string.
     *
     * @param
     *            string The string to sign
     * @param
     *            string The key
     * @return string The signature
     */
    public static function base64_hmac($value, $key)
    {
        return Utils::urlsafe_b64encode(hash_hmac('sha1', $value, $key, true));
    }
}
