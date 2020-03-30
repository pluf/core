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
 * Generate the WHERE SQL clause in an easy and SQL proof way.
 */
class Pluf_SQL
{

    protected $db;

    protected $where = '';

    public $ands = array();

    /**
     * Construct the constructor with a default condition.
     */
    function __construct($base = '', $args = array())
    {
        $this->db = Pluf::db();
        if (strlen($base) > 0) {
            $this->Q($base, $args);
        }
    }

    /**
     * Returns the where clause.
     *
     * @return string Where clause without the WHERE
     */
    function gen()
    {
        return implode(' AND ', $this->ands);
    }

    /**
     * Add a condition.
     *
     * @param
     *            string String to 'interpolate'
     * @param
     *            mixed String or array of parameters (array())
     */
    function Q($base, $args = array())
    {
        $escaped = array();
        if (! is_array($args)) {
            $args = array(
                $args
            );
        }
        foreach ($args as $arg) {
            $escaped[] = $this->db->esc($arg);
        }
        $this->ands[] = vsprintf($base, $escaped);
        return $this;
    }

    /**
     * Add another SQL as a AND.
     *
     * @param
     *            Pluf_SQL Other object to add to the current.
     */
    function SAnd($sql)
    {
        return $this->SDef($sql);
    }

    /**
     * Add another SQL as a OR
     *
     * @param
     *            Pluf_SQL Other object to add to the current.
     */
    function SOr($sql)
    {
        return $this->SDef($sql, 'OR');
    }

    /**
     * Add another SQL to the current
     *
     * @param
     *            Pluf_SQL Other object to add to the current.
     * @param
     *            string Type of addition
     */
    function SDef($sql, $k = 'AND')
    {
        if (empty($this->ands)) {
            $this->ands = $sql->ands;
        } else {
            $othersql = $sql->gen();
            $current = $this->gen();
            if (strlen($othersql)) {
                $this->ands = array();
                $this->ands[] = '(' . $current . ') ' . $k . ' (' . $othersql . ')';
            }
        }
        return $this;
    }

    /**
     * Get keywords.
     *
     * Considering a query string, explode the query string in
     * keywords given a defined delimiter.
     *
     * @param
     *            string Query string
     * @param
     *            string delimiter (' ')
     * @return array Array of keywords
     */
    function keywords($string, $del = ' ')
    {
        $keys = array();
        $args = explode($del, $string);
        foreach ($args as $arg) {
            $arg = trim($arg);
            if (strlen($arg) > 0) {
                $keys[] = $arg;
            }
        }
        return $keys;
    }

    public static function cleanString(string $str): string
    {
        return str_replace('%', '%%', $str);
        ;
    }
}

