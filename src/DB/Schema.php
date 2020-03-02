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

use Pluf\Exception;
use Pluf\Model;
use Pluf\ModelUtils;

/**
 * Create the schema of a given Model for a given database.
 */
class Schema
{

    /**
     * Database connection object.
     */
    private ?Engine $con = null;

    /**
     * Model from which the schema is generated.
     */
    public ?Model $model = null;

    /**
     * Schema generator object corresponding to the database.
     */
    public ?Generator $schema = null;

    function __construct(Engine $db, ?Model $model = null)
    {
        $this->con = $db;
        $this->model = $model;
        // create engine
        $generatorName = '\\Pluf\\DB\Generator\\' . $db->engine;
        $this->schema = new $generatorName($db);
    }

    /**
     * Get the schema generator.
     *
     * @return Generator of DB schema
     */
    public function getGenerator(): Generator
    {
        return $this->schema;
    }

    /**
     * Get the schema model.
     *
     * @return Generator of DB schema
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Create the tables and indexes for the current model.
     *
     * If the model is a mapped model ($model->_a['mapped'] == true) then only tables for its
     * many to many relations will be created and table for the model will not be created.
     *
     * A mapped model is a model which have not a separate table. In other word, a mapped model is
     * a specific view to another model and is not a real model.
     *
     * A mapped model may defines some new many to many relations which was not defined in the main model.
     *
     * @return mixed True if success or database error.
     */
    public function createTables(): void
    {
        $sql = $this->schema->getSqlCreate($this->model);
        // Note: hadi, 2019: If model is a mapped model, its table is created or will be created by a none mapped model.
        if ($this->model->_a['mapped']) {
            $modelTableName = ModelUtils::getTable($this->model);
            // remove sql to create main table
            $sql = array_diff_key($sql, array(
                $modelTableName => ''
            ));
        }
        foreach ($sql as $query) {
            if (false === $this->con->execute($query)) {
                throw new Exception($this->con->getError());
            }
        }
        if (! $this->model->_a['mapped']) {
            $sql = $this->schema->getSqlIndexes($this->model);
            foreach ($sql as $query) {
                if (false === $this->con->execute($query)) {
                    throw new Exception($this->con->getError());
                }
            }
        }
    }

    /**
     * Creates the constraints for the current model.
     * This should be done _after_ all tables of all models have been created.
     *
     * @throws Exception
     */
    public function createConstraints(): void
    {
        $sql = $this->schema->getSqlCreateConstraints($this->model);
        foreach ($sql as $query) {
            if (false === $this->con->execute($query)) {
                throw new Exception($this->con->getError());
            }
        }
    }

    /**
     * Drop the tables and indexes for the current model.
     *
     * @return mixed True if success or database error.
     */
    public function dropTables(): void
    {
        $sql = $this->schema->getSqlDelete($this->model);
        // Note: hadi, 2019: If model is a mapped model, its table is created or will be created by a none mapped model.
        if ($this->model->_a['mapped']) {
            $modelTableName = ModelUtils::getTable($this->model);
            // remove sql to create main table
            $sql = array_diff_key($sql, array(
                $modelTableName => ''
            ));
        }
        foreach ($sql as $query) {
            if (false === $this->con->execute($query)) {
                throw new Exception($this->con->getError());
            }
        }
    }

    /**
     * Drops the constraints for the current model.
     * This should be done _before_ all tables of all models are dropped.
     *
     * @throws Exception
     * @return boolean
     */
    public function dropConstraints(): void
    {
        $sql = $this->schema->getSqlDeleteConstraints($this->model);
        foreach ($sql as $query) {
            if (false === $this->con->execute($query)) {
                throw new Exception($this->con->getError());
            }
        }
    }

    /**
     * Given a column name or a string with column names in the format
     * "column1, column2, column3", returns the escaped correctly
     * quoted column names.
     * This is good for index creation.
     *
     * @param
     *            string Column
     * @param
     *            Pluf_DB DB handler
     * @return string Quoted for the DB column(s)
     */
    public static function quoteColumn($col, $db): string
    {
        if (false !== strpos($col, ',')) {
            $cols = explode(',', $col);
        } else {
            $cols = array(
                $col
            );
        }
        $res = array();
        foreach ($cols as $col) {
            $res[] = $db->qn(trim($col));
        }
        return implode(', ', $res);
    }
}
