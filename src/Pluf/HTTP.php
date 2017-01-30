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

/**
 * ابزارهای پایه کار با HTTP.
 */
class Pluf_HTTP
{

    /**
     * Break magic_quotes
     *
     * @credit Olivier Meunier
     */
    function removeTheMagic()
    {
        if (get_magic_quotes_gpc()) {
            if (!empty($_GET)) {
                array_walk($_GET, 'Pluf_HTTP_magicStrip');
            }
            if (!empty($_POST)) {
                array_walk($_POST, 'Pluf_HTTP_magicStrip');
            }
            if (!empty($_REQUEST)) {
                array_walk($_REQUEST, 'Pluf_HTTP_magicStrip');
            }
            if (!empty($_COOKIE)) {
                array_walk($_COOKIE, 'Pluf_HTTP_magicStrip');
            }
        }
        if (function_exists('ini_set')) {
            @ini_set('session.use_cookies', '1');
            @ini_set('session.use_only_cookies', '1');
            @ini_set('session.use_trans_sid', '0');
            @ini_set('url_rewriter.tags', '');
        }
    }
}


/**
 * Break magic_quotes
 *
 * @credit Olivier Meunier
 */
function Pluf_HTTP_magicStrip(&$k, $key)
{
    $k = Pluf_HTTP_handleMagicQuotes($k);
}

/**
 * Break magic_quotes
 *
 * @credit Olivier Meunier
 */
function Pluf_HTTP_handleMagicQuotes(&$value)
{
    if (is_array($value)) {
        $result = array();
        foreach ($value as $k => $v) {
            if (is_array($v)) {
                $result[$k] = Pluf_HTTP_handleMagicQuotes($v);
            } else {
                $result[$k] = stripslashes($v);
            }
        }
        return $result;
    } else {
        return stripslashes($value);
    }
}
