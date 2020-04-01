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
namespace Pluf\Db;

use Pluf\Options;
use Exception;
use PDO;
use PDOException;
use Pluf_SQL;

/**
 * SQLite connection class
 */
class SQLiteEngine extends Engine
{

    public $con_id;

    public $engine = 'SQLite';

    function __construct(Options $options)
    {
        parent::__construct($options);

        $this->type_cast[Engine::COMPRESSED] = $this->type_cast['Compressed'] = array(
            '\Pluf\Db\SQLiteEngine::compressedFromDb',
            '\Pluf\Db\SQLiteEngine::compressedToDb'
        );
        $this->type_cast[Engine::GEOMETRY] = $this->type_cast['Compressed'] = array(
            '\Pluf\Db\SQLiteEngine::geometryFromDb',
            '\Pluf\Db\SQLiteEngine::geometryToDb'
        );

        // Connect and let the Exception be thrown in case of problem
        try {
            $this->con_id = new PDO('sqlite:' . $options->dbname);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Get the version of the SQLite library.
     *
     * @return string Version string
     */
    function getServerInfo()
    {
        return $this->con_id->getAttribute(PDO::ATTR_SERVER_INFO);
    }

    function close()
    {
        $this->con_id = null;
        return true;
    }

    function select($query)
    {
        if ($query instanceof Pluf_SQL) {
            $query = $query->gen();
        }
        if (false === ($cur = $this->con_id->query($query))) {
            throw new Exception($this->getError());
        }
        return $cur->fetchAll(PDO::FETCH_ASSOC);
    }

    public function execute($query)
    {
        $queryStr = $query;
        if ($query instanceof Pluf_SQL) {
            $queryStr = $query->gen($this);
        }
        if (false === ($cur = $this->con_id->exec($queryStr))) {
            throw new \Pluf\Exception($this->getError());
        }
        return $cur;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Db\Engine::getLastID()
     */
    public function getLastID(): int
    {
        return (int) $this->con_id->lastInsertId();
    }

    /**
     * Returns a string ready to be used in the exception.
     *
     * @return string Error string
     */
    function getError()
    {
        $err = $this->con_id->errorInfo();
        return implode(' - ', $err);
    }

    function esc($str)
    {
        if (is_array($str)) {
            $res = array();
            foreach ($str as $s) {
                $res[] = $this->con_id->quote($s);
            }
            return implode(', ', $res);
        }
        return $this->con_id->quote($str);
    }
    
    /**
     * {@inheritDoc}
     * @see \Pluf\Db\Engine::quote()
     */
    public function quote(string $string, int $parameter_type = null){
        return $this->con_id->qoute($string, $parameter_type);
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
        return '<Pluf_DB_SQLite(' . $this->con_id . ')>';
    }

    public static function compressedFromDb($val)
    {
        return ($val) ? gzinflate($val) : $val;
    }

    public static function compressedToDb($val, $con)
    {
        if (is_null($val)) {
            return 'NULL';
        }
        return 'X' . $con->esc(bin2hex(gzdeflate($val, 9)));
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Db\Engine::isLive()
     */
    public function isLive(): bool
    {
        return isset($this->con_id);
    }

    /**
     *
     * @param Object $val
     * @return string
     */
    public static function geometryFromDb($val)
    {
        return $val;
    }

    /**
     * Convert text to geometry
     *
     * @return string
     */
    public static function geometryToDb($val, $db)
    {
        return self::identityToDb($val, $db);
    }
}
