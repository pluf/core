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
 * reCAPTCHA input for your forms.
 *
 * Based on http://recaptcha.googlecode.com/files/recaptcha-php-1.10.zip
 *
 * Copyright (c) 2007 reCAPTCHA -- http://recaptcha.net
 * AUTHORS:
 *   Mike Crawford
 *   Ben Maurer
 *
 * @see Pluf_Form_Field_ReCaptcha 
 *
 */
class Pluf_Form_Widget_ReCaptcha extends Pluf_Form_Widget_Input
{
    public $input_type = 'text';
    public $ssl = false;
    public $pubkey = '';


    /**
     * Renders the HTML of the input.
     *
     * @param string Name of the field.
     * @param mixed Value for the field, can be a non valid value.
     * @param array Extra attributes to add to the input form (array())
     * @return string The HTML string of the input.
     */
    public function render($name, $value, $extra_attrs=array())
    {
        return Pluf_Template::markSafe(self::getHtml($this->attrs['pubkey']));
    }

    /**
     * Gets the challenge HTML (javascript and non-javascript
     * version).  This is called from the browser, and the resulting
     * reCAPTCHA HTML widget is embedded within the HTML form it was
     * called from.
     *
     * @param string A public key for reCAPTCHA
     * @param string The error given by reCAPTCHA (null)
     * @param boolean Should the request be made over ssl? (false)
     * @return string The HTML to be embedded in the user's form.
     */
    public static function getHtml($pubkey, $error=null, $use_ssl=false)
    {
        $server = ($use_ssl) ? 'https://api-secure.recaptcha.net' 
                             : 'http://api.recaptcha.net';
        $errorpart = ($error) ? '&amp;error='.$error : '';

        return '<script type="text/javascript" src="'.$server.'/challenge?k='
            .$pubkey.$errorpart.'"></script>
             <noscript>
             <iframe src="'.$server.'/noscript?k='.$pubkey.$errorpart
            .'" height="300" width="500" frameborder="0"></iframe><br/>
             <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
             <input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
             </noscript>';
    }

    /**
     * Get the form data from the reCaptcha fields.
     *
     * We need to get back two fields from the POST request
     * 'recaptcha_challenge_field' and 'recaptcha_response_field'.
     *
     * They are hardcoded, so we do not even bother checking something
     * else. 
     *
     * @param string Name of the form
     * @param array Submitted form data
     * @return array Challenge and answer
     */
    public function valueFromFormData($name, $data)
    {
        $res = array('', '');
        $res[0] = isset($data['recaptcha_challenge_field']) 
            ? $data['recaptcha_challenge_field'] : '';
        $res[1] = isset($data['recaptcha_response_field']) 
            ? $data['recaptcha_response_field'] : '';
        return $res;
    }
}
