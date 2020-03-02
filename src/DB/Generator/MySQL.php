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
namespace Pluf\DB\Generator;

use Pluf\ModelUtils;
use Pluf\Bootstrap;
use Pluf\DB\Schema;
use Pluf\Model;

/**
 * Generator of the schemas corresponding to a given model.
 *
 * This class is for MySQL, you can create a class on the same
 * model for another database engine.
 */
class MySQL extends \Pluf\DB\Generator
{

    /**
     * Mapping of the fields.
     */
    public $mappings = array(
        'varchar' => 'varchar(%s)',
        'sequence' => 'mediumint(9) unsigned not null auto_increment',
        'boolean' => 'bool',
        'date' => 'date',
        'datetime' => 'datetime',
        'file' => 'varchar(250)',
        'manytomany' => null,
        'foreignkey' => 'mediumint(9) unsigned',
        'text' => 'longtext',
        'html' => 'longtext',
        'time' => 'time',
        'integer' => 'integer',
        'email' => 'varchar(150)',
        'password' => 'varchar(150)',
        'float' => 'numeric(%s, %s)',
        'blob' => 'blob',
        // GEO types
        'point' => 'POINT',
        'geometry' => 'GEOMETRY',
        'polygon' => 'POLYGON'
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
        'blob' => "''",

        // GEO types
        'point' => null,
        'geometry' => null,
        'polygon' => null
    );


    /**
     * Creates new instance
     *
     * @param Object $con
     */
    function __construct($con)
    {
        parent::__construct($con);
    }

