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
 * PostgreSQL connection class
 */
class Pluf_DB_PostgreSQL
{

    /**
     * The connection resource.
     */
    public $con_id;

    /**
     * The prefix for the table names.
     */
    public $pfx = '';

    /**
     * Debug mode.
     */
    private $debug = false;

    /**
     * The last query, set with debug().
     * Used when an error is
     * returned.
     */
    public $lastquery = '';

    /**
     * Name of the engine.
     */
    public $engine = 'PostgreSQL';

    /**
     * Used by the model to convert the values from and to the
     * database.
     *
     * @see Pluf_DB
     */
    public $type_cast = array();

    /**
     * Current query cursor.
     */
    private $cur = null;

    /**
     * Current search path.
     */
    public $search_path = 'public';

    function __construct($user, $pwd, $server, $dbname, $pfx = '', $debug = false)
    {
        Pluf::loadFunction('Pluf_DB_defaultTypecast');
        $this->type_cast = Pluf_DB_defaultTypecast();
        $this->type_cast['Pluf_DB_Field_Boolean'] = array(
            'Pluf_DB_PostgreSQL_BooleanFromDb',
            'Pluf_DB_BooleanToDb'
        );
        $this->type_cast['Pluf_DB_Field_Compressed'] = array(
            'Pluf_DB_PostgreSQL_CompressedFromDb',
            'Pluf_DB_PostgreSQL_CompressedToDb'
        );

        $this->debug('* POSTGRESQL CONNECT');
        $cstring = '';
        if ($server) {
            $cstring .= 'host=' . $server . ' ';
        }
        $cstring .= 'dbname=' . $dbname . ' user=' . $user;
        if ($pwd) {
            $cstring .= ' password=' . $pwd;
        }
        $this->debug = $debug;
        $this->pfx = $pfx;
        $this->cur = null;
        $this->con_id = @pg_connect($cstring);
        if (! $this->con_id) {
            throw new Exception($this->getError());
        }
    }

    /**
     * Get the version of the PostgreSQL server.
     *
     * Requires PostgreSQL 7.4 or later.
     *
     * @return string Version string
     */
    function getServerInfo()
    {
        $ver = pg_version($this->con_id);
        return $ver['server'];
    }

    /**
     * Log the queries.
     * Keep track of the last query and if in debug mode
     * keep track of all the queries in
     * $GLOBALS['_PX_debug_data']['sql_queries']
     *
     * @param
     *            string Query to keep track
     * @return bool true
     */
    function debug($query)
    {
        $this->lastquery = $query;
        if (! $this->debug)
            return true;
        if (! isset($GLOBALS['_PX_debug_data']['sql_queries']))
            $GLOBALS['_PX_debug_data']['sql_queries'] = array();
        $GLOBALS['_PX_debug_data']['sql_queries'][] = $query;
        return true;
    }

    function close()
    {
        if ($this->con_id) {
            pg_close($this->con_id);
            return true;
        } else {
            return false;
        }
    }

    function select($query)
    {
        $this->debug($query);
        $this->cur = @pg_query($this->con_id, $query);
        if (! $this->cur) {
            throw new Exception($this->getError());
        }
        $res = array();
        while ($row = pg_fetch_assoc($this->cur)) {
            $res[] = $row;
        }
        @pg_free_result($this->cur);
        $this->cur = null;
        return $res;
    }

    function execute($query)
    {
        $this->debug($query);
        $this->cur = @pg_query($this->con_id, $query);
        if (! $this->cur) {
            throw new Exception($this->getError());
        }
        return true;
    }

    function getLastID()
    {
        $this->debug('* GET LAST ID');
        $res = $this->select('SELECT lastval() AS last_id');
        return (int) $res[0]['last_id'];
    }

    /**
     * Returns a string ready to be used in the exception.
     *
     * @return string Error string
     */
    function getError()
    {
        if ($this->cur) {
            return pg_result_error($this->cur) . ' - ' . $this->lastquery;
        }
        if ($this->con_id) {
            return pg_last_error($this->con_id) . ' - ' . $this->lastquery;
        } else {
            return pg_last_error() . ' - ' . $this->lastquery;
        }
    }

    function esc($str)
    {
        if (is_array($str)) {
            $res = array();
            foreach ($str as $s) {
                $res[] = '\'' . pg_escape_string($this->con_id, $s) . '\'';
            }
            return implode(', ', $res);
        }
        return '\'' . pg_escape_string($this->con_id, $str) . '\'';
    }

    /**
     * Set the current search path.
     */
    function setSearchPath($search_path = 'public')
    {
        if (preg_match('/[^\w\s\,]/', $search_path)) {
            throw new Exception('The search path: "' . $search_path . '" is not valid.');
        }
        $this->execute('SET search_path TO ' . $search_path);
        $this->search_path = $search_path;
        return true;
    }

    /**
     * Quote the column name.
     *
     * @param
     *            string Name of the column
     * @return string Escaped name
     */
    function qn($col)
    {
        return '"' . $col . '"';
    }

    /**
     * Start a transaction.
     */
    function begin()
    {
        $this->execute('BEGIN');
    }

    /**
     * Commit a transaction.
     */
    function commit()
    {
        $this->execute('COMMIT');
    }

    /**
     * Rollback a transaction.
     */
    function rollback()
    {
        $this->execute('ROLLBACK');
    }

    function __toString()
    {
        return '<Pluf_DB_PostgreSQL(' . $this->con_id . ')>';
    }
}

function Pluf_DB_PostgreSQL_BooleanFromDb($val)
{
    if (! $val) {
        return false;
    }
    return (strtolower(substr($val, 0, 1)) == 't');
}

function Pluf_DB_PostgreSQL_CompressedToDb($val, $con)
{
    if (is_null($val)) {
        return 'NULL';
    }
    return "'" . pg_escape_bytea(gzdeflate($val, 9)) . "'";
}

function Pluf_DB_PostgreSQL_CompressedFromDb($val)
{
    return ($val) ? gzinflate(pg_unescape_bytea($val)) : $val;
}

