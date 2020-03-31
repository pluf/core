<?php
namespace Pluf\Db;

use Pluf;
use Pluf\Options;


class MySQLEngine extends Engine
{

    public $con_id;

    public $lastquery = '';

    public $engine = 'MySQL';

    /**
     * 
     * 
     * 
     * 
     * @param Options $options
     */
    function __construct(Options $options)
    {
        parent::__construct($options);
        $this->con_id = mysqli_connect(
            $options->server,         // [optional]
            $options->login,          // [optional]
            $options->password,       // [optional]
            $options->port,           // [optional]
            $options->socket          // [optional]
            );
        if (! $this->con_id) {
            $this->throwError();
        }
        $this->database($options->database);
        $this->execute('SET NAMES \'utf8\'');
    }

    function database($dbname)
    {
        $db = mysqli_select_db($this->con_id, $dbname);
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

    function close()
    {
        if ($this->con_id) {
            mysqli_close($this->con_id);
            return true;
        }
        return false;
    }

    function select($queryObj)
    {
        $query = $queryObj;
        $myModel = 'Pluf_SQL';
        if ($queryObj instanceof $myModel) {
            $query = $queryObj->gen($this);
        }
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
    function execute($queryObj)
    {
        $query = $queryObj;
        $myModel = 'Pluf_SQL';
        if ($queryObj instanceof $myModel) {
            $query = $queryObj->gen($this);
        }
        $cur = mysqli_query($this->con_id, $query);
        if (! $cur) {
            $this->throwError();
        }
        return true;
    }

    function getLastID() : int
    {
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
            $message = mysqli_error();
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
     * @throws \Pluf\Exception
     */
    function throwError()
    {
        $errorno = $this->getErrorNumber();
        $message = $this->getError();
        throw new \Pluf\Exception($message, $errorno);
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
    public function isLive(): bool
    {
        return true;
    }

}

