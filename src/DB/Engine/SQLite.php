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
namespace Pluf\DB\Engine;

use PDO;
use Pluf\Exception;

/**
 * SQLite connection class
 */
class SQLite extends \Pluf\DB\Engine
{

    function __construct($user, $pwd, $server, $dbname, $pfx = '', $debug = false)
    {
        parent::__construct($pfx, $debug);
        $this->debug('* SQLITE OPEN');
        $this->type_cast['\Pluf\DB\Field\Compressed'] = array(
            '\Pluf\DB::compressedFromDb',
            '\Pluf\DB\SQLite::ompressedToDb'
        );
        // Connect and let the Exception be thrown in case of problem
        try {
            $this->con_id = new \PDO('sqlite:' . $dbname);
        } catch (\PDOException $e) {
            throw new Exception('Fail to open database connection', 5000, $e);
        }
    }

    /**
     * Get the version of the SQLite library.
     *
     * @return string Version string
     */
    public function getServerInfo()
    {
        return $this->con_id->getAttribute(PDO::ATTR_SERVER_INFO);
    }

    public function close()
    {
        $this->con_id = null;
        return true;
    }

    public function select($query)
    {
        $this->debug($query);
        if (false === ($cur = $this->con_id->query($query))) {
            throw new Exception($this->getError());
        }
        return $cur->fetchAll(PDO::FETCH_ASSOC);
    }

    public function execute($query)
    {
        $this->debug($query);
        if (false === ($cur = $this->con_id->exec($query))) {
            throw new Exception($this->getError());
        }
        return $cur;
    }

    public function getLastID()
    {
        $this->debug('* GET LAST ID');
        return (int) $this->con_id->lastInsertId();
        ;
    }

    /**
     * Returns a string ready to be used in the exception.
     *
     * @return string Error string
     */
    public function getError()
    {
        $err = $this->con_id->errorInfo();
        $err[] = $this->lastquery;
        return implode(' - ', $err);
    }

    public function esc($str)
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
     * Quote the column name.
     *
     * @param
     *            string Name of the column
     * @return string Escaped name
     */
    public function qn($col)
    {
        return '"' . $col . '"';
    }

    /**
     * Start a transaction.
     */
    public function begin()
    {
        $this->execute('BEGIN');
    }

    /**
     * Commit a transaction.
     */
    public function commit()
    {
        $this->execute('COMMIT');
    }

    /**
     * Rollback a transaction.
     */
    public function rollback()
    {
        $this->execute('ROLLBACK');
    }

    public static function CompressedToDb($val, $con)
    {
        if (is_null($val)) {
            return 'NULL';
        }
        return 'X' . $con->esc(bin2hex(gzdeflate($val, 9)));
    }
}

