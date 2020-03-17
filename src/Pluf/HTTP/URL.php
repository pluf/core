<?php

/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
 * # ***** BEGIN LICENSE BLOCK *****
 * # This file is part of Plume Framework, a simple PHP Application Framework.
 * # Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
 * #
 * # Plume Framework is free software; you can redistribute it and/or modify
 * # it under the terms of the GNU Lesser General Public License as published by
 * # the Free Software Foundation; either version 2.1 of the License, or
 * # (at your option) any later version.
 * #
 * # Plume Framework is distributed in the hope that it will be useful,
 * # but WITHOUT ANY WARRANTY; without even the implied warranty of
 * # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * # GNU Lesser General Public License for more details.
 * #
 * # You should have received a copy of the GNU Lesser General Public License
 * # along with this program; if not, write to the Free Software
 * # Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 * #
 * # ***** END LICENSE BLOCK *****
 */

/**
 * Generate a ready to use URL to be used in location/redirect or forms.
 *
 * When redirecting a user, depending of the format of the url with
 * mod_rewrite or not, the parameters must all go in the GET or
 * everything but the action. This class provide a convinient way to
 * generate those url and parse the results for the dispatcher.
 */
class Pluf_HTTP_URL
{

    /**
     * Defines type ot the url
     *
     * @var string
     */
    var string $type = 'simple';

    function __construct(string $type = 'simple')
    {
        $this->type = $type;
    }

    /**
     * Generate the URL.
     *
     * The & is encoded as &amp; in the url.
     *
     * @param
     *            string Action url
     * @param
     *            array Associative array of the parameters (array())
     * @param
     *            bool Encode the & in the url (true)
     * @return string Ready to use URL.
     */
    public function generate(string $action, $params = array())
    {
        $url = '';
        switch ($this->type) {
            case 'simple':
                $params['_px_action'] = $action;
                $url = '?' . http_build_query($params);
                break;
            case 'mod_rewrite':
                $url = $action;
                if (count($params)) {
                    $url .= '?' . http_build_query($params);
                }
                break;
            default:
                throw new Exception('Unsupported URL type: "' . $this->type . '"');
        }
        return $url;
    }

