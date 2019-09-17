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
 * Create the schema of a given Pluf_Model for a given database.
 */
class Pluf_DB_Schema
{
    /**
     * Database connection object.
     */
    private $con = null;

    /**
     * Model from which the schema is generated.
     */
    public $model = null;

    /**
     * Schema generator object corresponding to the database.
     */
    public $schema = null;

    function __construct($db, $model=null)
    {
        $this->con = $db;
        $this->model = $model;
        $this->schema = Pluf::factory('Pluf_DB_Schema_'.$db->engine, $db);
    }


    /**
     * Get the schema generator.
     *
     * @return object Pluf_DB_Schema_XXXX
     */
    function getGenerator()
    {
        return $this->schema;
    }

    /**
     * Create the tables and indexes for the current model.
     *
     * @return mixed True if success or database error.
     */
    function createTables()
    {
        $sql = $this->schema->getSqlCreate($this->model);
        foreach ($sql as $query) {
            if (false === $this->con->execute($query)) {
                throw new Pluf_Exception($this->con->getError());
            }
        }
        $sql = $this->schema->getSqlIndexes($this->model);
        foreach ($sql as $query) {
            if (false === $this->con->execute($query)) {
                throw new Pluf_Exception($this->con->getError());
            }
        }
        return true;
    }

    /**
     * Creates the constraints for the current model.
     * This should be done _after_ all tables of all models have been created.
     *
     * @throws Exception
     */
    function createConstraints()
    {
        $sql = $this->schema->getSqlCreateConstraints($this->model);
        foreach ($sql as $k => $query) {
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
    function dropTables()
    {
        $sql = $this->schema->getSqlDelete($this->model);
        foreach ($sql as $k => $query) {
            if (false === $this->con->execute($query)) {
                throw new Exception($this->con->getError());
            }
        }
        return true;
    }

    /**
     * Drops the constraints for the current model.
     * This should be done _before_ all tables of all models are dropped.
     *
     * @throws Exception
     * @return boolean
     */
    function dropConstraints()
    {
        $sql = $this->schema->getSqlDeleteConstraints($this->model);
        foreach ($sql as $k => $query) {
            if (false === $this->con->execute($query)) {
                throw new Exception($this->con->getError());
            }
        }
        return true;
    }

    /**
     * Given a column name or a string with column names in the format
     * "column1, column2, column3", returns the escaped correctly
     * quoted column names. This is good for index creation.
     *
     * @param string Column
     * @param Pluf_DB DB handler
     * @return string Quoted for the DB column(s)
     */
    public static function quoteColumn($col, $db)
    {
        if (false !== strpos($col, ',')) {
            $cols = explode(',', $col);
        } else {
            $cols = array($col);
        }
        $res = array();
        foreach ($cols as $col) {
            $res[] = $db->qn(trim($col));
        }
        return implode(', ', $res);
    }
}
