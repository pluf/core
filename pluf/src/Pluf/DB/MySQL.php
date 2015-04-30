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
 * MySQL connection class
 */
class Pluf_DB_MySQL
{
    public $con_id;
    public $pfx = '';
    private $debug = false;
    /** The last query, set with debug(). Used when an error is returned. */
    public $lastquery = '';
    public $engine = 'MySQL';
    public $type_cast = array();

    function __construct($user, $pwd, $server, $dbname, $pfx='', $debug=false)
    {
        Pluf::loadFunction('Pluf_DB_defaultTypecast');
        $this->type_cast = Pluf_DB_defaultTypecast();
        $this->debug('* MYSQL CONNECT');
        $this->con_id = mysqli_connect($server, $user, $pwd);
        $this->debug = $debug;
        $this->pfx = $pfx;
        if (!$this->con_id) {
            throw new Exception($this->getError());
        }
        $this->database($dbname);
        $this->execute('SET NAMES \'utf8\'');
    }

    function database($dbname)
    {
        $db = mysqli_select_db($this->con_id, $dbname);
        $this->debug('* USE DATABASE '.$dbname);
        if (!$db) {
            throw new Exception($this->getError());
        }
        return true;
    }

    /**
     * Get the version of the MySQL server.
     *
     * @return string Version string
     */
    function getServerInfo()
    {
        return mysqli_get_server_info($this->con_id);
    }

    /**
     * Log the queries. Keep track of the last query and if in debug mode
     * keep track of all the queries in 
     * $GLOBALS['_PX_debug_data']['sql_queries']
     *
     * @param string Query to keep track
     * @return bool true
     */
    function debug($query)
    {
        $this->lastquery = $query;
        if (!$this->debug) return true;
        if (!isset($GLOBALS['_PX_debug_data']['sql_queries'])) 
            $GLOBALS['_PX_debug_data']['sql_queries'] = array();
        $GLOBALS['_PX_debug_data']['sql_queries'][] = $query;
        return true;
    }

    function close()
    {
        if ($this->con_id) {
            mysqli_close($this->con_id);
            return true;
        } else {
            return false;
        }
    }

    function select($query)
    {
        $this->debug($query);
        $cur = mysqli_query($this->con_id,$query);
        if ($cur) {
            $res = array();
            while ($row = mysqli_fetch_assoc($cur)) {
                $res[] = $row;
            }
            mysqli_free_result($cur);
            return $res;
        } else {
            throw new Exception($this->getError());
        }
    }

    function execute($query)
    {
        $this->debug($query);
        $cur = mysqli_query($this->con_id, $query);
        if (!$cur) {
            throw new Exception($this->getError());
        } else {
            return true;
        }
    }

    function getLastID()
    {
        $this->debug('* GET LAST ID');
        return (int) mysqli_insert_id($this->con_id);
    }

    /**
     * Returns a string ready to be used in the exception.
     *
     * @return string Error string
     */
    function getError()
    {
        
        if ($this->con_id) {
            return mysqli_errno($this->con_id).' - '
                .mysqli_error($this->con_id).' - '.$this->lastquery;
        } else {
            return mysqli_errno().' - '
                .mysqli_error().' - '.$this->lastquery;
        }
    }

    function esc($str)
    {
        return '\''.mysqli_real_escape_string( $this->con_id, $str).'\'';
    }

    /**
     * Quote the column name.
     *
     * @param string Name of the column
     * @return string Escaped name
     */
    function qn($col)
    {
        return '`'.$col.'`';
    }

    /**
     * Start a transaction.
     */
    function begin()
    {
        if (Pluf::f('db_mysql_transaction', false)) {
            $this->execute('BEGIN');
        }
    }

    /**
     * Commit a transaction.
     */
    function commit()
    {
        if (Pluf::f('db_mysql_transaction', false)) {
            $this->execute('COMMIT');
        }
    }

    /**
     * Rollback a transaction.
     */
    function rollback()
    {
        if (Pluf::f('db_mysql_transaction', false)) {
            $this->execute('ROLLBACK');
        }
    }

    function __toString()
    {
        return '<Pluf_DB_MySQL('.$this->con_id.')>';
    }

}

