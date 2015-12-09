<?php

/**
 * ابزارها و متدهای پرکاربرد
 * 
 * در این کلاس یک سری از متدهای پرکاربرد به صورت متدهای ایستا
 * پیاده سازی شده است.
 *
 */
class Pluf_Utils
{

    /**
     * Produces a random string.
     *
     * @param
     *            int Length of the random string to be generated.
     * @return string Random string
     */
    static function getRandomString ($len = 35)
    {
        $string = '';
        $chars = '0123456789abcdefghijklmnopqrstuvwxyz' .
                 'ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%&*()+=-_}{[]><?/';
        $lchars = strlen($chars);
        $i = 0;
        while ($i < $len) {
            $string .= substr($chars, mt_rand(0, $lchars - 1), 1);
            $i ++;
        }
        return $string;
    }

    /**
     * Produces a random password.
     *
     * The random password generator avoid characters that can be
     * confused like 0,O,o,1,l,I.
     *
     * @param
     *            int Length of the password (8)
     * @return string Password
     */
    static function getPassword ($len = 8)
    {
        $string = '';
        $chars = '23456789abcdefghijkmnpqrstuvwxyz' . 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $lchars = strlen($chars);
        $i = 0;
        while ($i < $len) {
            $string .= substr($chars, mt_rand(0, $lchars - 1), 1);
            $i ++;
        }
        return $string;
    }

    /**
     * Clean the name of a file to only have alphanumeric characters.
     *
     * @param
     *            string Name
     * @return string Clean name
     */
    static function cleanFileName ($name)
    {
        return mb_ereg_replace("/\015\012|\015|\012|\s|[^A-Za-z0-9\.\-\_]/", 
                '_', $name);
    }

    static function prettySize ($size)
    {
        switch (strtolower(substr($size, - 1))) {
            case 'k':
                $size = substr($size, 0, - 1) * 1000;
                break;
            case 'm':
                $size = substr($size, 0, - 1) * 1000 * 1000;
                break;
            case 'g':
                $size = substr($size, 0, - 1) * 1000 * 1000 * 1000;
                break;
        }
        if ($size > (1000 * 1000 * 1000)) {
            $mysize = sprintf('%01.2f', $size / (1000 * 1000 * 1000)) . ' ' .
                     __('GB');
        } elseif ($size > (1000 * 1000)) {
            $mysize = sprintf('%01.2f', $size / (1000 * 1000)) . ' ' . __('MB');
        } elseif ($size >= 1000) {
            $mysize = sprintf('%01.2f', $size / 1000) . ' ' . __('kB');
        } else {
            $mysize = sprintf(_n('%d byte', '%d bytes', $size), $size);
        }
        return $mysize;
    }

    /**
     * RFC(2)822 Email Parser
     *
     * By Cal Henderson <cal@iamcal.com>
     * This code is licensed under a Creative Commons
     * Attribution-ShareAlike 2.5 License
     * http://creativecommons.org/licenses/by-sa/2.5/
     * Revision 5 - http://www.iamcal.com/publish/articles/php/parsing_email/
     *
     * Comments were stripped, check the source for the way this
     * parser is built. It is a very interesting reading.
     *
     * @param
     *            string Email
     * @return bool Is email
     */
    static function isValidEmail ($email)
    {
        $email = trim($email);
        $n = explode(' ', $email);
        if (count($n) > 1) {
            return false;
        }
        $no_ws_ctl = "[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x7f]";
        $alpha = "[\\x41-\\x5a\\x61-\\x7a]";
        $digit = "[\\x30-\\x39]";
        $cr = "\\x0d";
        $lf = "\\x0a";
        $crlf = "($cr$lf)";
        $obs_char = "[\\x00-\\x09\\x0b\\x0c\\x0e-\\x7f]";
        $obs_text = "($lf*$cr*($obs_char$lf*$cr*)*)";
        $text = "([\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f]|$obs_text)";
        $obs_qp = "(\\x5c[\\x00-\\x7f])";
        $quoted_pair = "(\\x5c$text|$obs_qp)";
        $wsp = "[\\x20\\x09]";
        $obs_fws = "($wsp+($crlf$wsp+)*)";
        $fws = "((($wsp*$crlf)?$wsp+)|$obs_fws)";
        $ctext = "($no_ws_ctl|[\\x21-\\x27\\x2A-\\x5b\\x5d-\\x7e])";
        $ccontent = "($ctext|$quoted_pair)";
        $comment = "(\\x28($fws?$ccontent)*$fws?\\x29)";
        $cfws = "(($fws?$comment)*($fws?$comment|$fws))";
        $cfws = "$fws*";
        $atext = "($alpha|$digit|[\\x21\\x23-\\x27\\x2a\\x2b\\x2d\\x2f\\x3d\\x3f\\x5e\\x5f\\x60\\x7b-\\x7e])";
        $atom = "($cfws?$atext+$cfws?)";
        $qtext = "($no_ws_ctl|[\\x21\\x23-\\x5b\\x5d-\\x7e])";
        $qcontent = "($qtext|$quoted_pair)";
        $quoted_string = "($cfws?\\x22($fws?$qcontent)*$fws?\\x22$cfws?)";
        $word = "($atom|$quoted_string)";
        $obs_local_part = "($word(\\x2e$word)*)";
        $obs_domain = "($atom(\\x2e$atom)*)";
        $dot_atom_text = "($atext+(\\x2e$atext+)*)";
        $dot_atom = "($cfws?$dot_atom_text$cfws?)";
        $dtext = "($no_ws_ctl|[\\x21-\\x5a\\x5e-\\x7e])";
        $dcontent = "($dtext|$quoted_pair)";
        $domain_literal = "($cfws?\\x5b($fws?$dcontent)*$fws?\\x5d$cfws?)";
        $local_part = "($dot_atom|$quoted_string|$obs_local_part)";
        $domain = "($dot_atom|$domain_literal|$obs_domain)";
        $addr_spec = "($local_part\\x40$domain)";
        
        $done = 0;
        while (! $done) {
            $new = preg_replace("!$comment!", '', $email);
            if (strlen($new) == strlen($email)) {
                $done = 1;
            }
            $email = $new;
        }
        return preg_match("!^$addr_spec$!", $email) ? true : false;
    }

