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
namespace Pluf\Text\HTML;

/**
 * A PHP HTML filtering library
 *
 * lib_filter.php,v 1.15 2006/09/28 22:44:31 cal
 *
 * http://iamcal.com/publish/articles/php/processing_html/
 * http://iamcal.com/publish/articles/php/processing_html_part_2/
 *
 * By Cal Henderson <cal@iamcal.com>
 *
 * This code is licensed under a Creative Commons
 * Attribution-ShareAlike 2.5 License
 * http://creativecommons.org/licenses/by-sa/2.5/
 *
 * Thanks to Jang Kim for adding support for single quoted attributes.
 *
 * TODO: Add HTML check from:
 * http://simonwillison.net/2003/Feb/23/safeHtmlChecker/
 */
class Filter
{

    public $tag_counts = array();

    /**
     * tags and attributes that are allowed
     */
    public $allowed = array(
        'a' => array(
            'href',
            'target'
        ),
        'b' => array(),
        'img' => array(
            'src',
            'width',
            'height',
            'alt'
        )
    );

    /**
     * tags which should always be self-closing (e.g.
     * "<img />")
     */
    public $no_close = array(
        'img'
    );

    /**
     * tags which must always have seperate opening and closing tags
     * (e.g.
     * "<b></b>")
     */
    public $always_close = array(
        'a',
        'b'
    );

    /**
     * attributes which should be checked for valid protocols
     */
    public $protocol_attributes = array(
        'src',
        'href'
    );

    /**
     * protocols which are allowed
     */
    public $allowed_protocols = array(
        'http',
        'ftp',
        'mailto'
    );

    /**
     * tags which should be removed if they contain no content
     * (e.g.
     * "<b></b>" or "<b />")
     */
    public $remove_blanks = array(
        'a',
        'b'
    );

    /**
     * should we remove comments?
     */
    public $strip_comments = 1;

    /**
     * should we try and make a b tag out of "b>"
     */
    public $always_make_tags = 0;

    /**
     * Allows decimal entities.
     *
     * An entity has to decimal format <code>&#32</code>.
     * For example, the entity <code>&#64;</code> is the <code>@</code> character.
     *
     * @var int
     */
    public $allow_numbered_entities = 1;

    /**
     * Allows hexadecimal entities.
     *
     * An entity has to decimal format <code>&#x20</code>.
     * For example, the entity <code>&#x40;</code> is the <code>@</code> character.
     *
     * @var int
     */
    public $allow_hexadecimal_entities = 1;

    public $allowed_entities = array(
        'amp',
        'gt',
        'lt',
        'quot'
    );

    function go($data)
    {
        $this->tag_counts = array();
        $data = $this->escape_comments($data);
        $data = $this->balance_html($data);
        $data = $this->check_tags($data);
        $data = $this->process_remove_blanks($data);
        $data = $this->validate_entities($data);
        return $data;
    }

    function escape_comments($data)
    {
        $data = preg_replace_callback("/<!--(.*?)-->/s", function ($matchs) {
            return '<!--' . HtmlSpecialChars($this->StripSingle($matchs[1])) . '-->';
        }, $data);
        return $data;
    }

    function balance_html($data)
    {
        if ($this->always_make_tags) {
            // try and form html
            $data = preg_replace("/>>+/", ">", $data);
            $data = preg_replace("/<<+/", "<", $data);
            $data = preg_replace("/^>/", "", $data);
            $data = preg_replace("/<([^>]*?)(?=<|$)/", "<$1>", $data);
            $data = preg_replace("/(^|>)([^<]*?)(?=>)/", "$1<$2", $data);
        } else {
            // escape stray brackets
            $data = preg_replace("/<([^>]*?)(?=<|$)/", "&lt;$1", $data);
            $data = preg_replace("/(^|>)([^<]*?)(?=>)/", "$1$2&gt;<", $data);
            // the last regexp causes '<>' entities to appear
            // (we need to do a lookahead assertion so that the last bracket can
            // be used in the next pass of the regexp)
            $data = str_replace('<>', '', $data);
        }
        // echo "::".HtmlSpecialChars($data)."<br />\n";
        return $data;
    }

