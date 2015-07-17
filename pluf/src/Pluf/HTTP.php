<?php

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
