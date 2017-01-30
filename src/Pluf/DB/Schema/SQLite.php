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
 * Generator of the schemas corresponding to a given model.
 *
 * This class is for SQLite, you can create a class on the same
 * model for another database engine.
 */
class Pluf_DB_Schema_SQLite
{

    /**
     * Mapping of the fields.
     */
    public $mappings = array(
            'varchar' => 'varchar(%s)',
            'sequence' => 'integer primary key autoincrement',
            'boolean' => 'bool',
            'date' => 'date',
            'datetime' => 'datetime',
            'file' => 'varchar(150)',
            'manytomany' => null,
            'foreignkey' => 'integer',
            'text' => 'text',
            'html' => 'text',
            'time' => 'time',
            'integer' => 'integer',
            'email' => 'varchar(150)',
            'password' => 'varchar(150)',
            'float' => 'real',
            'blob' => 'blob'
    );

    public $defaults = array(
            'varchar' => "''",
            'sequence' => null,
            'boolean' => 1,
            'date' => 0,
            'datetime' => 0,
            'file' => "''",
            'manytomany' => null,
            'foreignkey' => 0,
            'text' => "''",
            'html' => "''",
            'time' => 0,
            'integer' => 0,
            'email' => "''",
            'password' => "''",
            'float' => 0.0,
            'blob' => "''"
    );

    private $con = null;

    function __construct ($con)
    {
        $this->con = $con;
    }

    /**
     * Get the SQL to generate the tables of the given model.
     *
     * @param
     *            Object Model
     * @return array Array of SQL strings ready to execute.
     */
    function getSqlCreate ($model)
    {
        $tables = array();
        $cols = $model->_a['cols'];
        $manytomany = array();
        $query = 'CREATE TABLE ' . $this->con->pfx . $model->_a['table'] . ' (';
        $sql_col = array();
        foreach ($cols as $col => $val) {
            $field = new $val['type']();
            if ($field->type != 'manytomany') {
                $sql = $this->con->qn($col) . ' ';
                $_tmp = $this->mappings[$field->type];
                if ($field->type == 'varchar') {
                    if (isset($val['size'])) {
                        $_tmp = sprintf($this->mappings['varchar'], 
                                $val['size']);
                    } else {
                        $_tmp = sprintf($this->mappings['varchar'], '150');
                    }
                }
                if ($field->type == 'float') {
                    if (! isset($val['max_digits'])) {
                        $val['max_digits'] = 32;
                    }
                    if (! isset($val['decimal_places'])) {
                        $val['decimal_places'] = 8;
                    }
                    $_tmp = sprintf($this->mappings['float'], 
                            $val['max_digits'], $val['decimal_places']);
                }
                $sql .= $_tmp;
                if (empty($val['is_null'])) {
                    $sql .= ' not null';
                }
                if (isset($val['default'])) {
                    $sql .= ' default ' . $model->_toDb($val['default'], $col);
                } elseif ($field->type != 'sequence') {
                    $sql .= ' default ' . $this->defaults[$field->type];
                }
                $sql_col[] = $sql;
            } else {
                $manytomany[] = $col;
            }
        }
        $query = $query . "\n" . implode(",\n", $sql_col) . "\n" . ');';
        $tables[$this->con->pfx . $model->_a['table']] = $query;
        
        // Now for the many to many
        foreach ($manytomany as $many) {
            $omodel = new $cols[$many]['model']();
            $hay = array(
                    strtolower($model->_a['model']),
                    strtolower($omodel->_a['model'])
            );
            sort($hay);
            $table = $hay[0] . '_' . $hay[1] . '_assoc';
            $sql = 'CREATE TABLE ' . $this->con->pfx . $table . ' (';
            $sql .= "\n" . strtolower($model->_a['model']) . '_id ' .
                     $this->mappings['foreignkey'] . ' default 0,';
            $sql .= "\n" . strtolower($omodel->_a['model']) . '_id ' .
                     $this->mappings['foreignkey'] . ' default 0,';
            $sql .= "\n" . 'primary key (' . strtolower($model->_a['model']) .
                     '_id, ' . strtolower($omodel->_a['model']) . '_id)';
            $sql .= "\n" . ');';
            $tables[$this->con->pfx . $table] = $sql;
        }
        return $tables;
    }

