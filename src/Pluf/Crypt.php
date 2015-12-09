<?php

/**
 * یک کلاس ساده برای کارهای رمزنگاری
 *
 * برخی از کارهای ساده و پرکاربرد رمزنگاری در این کلاس پیاده سازی شده که 
 * در کاربردهای متفاوت سیستم به کار گرفته شود. از این میان می‌توانیم به
 * رمز کردن و رمزگشایی از این داده‌ها اشاره کنیم.
 *
 * @note از این پیاده سازی برای کارهای که داده‌های آنها واقعال امن باید
 * باشده استفاده نکنید.
 *
 * Credit Anonymous on http://www.php.net/mcrypt
 */
class Pluf_Crypt
{

    public $key = '';

    /**
     * Construct the encryption object.
     *
     * @param
     *            string The encryption key ('')
     */
    function __construct ($key = '')
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
    function encrypt ($string, $key = '')
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
    function decrypt ($string, $key = '')
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