    function check_tags($data)
    {
        $data = preg_replace_callback("/<(.*?)>/s", function ($matchs) {
            return $this->process_tag($this->StripSingle($matchs[1]));
        }, $data);
        foreach (array_keys($this->tag_counts) as $tag) {
            for ($i = 0; $i < $this->tag_counts[$tag]; $i ++) {
                $data .= "</$tag>";
            }
        }
        return $data;
    }

    function process_tag($data)
    {
        // ending tags
        $matches = [];
        if (preg_match("/^\/([a-z0-9]+)/si", $data, $matches)) {
            $name = StrToLower($matches[1]);
            if (in_array($name, array_keys($this->allowed))) {
                if (! in_array($name, $this->no_close)) {
                    if (isset($this->tag_counts[$name]) and $this->tag_counts[$name]) {
                        $this->tag_counts[$name] --;
                        return '</' . $name . '>';
                    }
                }
            } else {
                return '';
            }
        }
        // starting tags
        if (preg_match("/^([a-z0-9]+)(.*?)(\/?)$/si", $data, $matches)) {
            $name = StrToLower($matches[1]);
            $body = $matches[2];
            $ending = $matches[3];
            if (in_array($name, array_keys($this->allowed))) {
                $params = "";
                $matches_1 = [];
                $matches_2 = [];
                $matches_3 = [];
                preg_match_all("/([a-z0-9]+)=([\"'])(.*?)\\2/si", $body, $matches_2, PREG_SET_ORDER); // <foo a="b" />
                preg_match_all("/([a-z0-9]+)(=)([^\"\s']+)/si", $body, $matches_1, PREG_SET_ORDER); // <foo a=b />
                preg_match_all("/([a-z0-9]+)=([\"'])([^\"']*?)\s*$/si", $body, $matches_3, PREG_SET_ORDER); // <foo a="b />
                $matches = array_merge($matches_1, $matches_2, $matches_3);
                foreach ($matches as $match) {
                    $pname = StrToLower($match[1]);
                    if (in_array($pname, $this->allowed[$name])) {
                        $value = $match[3];
                        if (in_array($pname, $this->protocol_attributes)) {
                            $value = $this->process_param_protocol($value);
                        }
                        $params .= " $pname=\"$value\"";
                    }
                }
                if (in_array($name, $this->no_close)) {
                    $ending = ' /';
                }
                if (in_array($name, $this->always_close)) {
                    $ending = '';
                }
                if (! $ending) {
                    if (isset($this->tag_counts[$name])) {
                        $this->tag_counts[$name] ++;
                    } else {
                        $this->tag_counts[$name] = 1;
                    }
                }
                if ($ending) {
                    $ending = ' /';
                }
                return '<' . $name . $params . $ending . '>';
            } else {
                return '';
            }
        }
        // comments
        if (preg_match("/^!--(.*)--$/si", $data)) {
            if ($this->strip_comments) {
                return '';
            } else {
                return '<' . $data . '>';
            }
        }
        // garbage, ignore it
        return '';
    }

    function process_param_protocol($data)
    {
        $data = $this->decode_entities($data);
        $matches = [];
        if (preg_match("/^([^:]+)\:/si", $data, $matches)) {
            if (! in_array($matches[1], $this->allowed_protocols)) {
                $data = '#' . substr($data, strlen($matches[1]) + 1);
            }
        }
        return $data;
    }

    function process_remove_blanks($data)
    {
        foreach ($this->remove_blanks as $tag) {
            $data = preg_replace("/<{$tag}(\s[^>]*)?><\\/{$tag}>/", '', $data);
            $data = preg_replace("/<{$tag}(\s[^>]*)?\\/>/", '', $data);
        }
        return $data;
    }

    function fix_case($data)
    {
        $data_notags = Strip_Tags($data);
        $data_notags = preg_replace('/[^a-zA-Z]/', '', $data_notags);
        if (strlen($data_notags) < 5) {
            return $data;
        }
        if (preg_match('/[a-z]/', $data_notags)) {
            return $data;
        }
        return preg_replace_callback("/(>|^)([^<]+?)(<|$)/s", function ($matchs) {
            return $this->StripSingle($matchs[1]) . $this->fix_case_inner($this->StripSingle($matchs[1])) . $this->StripSingle($matchs[1]);
        }, $data);
    }