    /**
     * Get the action of the request.
     *
     * We directly get the PATH_INFO OR ORIG_PATH_INFO variable or return '/'
     *
     * @return string Action
     */
    public function getAction()
    {
        switch ($this->type) {
            case 'simple':
                return $_GET['_px_action'];
            case 'mod_rewrite':
                $request_uri = '';
                if (isset($_SERVER['PATH_INFO'])) {
                    $request_uri = trim($_SERVER['PATH_INFO']);
                } else if (isset($_SERVER['ORIG_PATH_INFO'])) {
                    $request_uri = trim(str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['ORIG_PATH_INFO']), '/');
                }
                return $request_uri;
            default:
                throw new Exception('Unsupported URL type: "' . $this->type . '"');
        }
    }

    /**
     * Provide the full URL (without domain) to a view.
     *
     * @param
     *            string View.
     * @param
     *            array Parameters for the view (array()).
     * @param
     *            array Extra GET parameters for the view (array()).
     * @param
     *            bool Should the URL be encoded (true).
     * @return string URL.
     */
    public static function urlForView($view, $params = array(), $get_params = array())
    {
        $url = new Pluf_HTTP_URL(Pluf::f('url_format', 'mod_rewrite'));
        return $url->generate(self::reverse($view, $params), $get_params);
    }

    /**
     * Reverse an URL.
     *
     * @param
     *            string View in the form 'class::method' or string of the name.
     * @param
     *            array Possible parameters for the view (array()).
     * @return string URL.
     */
    public static function reverse($view, $params = array())
    {
        $model = '';
        $method = '';
        if (false !== strpos($view, '::')) {
            list ($model, $method) = explode('::', $view);
        }
        $vdef = array(
            $model,
            $method,
            $view
        );
        $regbase = array(
            '',
            array()
        );
        $regbase = self::find($GLOBALS['_PX_views'], $vdef, $regbase);
        if ($regbase === false) {
            throw new Exception(sprintf('Error, the view: %s has not been found.', $view));
        }
        $url = '';
        foreach ($regbase[1] as $regex) {
            if ($regex == '#^#')
                continue;
            $url .= self::buildReverseUrl($regex, $params);
        }
        if (! defined('IN_UNIT_TESTS')) {
            $url = $regbase[0] . $url;
        }

        return $url;
    }

    /**
     * Go in the list of views to find the matching one.
     *
     * @param
     *            array Views
     * @param
     *            array View definition array(model, method, name)
     * @param
     *            array Regex of the view up to now and base
     * @return mixed Regex of the view or false
     */
    public static function find($views, $vdef, $regbase)
    {
        foreach ($views as $dview) {
            if (isset($dview['sub'])) {
                $regbase2 = $regbase;
                if (empty($regbase2[0])) {
                    $regbase2[0] = $dview['base'];
                }
                $regbase2[1][] = $dview['regex'];
                $res = self::find($dview['sub'], $vdef, $regbase2);
                if ($res) {
                    return $res;
                }
                continue;
            }
            if ((isset($dview['name']) && $dview['name'] == $vdef[2]) or ($dview['model'] == $vdef[0] && $dview['method'] == $vdef[1])) {
                $regbase[1][] = $dview['regex'];
                if (! empty($dview['base'])) {
                    $regbase[0] = $dview['base'];
                }
                return $regbase;
            }
        }
        return false;
    }

    /**
     * Build the reverse URL without the path base.
     *
     * Credits to Django, again...
     *
     * @param
     *            string Regex for the URL.
     * @param
     *            array Parameters
     * @return string URL filled with the parameters.
     */
    public static function buildReverseUrl($url_regex, $params = array())
    {
        $url = str_replace(array(
            '\\.',
            '\\-'
        ), array(
            '.',
            '-'
        ), $url_regex);
        if (count($params)) {
            $groups = array_fill(0, count($params), '#\(([^)]+)\)#');
            $url = preg_replace($groups, $params, $url, 1);
        }
        $matches = array();
        preg_match('/^#\^?([^#\$]+)/', $url, $matches);
        return $matches[1];
    }
}

/**
 * Provide the full URL (without domain) to a view.
 *
 * @param
 *            string View.
 * @param
 *            array Parameters for the view (array()).
 * @param
 *            array Extra GET parameters for the view (array()).
 * @param
 *            bool Should the URL be encoded (true).
 * @return string URL.
 * @deprecated use Pluf_HTTP_URL::urlForView
 */
function Pluf_HTTP_URL_urlForView($view, $params = array(), $get_params = array(), $encoded = true)
{
    return Pluf_HTTP_URL::urlForView($view, $params, $get_params);
}

/**
 * Reverse an URL.
 *
 * @param
 *            string View in the form 'class::method' or string of the name.
 * @param
 *            array Possible parameters for the view (array()).
 * @return string URL.
 * @deprecated Use Pluf_HTTP_URL::reverse
 */
function Pluf_HTTP_URL_reverse($view, $params = array())
{
    return Pluf_HTTP_URL::reverse($view, $params);
}

/**
 * Go in the list of views to find the matching one.
 *
 * @param
 *            array Views
 * @param
 *            array View definition array(model, method, name)
 * @param
 *            array Regex of the view up to now and base
 * @return mixed Regex of the view or false
 * @deprecated Use Pluf_HTTP_URL::find
 */
function Pluf_HTTP_URL_find($views, $vdef, $regbase)
{
    return Pluf_HTTP_URL::find($views, $vdef, $regbase);
}

/**
 * Build the reverse URL without the path base.
 *
 * Credits to Django, again...
 *
 * @param
 *            string Regex for the URL.
 * @param
 *            array Parameters
 * @return string URL filled with the parameters.
 * @deprecated Use Pluf_HTTP_URL::buildReverseUrl
 */
function Pluf_HTTP_URL_buildReverseUrl($url_regex, $params = array())
{
    return Pluf_HTTP_URL::buildReverseUrl($url_regex, $params);
}