    /**
     * SQLite cannot add foreign key constraints to already existing tables,
     * so we skip their creation completely.
     *
     * @param
     *            Object Model
     * @return array
     */
    function getSqlCreateConstraints ($model)
    {
        return array();
    }

    /**
     * Get the SQL to generate the indexes of the given model.
     *
     * @param
     *            Object Model
     * @return array Array of SQL strings ready to execute.
     */
    function getSqlIndexes ($model)
    {
        $index = array();
        foreach ($model->_a['idx'] as $idx => $val) {
            if (! isset($val['col'])) {
                $val['col'] = $idx;
            }
            $unique = (isset($val['type']) && ($val['type'] == 'unique')) ? 'UNIQUE ' : '';
            $index[$this->con->pfx . $model->_a['table'] . '_' . $idx] = sprintf(
                    'CREATE %sINDEX %s ON %s (%s);', $unique, 
                    $this->con->pfx . $model->_a['table'] . '_' . $idx, 
                    $this->con->pfx . $model->_a['table'], 
                    Pluf_DB_Schema::quoteColumn($val['col'], $this->con));
        }
        foreach ($model->_a['cols'] as $col => $val) {
            $field = new $val['type']();
            if ($field->type == 'foreignkey') {
                $index[$this->con->pfx . $model->_a['table'] . '_' . $col .
                         '_foreignkey'] = sprintf('CREATE INDEX %s ON %s (%s);', 
                                $this->con->pfx . $model->_a['table'] . '_' .
                                 $col . '_foreignkey_idx', 
                                $this->con->pfx . $model->_a['table'], 
                                Pluf_DB_Schema::quoteColumn($col, $this->con));
            }
            if (isset($val['unique']) and $val['unique'] == true) {
                $index[$this->con->pfx . $model->_a['table'] . '_' . $col .
                         '_unique'] = sprintf(
                                'CREATE UNIQUE INDEX %s ON %s (%s);', 
                                $this->con->pfx . $model->_a['table'] . '_' .
                                 $col . '_unique_idx', 
                                $this->con->pfx . $model->_a['table'], 
                                Pluf_DB_Schema::quoteColumn($col, $this->con));
            }
        }
        return $index;
    }

    /**
     * Get the SQL to drop the tables corresponding to the model.
     *
     * @param
     *            Object Model
     * @return string SQL string ready to execute.
     */
    function getSqlDelete ($model)
    {
        $cols = $model->_a['cols'];
        $manytomany = array();
        $sql = array();
        $sql[] = 'DROP TABLE IF EXISTS ' . $this->con->pfx . $model->_a['table'];
        foreach ($cols as $col => $val) {
            $field = new $val['type']();
            if ($field->type == 'manytomany') {
                $manytomany[] = $col;
            }
        }
        
        // Now for the many to many
        foreach ($manytomany as $many) {
            $omodel = new $cols[$many]['model']();
            $hay = array(
                    strtolower($model->_a['model']),
                    strtolower($omodel->_a['model'])
            );
            sort($hay);
            $table = $hay[0] . '_' . $hay[1] . '_assoc';
            $sql[] = 'DROP TABLE IF EXISTS ' . $this->con->pfx . $table;
        }
        return $sql;
    }

    /**
     * SQLite cannot drop foreign keys from existing tables,
     * so we skip their deletion completely.
     *
     * @param
     *            Object Model
     * @return array
     */
    function getSqlDeleteConstraints ($model)
    {
        return array();
    }
}

