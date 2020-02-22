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
namespace Pluf\DB\Introspect;

class PostgreSQL
{

    protected $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Get the list of tables in the current database.
     * The search
     * automatically limit the list to the visible ones.
     *
     * @param
     *            object DB connection.
     * @return array List of tables.
     */
    function listTables()
    {
        $sql = 'SELECT c.relname AS name
         FROM pg_catalog.pg_class c
         LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
         WHERE c.relkind IN (\'r\', \'v\', \'\')
             AND n.nspname NOT IN (\'pg_catalog\', \'pg_toast\')
             AND pg_catalog.pg_table_is_visible(c.oid)';
        $res = $this->db->select($sql);
        $tables = array();
        foreach ($res as $t) {
            $tables[] = $t['name'];
        }
        return $tables;
    }
}