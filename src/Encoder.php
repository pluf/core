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

use Pluf\HTTP\Error403;

/**
 * Validators are functions used to validate user/program input.
 *
 * A validator signature is:
 * my_validator($field_data, $params=array())
 * with $params an associative array of parameters.
 *
 *
 * A validator must fail on an empty string by raising an
 * Pluf_Form_Invalid Exception or return the data in the right format
 * (string, bool, whatever).
 *
 * FIXME: Escape the strings when bad strings are sent in the error message.
 */
class Encoder
{

    /**
     * Store the complete form data if validation is coming from a form.
     */
    protected $form = array();

    /**
     * Set the form data.
     *
     * @param
     *            &array Reference to the form data
     */
    function setFormData(&$form)
    {
        $this->form = $form;
    }

    /**
     * Check if could be empty or not.
     */
    function checkEmpty($data, $form = array(), $p = array())
    {
        if (strlen($data) == 0 and isset($p['blank']) and false == $p['blank']) {
            throw new Error403('The value must not be empty.');
        }
        return true;
    }

    /**
     * Validate an url.
     *
     * Only the structure is checked, no check of availability of the
     * url is performed. It is a really basic validation.
     */
    static function url($url, $form = array(), $p = array())
    {
        $ip = '(25[0-5]|2[0-4]\d|[0-1]?\d?\d)(\.' . '(25[0-5]|2[0-4]\d|[0-1]?\d?\d)){3}';
        $dom = '([a-z0-9\.\-]+)';
        if (preg_match('!^(http|https|ftp|gopher)\://(' . $ip . '|' . $dom . ')!i', $url)) {
            return $url;
        } else {
            throw new Error403(sprintf(('The URL <em>%s</em> is not valid.'), htmlspecialchars($url)));
        }
    }

    static function varchar($string, $form = array(), $p = array())
    {
        if (isset($p['size']) && strlen($string) > $p['size']) {
            throw new Error403(sprintf(('The value should not be more than <em>%s</em> characters long.'), $p['size']));
        }
        return $string;
    }

    static function password($string, $form = array(), $p = array())
    {
        if (strlen($string) < 6) {
            throw new Error403(sprintf(('The password must be at least <em>%s</em> characters long.'), '6'));
        }
        return $string;
    }

    static function email($string, $form = array(), $p = array())
    {
        if (preg_match('/^[A-Z0-9._%-][+A-Z0-9._%-]*@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}$/i', $string)) {
            return $string;
        } else {
            throw new Error403(sprintf(('The email address "%s" is not valid.'), $string));
        }
    }

    static function text($string, $form = array(), $p = array())
    {
        return Encoder::varchar($string, $form, $p);
    }

    static function sequence($id, $form = array(), $p = array())
    {
        return Encoder::integer($id, $p);
    }

    static function boolean($bool, $form = array(), $p = array())
    {
        if (in_array($bool, array(
            'on',
            'y',
            '1',
            1,
            true
        ))) {
            return true;
        }
        return false;
    }

    static function foreignkey($id, $form = array(), $p = array())
    {
        return Encoder::integer($id, $p);
    }

    static function integer($int, $form = array(), $p = array())
    {
        if (! preg_match('/[0-9]+/', $int)) {
            throw new Error403(('The value must be an integer.'));
        }
        return (int) $int;
    }

    static function datetime($datetime, $form = array(), $p = array())
    {
        if (false === ($stamp = strtotime($datetime))) {
            throw new Error403(sprintf(('The date and time <em>%s</em> are not valid.'), htmlspecialchars($datetime)));
        }
        // convert to GMT
        return gmdate('Y-m-d H:i:s', $stamp);
    }

    static function date($date, $form = array(), $p = array())
    {
        $ymd = explode('-', $date);
        if (count($ymd) != 3 or strlen($ymd[0]) != 4 or false === checkdate($ymd[1], $ymd[2], $ymd[0])) {
            throw new Error403(sprintf(('The date <em>%s</em> is not valid.'), htmlspecialchars($date)));
        }
        return $date;
    }

    static function manytomany($vals, $form = array(), $p = array())
    {
        $res = array();
        foreach ($vals as $val) {
            $res[] = Encoder::integer($val);
        }
        return $res;
    }

    static function float($val, $form = array(), $p = array())
    {
        return (float) $val;
    }
}
