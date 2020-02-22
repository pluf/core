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
 * MySQL connection
 */
class Pluf_DB_MySQL
{

    public $con_id;

    public $pfx = '';

    private $debug = false;

    /**
     * اخرین کاوشی که اجرا می‌شود در این متغییر ذخیره می‌شود.
     * این کار در
     * رفع خطا و یا گزارش خطا بسیار مناسب است.
     */
    public $lastquery = '';

    public $engine = 'MySQL';

    public $type_cast = array();

    function __construct($user, $pwd, $server, $dbname, $pfx = '', $debug = false)
    {
        Pluf::loadFunction('Pluf_DB_defaultTypecast');
        $this->type_cast = Pluf_DB_defaultTypecast();
        $this->debug('* MYSQL CONNECT');
        $this->con_id = mysqli_connect($server, $user, $pwd);
        $this->debug = $debug;
        $this->pfx = $pfx;
        if (! $this->con_id) {
            $this->throwError();
        }
        $this->database($dbname);
        $this->execute('SET NAMES \'utf8\'');
    }

    function database($dbname)
    {
        $db = mysqli_select_db($this->con_id, $dbname);
        $this->debug('* USE DATABASE ' . $dbname);
        if (! $db) {
            $this->throwError();
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
            mysqli_close($this->con_id);
            return true;
        }
        return false;
    }

    function select($query)
    {
        $this->debug($query);
        $cur = mysqli_query($this->con_id, $query);
        if ($cur) {
            $res = array();
            while ($row = mysqli_fetch_assoc($cur)) {
                $res[] = $row;
            }
            mysqli_free_result($cur);
            return $res;
        }
        $this->throwError();
    }

    /**
     * run a query
     *
     * @param String $query
     * @return boolean true if is success
     */
    function execute($query)
    {
        $this->debug($query);
        $cur = mysqli_query($this->con_id, $query);
        if (! $cur) {
            $this->throwError();
        }
        return true;
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
        $message = "";
        if ($this->con_id) {
            $message = mysqli_error($this->con_id);
        } else {
            return mysqli_error();
        }
        if (Pluf::f('debug', false)) {
            $message = $message . ' - ' . $this->lastquery;
        }
        return $message;
    }

    /**
     * شماره خطای ایجاد شده را تعیین می‌کند.
     */
    function getErrorNumber()
    {
        if ($this->con_id) {
            return mysqli_errno($this->con_id);
        } else {
            return mysqli_errno();
        }
    }

    /**
     * خطای مناسب با حالت سیستم ایجاد می‌کند.
     *
     * @throws Exception
     */
    function throwError()
    {
        $errorno = $this->getErrorNumber();
        switch ($errorno) {
            case 1062:
                throw new Exception($this->getError(), 4101, null, 400);
            case 1064:
                throw new Exception($this->getError(), 4102, null, 400);
            default:
                throw new Exception($this->getError(), 4000, null, 400);
        }
    }

    function esc($str)
    {
        if (is_array($str)) {
            $res = array();
            foreach ($str as $s) {
                $res[] = '\'' . mysqli_real_escape_string($this->con_id, $s) . '\'';
            }
            return implode(', ', $res);
        }
        return '\'' . mysqli_real_escape_string($this->con_id, $str) . '\'';
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
        return '`' . $col . '`';
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
        return '<Pluf_DB_MySQL(' . $this->con_id . ')>';
    }
}