    function fix_case_inner($data)
    {
        $data = StrToLower($data);
        $data = preg_replace_callback('/(^|[^\w\s\';,\\-])(\s*)([a-z])/', function ($matchs) {
            return $this->StripSingle($matchs[1] . $matchs[2]) . StrToUpper($this->StripSingle($matchs[1]));
        }, $data);
        return $data;
    }

    function validate_entities($data)
    {
        // validate entities throughout the string
        $data = preg_replace_callback('!&([^&;]*)(?=(;|&|$))!', function ($matchs) {
            return $this->check_entity($this->StripSingle($matchs[1]), $this->StripSingle($matchs[2]));
        }, $data);
        // validate quotes outside of tags
        $data = preg_replace_callback("/(>|^)([^<]+?)(<|$)/s", function ($matchs) {
            return $this->StripSingle($matchs[1]) . str_replace('"', '&quot;', $this->StripSingle($matchs[2])) . $this->StripSingle($matchs[3]);
        }, $data);
        return $data;
    }

    function check_entity($preamble, $term)
    {
        if (';' === $term) {
            if ($this->is_valid_entity($preamble)) {
                return '&' . $preamble;
            }
        }
        return '&amp;' . $preamble;
    }

    /**
     * Determines if the string provided is a valid entity.
     *
     * @param string $entity
     *            String to test against.
     * @return boolean
     */
    function is_valid_entity($entity)
    {
        $m = [];
        if (preg_match('#^\#([0-9]{2,}|x[0-9a-f]{2,})$#i', $entity, $m)) {
            if (0 === strpos($m[1], 'x')) {
                // hexadecimal entity
                if ($this->allow_hexadecimal_entities && $this->not_control_caracter($m[1])) {
                    return true;
                }
                return false;
            } else {
                // decimal entity
                if ($this->allow_numbered_entities && $this->not_control_caracter($m[1])) {
                    return true;
                }
                return false;
            }
        }
        // HTML 4.0 character entity
        return in_array($entity, $this->allowed_entities);
    }

    /**
     * Determines if the data provided is not a control character.
     *
     * @param string|int $data
     *            Data to test against like "64" or "x40".
     * @return boolean
     */
    function not_control_caracter($data)
    {
        if (0 === strpos($data, 'x')) {
            $data = substr($data, 1);
            $data = hexdec($data);
        } else {
            $data = intval($data);
        }
        return (31 < $data && (127 > $data || 159 < $data));
    }

    // within attributes, we want to convert all hex/dec/url escape
    // sequences into their raw characters so that we can check we
    // don't get stray quotes/brackets inside strings
    function decode_entities($data)
    {
        $data = preg_replace_callback('!(&)#(\d+);?!', array(
            $this,
            'decode_dec_entity'
        ), $data);
        $data = preg_replace_callback('!(&)#x([0-9a-f]+);?!i', array(
            $this,
            'decode_hex_entity'
        ), $data);
        $data = preg_replace_callback('!(%)([0-9a-f]{2});?!i', array(
            $this,
            'decode_hex_entity'
        ), $data);
        $data = $this->validate_entities($data);
        return $data;
    }

    function decode_hex_entity($m)
    {
        return $this->decode_num_entity($m[1], hexdec($m[2]));
    }

    function decode_dec_entity($m)
    {
        return $this->decode_num_entity($m[1], intval($m[2]));
    }

    function decode_num_entity($orig_type, $d)
    {
        if ($d < 0) {
            $d = 32;
        } // space
          // don't mess with huigh chars
        if ($this->not_control_caracter($d)) {
            if ($orig_type == '%') {
                return '%' . dechex($d);
            }
            if ($orig_type == '&') {
                return "&#$d;";
            }
        }
        return HtmlSpecialChars(chr($d));
    }

    function StripSingle($data)
    {
        return str_replace(array(
            '\\"',
            "\\0"
        ), array(
            '"',
            chr(0)
        ), $data);
    }
}