    /**
     * Validate an url.
     *
     * Only the structure is checked, no check of availability of the
     * url is performed. It is a really basic validation.
     */
    static function isValidUrl ($url)
    {
        $ip = '(25[0-5]|2[0-4]\d|[0-1]?\d?\d)(\.' .
                 '(25[0-5]|2[0-4]\d|[0-1]?\d?\d)){3}';
        $dom = '([a-z0-9\.\-]+)';
        return (preg_match('!^(http|https|ftp|gopher)\://(' . $ip . '|' . $dom .
                 ')!i', $url)) ? true : false;
    }

    /**
     * Convert a whatever separated list of items and returns an array
     * of them.
     *
     * @param
     *            string Items.
     * @param
     *            string Separator (',')
     * @return array Items.
     */
    static function itemsToArray ($items, $sep = ',')
    {
        $_t = explode($sep, $items);
        $res = array();
        foreach ($_t as $item) {
            $item = trim($item);
            if (strlen($item) > 0) {
                $res[] = $item;
            }
        }
        return $res;
    }

    /**
     * Run an external program capturing both stdout and stderr.
     *
     * @credits dk at brightbyte dot de
     * @source http://www.php.net/manual/en/function.shell-exec.php
     * 
     * @see proc_open
     *
     * @param
     *            string Command to run (will be passed to proc_open)
     * @param
     *            &int Return code of the command
     * @return mixed false in case of error or output string
     */
    public static function runExternal ($cmd, &$code)
    {
        $descriptorspec = array(
                // stdin is a pipe that the child will read from
                0 => array(
                        'pipe',
                        'r'
                ),
                // stdout is a pipe that the child will write to
                1 => array(
                        'pipe',
                        'w'
                ),
                // stderr is a file to write to
                2 => array(
                        'pipe',
                        'w'
                )
        );
        $pipes = array();
        $process = proc_open($cmd, $descriptorspec, $pipes);
        $output = '';
        if (! is_resource($process))
            return false;
        fclose($pipes[0]); // close child's input imidiately
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);
        $todo = array(
                $pipes[1],
                $pipes[2]
        );
        while (true) {
            $read = array();
            if (! feof($pipes[1]))
                $read[] = $pipes[1];
            if (! feof($pipes[2]))
                $read[] = $pipes[2];
            if (! $read)
                break;
            $write = $ex = array();
            $ready = stream_select($read, $write, $ex, 2);
            if ($ready === false) {
                break; // should never happen - something died
            }
            foreach ($read as $r) {
                $s = fread($r, 1024);
                $output .= $s;
            }
        }
        fclose($pipes[1]);
        fclose($pipes[2]);
        $code = proc_close($process);
        return $output;
    }

    /**
     * URL safe base 64 encoding.
     *
     * Compatible with python base64's urlsafe methods.
     *
     * @link http://www.php.net/manual/en/function.base64-encode.php#63543
     */
    public static function urlsafe_b64encode ($string)
    {
        return str_replace(array(
                '+',
                '/',
                '='
        ), array(
                '-',
                '_',
                ''
        ), base64_encode($string));
    }

    /**
     * URL safe base 64 decoding.
     */
    public static function urlsafe_b64decode ($string)
    {
        $data = str_replace(array(
                '-',
                '_'
        ), array(
                '+',
                '/'
        ), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    /**
     * Flatten an array.
     *
     * @param array $array
     *            The array to flatten.
     * @return array
     */
    public static function flattenArray ($array)
    {
        $result = array();
        foreach (new RecursiveIteratorIterator(
                new RecursiveArrayIterator($array)) as $value) {
            $result[] = $value;
        }
        
        return $result;
    }
}
