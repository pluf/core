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
 * Base HTTP tools.
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
