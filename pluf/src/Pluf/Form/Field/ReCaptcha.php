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
 * Add ReCaptcha control to your forms.
 *
 * You need first to get a ReCaptcha account, create a domain and get
 * the API keys for your domain. Check http://recaptcha.net/ for more
 * information.
 *
 * The recaptcha field needs to know the IP address of the user
 * submitting the form and if the request is made over SSL or
 * not. This means that you need to provide the $request object in the
 * extra parameters of your form.
 *
 * To add the ReCaptcha field to your form, simply add the following
 * to your form object (note the use of $extra['request']):
 *
 * <pre>
 * $ssl = (!empty($extra['request']->SERVER['HTTPS']) 
 *         and $extra['request']->SERVER['HTTPS'] != 'off');
 *
 * $this->fields['recaptcha'] = new Pluf_Form_Field_ReCaptcha(
 *                       array('required' => true,
 *                               'label' => __('Please solve this challenge'),
 *                               'privkey' => 'PRIVATE_RECAPTCHA_KEY_HERE',
 *                               'remoteip' => $extra['request']->remote_addr,
 *                               'widget_attrs' => array(
 *                                      'pubkey' => 'PUBLIC_RECAPTCHA_KEY_HERE',
 *                                      ),
 *                                      ));
 * </pre>
 *
 * Then in your template, you simply need to add the ReCaptcha field:
 * 
 * <pre>
 * {if $form.f.recaptcha.errors}{$form.f.recaptcha.fieldErrors}{/if}
 * {$form.f.recaptcha|safe}
 * </pre>
 *
 * Based on http://recaptcha.googlecode.com/files/recaptcha-php-1.10.zip
 *
 * Copyright (c) 2007 reCAPTCHA -- http://recaptcha.net
 * AUTHORS:
 *   Mike Crawford
 *   Ben Maurer
 */
class Pluf_Form_Field_ReCaptcha extends Pluf_Form_Field
{
    public $widget = 'Pluf_Form_Widget_ReCaptcha';
    public $privkey = '';
    public $remoteip = '';
    public $extra_params = array();

    public function clean($value)
    {
        // will throw the Pluf_Form_Invalid exception in case of
        // error.
        self::checkAnswer($this->privkey, $this->remoteip, 
                          $value[0], $value[1], $this->extra_params);
        return $value;
    }

    /**
     * Submits an HTTP POST to a reCAPTCHA server
     *
     * @param string Host
     * @param string Path
     * @param array Data
     * @param int port (80
     * @return array response
     */
    public static function httpPost($host, $path, $data, $port=80) 
    {

        $req = self::qsencode($data);
        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
        $http_request .= "Content-Length: " . strlen($req) . "\r\n";
        $http_request .= "User-Agent: reCAPTCHA/PHP\r\n";
        $http_request .= "\r\n";
        $http_request .= $req;

        if (false === ($fs=@fsockopen($host, $port, $errno, $errstr, 10))) {
            throw new Pluf_Form_Invalid(__('Cannot connect to the reCaptcha server for validation.'));
        }
        fwrite($fs, $http_request);
        $response = '';
        while (!feof($fs)) {
            $response .= fgets($fs, 1160); // One TCP-IP packet
        }
        fclose($fs);
        return explode("\r\n\r\n", $response, 2);
    }

    /**
     * Encodes the given data into a query string format
     *
     * @param array Array of string elements to be encoded
     * @return string Encoded request
     */
    public static function qsencode($data) 
    {
        $d = array();
        foreach ($data as $key => $value) {
            $d[] = $key.'='.urlencode(stripslashes($value));
        }
        return implode('&', $d);
    }

    /**
     * Calls an HTTP POST function to verify if the user's guess was correct
     * @param string $privkey
     * @param string $remoteip
     * @param string $challenge
     * @param string $response
     * @param array $extra_params an array of extra variables to post to the server
     * @return ReCaptchaResponse
     */
    public static function checkAnswer($privkey, $remoteip, $challenge, $response, $extra_params=array())
    {
        if ($privkey == '') {
            throw new Pluf_Form_Invalid(__('To use reCAPTCHA you must set your API key.'));
        }
        if ($remoteip == '') {
            throw new Pluf_Form_Invalid(__('For security reasons, you must pass the remote ip to reCAPTCHA.'));
        }
        //discard spam submissions
        if (strlen($challenge) == 0 || strlen($response) == 0) {
            return false;
        }

        $response = self::httpPost('api-verify.recaptcha.net', '/verify',
                                   array(
                                         'privatekey' => $privkey,
                                         'remoteip' => $remoteip,
                                         'challenge' => $challenge,
                                         'response' => $response
                                         ) + $extra_params
                                   );

        $answers = explode("\n", $response[1]);
        if (trim($answers[0]) == 'true') {
            return true;
        } else {
            throw new Pluf_Form_Invalid($answers[1]);
        }
    }
}
