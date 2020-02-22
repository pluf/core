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
namespace Pluf\DB;

use Pluf\Log;
use Pluf\DB;
use Pluf\Bootstrap;

/**
 * Database statistics class.
 *
 * This class is just a wrapper which will pass the queries to the
 * underlying database class but keeping timing information. This is
 * very good to track your slow queries and improve your code.
 */
class Stats
{

    /**
     * The real database connection.
     */
    protected $_rdb = null;

    public function __construct($db)
    {
        $this->_rdb = $db;
    }

    public function __call($name, $args)
    {
        if (! in_array($name, array(
            'execute',
            'select'
        ))) {
            return call_user_func_array(array(
                $this->_rdb,
                $name
            ), $args);
        }
        Log::stime('timer');
        $res = call_user_func_array(array(
            $this->_rdb,
            $name
        ), $args);
        Log::perf(array(
            'Pluf_DB_Stats',
            $this->_rdb->lastquery,
            Log::etime('timer', 'total_sql')
        ));
        Log::inc('sql_query');
        return $res;
    }

    public function __get($name)
    {
        return $this->_rdb->$name;
    }

    public function __set($name, $value)
    {
        return $this->_rdb->$name = $value;
    }
}

function Pluf_DB_Stats_getConnection($extra = null)
{
    if (isset($GLOBALS['_PX_db']) && (is_resource($GLOBALS['_PX_db']->con_id) or is_object($GLOBALS['_PX_db']->con_id))) {
        return $GLOBALS['_PX_db'];
    }
    $GLOBALS['_PX_db'] = new Stats(DB::get(Bootstrap::f('db_engine'), Bootstrap::f('db_server'), Bootstrap::f('db_database'), Bootstrap::f('db_login'), Bootstrap::f('db_password'), Bootstrap::f('db_table_prefix'), Bootstrap::f('debug'), Bootstrap::f('db_version')));
    return $GLOBALS['_PX_db'];
}