    /**
     * Creates sql for DB
     *
     * @param Model $model
     * @return string[]
     */
    function getSqlCreate($model)
    {
        $tables = array();
        $cols = $model->_a['cols'];
        $manytomany = array();
        $sql = 'CREATE TABLE `' . $this->con->pfx . $model->_a['table'] . '` (';

        foreach ($cols as $col => $val) {
            $field = new $val['type']();
            if ($field->type != 'manytomany') {
                $sql .= "\n" . $this->con->qn($col) . ' ';
                $_tmp = $this->mappings[$field->type];
                if ($field->type == 'varchar') {
                    if (isset($val['size'])) {
                        $_tmp = sprintf($this->mappings['varchar'], $val['size']);
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
                    $_tmp = sprintf($this->mappings['float'], $val['max_digits'], $val['decimal_places']);
                }
                $sql .= $_tmp;
                if (empty($val['is_null'])) {
                    $sql .= ' NOT NULL';
                }
                if ($field->type != 'text' && $field->type != 'blob' && $field->type != 'geometry' && $field->type != 'polygon') {
                    if (isset($val['default'])) {
                        $sql .= ' default ';
                        $sql .= $model->_toDb($val['default'], $col);
                    } elseif ($field->type != 'sequence' && $field->type != 'point') {
                        $sql .= ' default ' . $this->defaults[$field->type];
                    }
                }
                $sql .= ',';
            } else {
                $manytomany[] = $col;
            }
        }
        $sql .= "\n" . 'PRIMARY KEY (`id`))';
        $engine = 'InnoDB';
        if (key_exists('engine', $model->_a)) {
            $engine = $model->_a['engine'];
        }
        $sql .= 'ENGINE=' . $engine . ' DEFAULT CHARSET=utf8;';
        $tables[$this->con->pfx . $model->_a['table']] = $sql;

        // Now for the many to many
        foreach ($manytomany as $many) {
            $omodel = new $cols[$many]['model']();
            $table = ModelUtils::getAssocTable($model, $omodel);

            $ra = ModelUtils::getAssocField($model);
            $rb = ModelUtils::getAssocField($omodel);

            $sql = 'CREATE TABLE `' . $table . '` (';
            $sql .= "\n" . $ra . ' ' . $this->mappings['foreignkey'] . ' default 0,';
            $sql .= "\n" . $rb . ' ' . $this->mappings['foreignkey'] . ' default 0,';
            $sql .= "\n" . 'PRIMARY KEY (' . $ra . ', ' . $rb . ')';
            $sql .= "\n" . ') ENGINE=InnoDB';
            $sql .= ' DEFAULT CHARSET=utf8;';
            $tables[$table] = $sql;
        }
        return $tables;
    }

    /**
     * دستور معادل برای ایجاد اندیس‌ها را تولید می‌کند.
     *
     * @param
     *            Object Model
     * @return array Array of SQL strings ready to execute.
     */
    function getSqlIndexes($model)
    {
        $index = array();
        foreach ($model->_a['idx'] as $idx => $val) {
            if (! isset($val['col'])) {
                $val['col'] = $idx;
            }
            $type = '';
            if (isset($val['type']) && strcasecmp($val['type'], 'normal') != 0) {
                $type = $val['type'];
            }
            $index[$this->con->pfx . $model->_a['table'] . '_' . $idx] = sprintf('CREATE %s INDEX `%s` ON `%s` (%s);', $type, $idx, $this->con->pfx . $model->_a['table'], Schema::quoteColumn($val['col'], $this->con));
        }
        foreach ($model->_a['cols'] as $col => $val) {
            $field = new $val['type']();
            if ($field->type == 'foreignkey') {
                $index[$this->con->pfx . $model->_a['table'] . '_' . $col . '_foreignkey'] = sprintf('CREATE INDEX `%s` ON `%s` (`%s`);', $col . '_foreignkey_idx', $this->con->pfx . $model->_a['table'], $col);
            }
            if (isset($val['unique']) and $val['unique'] == true) {
                // Add tenant column to index if config and table are multitenant.
                $columns = (Bootstrap::f('multitenant', false) && $model->_a['multitenant']) ? 'tenant,' . $col : $col;
                $index[$this->con->pfx . $model->_a['table'] . '_' . $col . '_unique'] = sprintf('CREATE UNIQUE INDEX `%s` ON `%s` (%s);', $col . '_unique_idx', $this->con->pfx . $model->_a['table'], Schema::quoteColumn($columns, $this->con));
            }
        }
        return $index;
    }

    /**
     * Workaround for <http://bugs.mysql.com/bug.php?id=13942> which limits the
     * length of foreign key identifiers to 64 characters.
     *
     * @param
     *            string
     * @return string
     */
    function getShortenedFKeyName($name)
    {
        if (strlen($name) <= 64) {
            return $name;
        }
        return substr($name, 0, 55) . '_' . substr(md5($name), 0, 8);
    }

    /**
     * Get the SQL to create the constraints for the given model
     *
     * @param
     *            Object Model
     * @return array Array of SQL strings ready to execute.
     */
    function getSqlCreateConstraints($model)
    {
        $table = $this->con->pfx . $model->_a['table'];
        $constraints = array();
        $alter_tbl = 'ALTER TABLE ' . $table;
        $cols = $model->_a['cols'];
        $manytomany = array();

        foreach ($cols as $col => $val) {
            $field = new $val['type']();
            // remember these for later
            if ($field->type == 'manytomany') {
                $manytomany[] = $col;
            }
            if ($field->type == 'foreignkey') {
                // Add the foreignkey constraints
                $referto = new $val['model']();
                $constraints[] = $alter_tbl . ' ADD CONSTRAINT ' . $this->getShortenedFKeyName($table . '_' . $col . '_fkey') . '
                    FOREIGN KEY (' . $this->con->qn($col) . ')
                    REFERENCES ' . $this->con->pfx . $referto->_a['table'] . ' (id)
                    ON DELETE NO ACTION ON UPDATE NO ACTION';
            }
        }

        // Now for the many to many
        foreach ($manytomany as $many) {
            $omodel = new $cols[$many]['model']();
            $table = ModelUtils::getAssocTable($model, $omodel);

            $alter_tbl = 'ALTER TABLE ' . $table;
            $constraints[] = $alter_tbl . ' ADD CONSTRAINT ' . $this->getShortenedFKeyName($table . '_fkey1') . '
                FOREIGN KEY (' . strtolower($model->_a['model']) . '_id)
                REFERENCES ' . $this->con->pfx . $model->_a['table'] . ' (id)
                ON DELETE NO ACTION ON UPDATE NO ACTION';
            $constraints[] = $alter_tbl . ' ADD CONSTRAINT ' . $this->getShortenedFKeyName($table . '_fkey2') . '
                FOREIGN KEY (' . strtolower($omodel->_a['model']) . '_id)
                REFERENCES ' . $this->con->pfx . $omodel->_a['table'] . ' (id)
                ON DELETE NO ACTION ON UPDATE NO ACTION';
        }
        return $constraints;
    }

    /**
     * Get the SQL to drop the tables corresponding to the model.
     *
     * @param
     *            Object Model
     * @return string SQL string ready to execute.
     */
    function getSqlDelete($model)
    {
        $cols = $model->_a['cols'];
        $manytomany = array();
        $sql = 'DROP TABLE IF EXISTS `' . $this->con->pfx . $model->_a['table'] . '`';

        foreach ($cols as $col => $val) {
            $field = new $val['type']();
            if ($field->type == 'manytomany') {
                $manytomany[] = $col;
            }
        }

        // Now for the many to many
        foreach ($manytomany as $many) {
            $omodel = new $cols[$many]['model']();
            $table = ModelUtils::getAssocTable($model, $omodel);
            $sql .= ', `' . $table . '`';
        }
        return array(
            $sql
        );
    }

    /**
     * Get the SQL to drop the constraints for the given model
     *
     * @param
     *            Object Model
     * @return array Array of SQL strings ready to execute.
     */
    function getSqlDeleteConstraints($model)
    {
        $table = $this->con->pfx . $model->_a['table'];
        $constraints = array();
        $alter_tbl = 'ALTER TABLE ' . $table;
        $cols = $model->_a['cols'];
        $manytomany = array();

        foreach ($cols as $col => $val) {
            $field = new $val['type']();
            // remember these for later
            if ($field->type == 'manytomany') {
                $manytomany[] = $col;
            }
            if ($field->type == 'foreignkey') {
                // Add the foreignkey constraints
                // $referto = new $val['model']();
                $constraints[] = $alter_tbl . ' DROP CONSTRAINT ' . $this->getShortenedFKeyName($table . '_' . $col . '_fkey');
            }
        }

        // Now for the many to many
        foreach ($manytomany as $many) {
            $omodel = new $cols[$many]['model']();
            $table = ModelUtils::getAssocTable($model, $omodel);
            $alter_tbl = 'ALTER TABLE ' . $table;
            $constraints[] = $alter_tbl . ' DROP CONSTRAINT ' . $this->getShortenedFKeyName($table . '_fkey1');
            $constraints[] = $alter_tbl . ' DROP CONSTRAINT ' . $this->getShortenedFKeyName($table . '_fkey2');
        }
        return $constraints;
    }
}
