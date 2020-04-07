<?php
namespace Pluf\Db;

use Pluf;

class PostgreSQLEngine extends Engine
{

    /**
     * The connection resource.
     */
    public $con_id;

    /**
     * The prefix for the table names.
     */
    public $pfx = '';


    /**
     * Name of the engine.
     */
    public $engine = 'PostgreSQL';

    /**
     * Current query cursor.
     */
    private $cur = null;

    /**
     * Current search path.
     */
    public $search_path = 'public';

    function __construct($user, $pwd, $server, $dbname, $pfx = '')
    {
        parent::__construct($server, $dbname, $user);

        /*
         * Old model support
         */
        $this->type_cast['Boolean'] = $this->type_cast['Boolean'] = array(
            '\Pluf\Db\PostgreSQLEngine::booleanFromDb',
            '\Pluf\Db\PostgreSQLEngine::booleanToDb'
        );
        $this->type_cast['Compressed'] = $this->type_cast['Compressed'] = array(
            '\Pluf\Db\PostgreSQLEngine::compressedFromDb',
            '\Pluf\Db\PostgreSQLEngine::compressedToDb'
        );

        $cstring = '';
        if ($server) {
            $cstring .= 'host=' . $server . ' ';
        }
        $cstring .= 'dbname=' . $dbname . ' user=' . $user;
        if ($pwd) {
            $cstring .= ' password=' . $pwd;
        }
        $this->pfx = $pfx;
        $this->cur = null;
        $this->con_id = @pg_connect($cstring);
        if (! $this->con_id) {
            throw new \Pluf\Exception($this->getError());
        }
    }

    /**
     * Get the version of the PostgreSQL server.
     *
     * Requires PostgreSQL 7.4 or later.
     *
     * @return string Version string
     */
    function getServerInfo()
    {
        $ver = pg_version($this->con_id);
        return $ver['server'];
    }

    function close()
    {
        if ($this->con_id) {
            pg_close($this->con_id);
            return true;
        } else {
            return false;
        }
    }

    function select($query)
    {
        $this->cur = @pg_query($this->con_id, $query);
        if (! $this->cur) {
            throw new \Pluf\Exception($this->getError());
        }
        $res = array();
        while ($row = pg_fetch_assoc($this->cur)) {
            $res[] = $row;
        }
        @pg_free_result($this->cur);
        $this->cur = null;
        return $res;
    }

    function execute($query)
    {
        $this->cur = @pg_query($this->con_id, $query);
        if (! $this->cur) {
            throw new \Pluf\Exception($this->getError());
        }
        return true;
    }

    function getLastID()
    {
        $res = $this->select('SELECT lastval() AS last_id');
        return (int) $res[0]['last_id'];
    }

    /**
     * Returns a string ready to be used in the exception.
     *
     * @return string Error string
     */
    function getError()
    {
        if ($this->cur) {
            return pg_result_error($this->cur) . ' - ' . $this->lastquery;
        }
        if ($this->con_id) {
            return pg_last_error($this->con_id) . ' - ' . $this->lastquery;
        } else {
            return pg_last_error() . ' - ' . $this->lastquery;
        }
    }

    function esc($str)
    {
        if (is_array($str)) {
            $res = array();
            foreach ($str as $s) {
                $res[] = '\'' . pg_escape_string($this->con_id, $s) . '\'';
            }
            return implode(', ', $res);
        }
        return '\'' . pg_escape_string($this->con_id, $str) . '\'';
    }

    /**
     * Set the current search path.
     */
    function setSearchPath($search_path = 'public')
    {
        if (preg_match('/[^\w\s\,]/', $search_path)) {
            throw new \Pluf\Exception('The search path: "' . $search_path . '" is not valid.');
        }
        $this->execute('SET search_path TO ' . $search_path);
        $this->search_path = $search_path;
        return true;
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
        return '<Pluf_DB_PostgreSQL(' . $this->con_id . ')>';
    }

    public static function booleanFromDb($val): bool
    {
        if (! $val) {
            return false;
        }
        return (strtolower(substr($val, 0, 1)) == 't');
    }

    public static function booleanToDb($val, $db)
    {
        if (null === $val) {
            return 'NULL';
        }
        if ($val) {
            return '1';
        }
        return '0';
    }

    public static function compressedToDb($val, $con)
    {
        if (is_null($val)) {
            return 'NULL';
        }
        return "'" . pg_escape_bytea(gzdeflate($val, 9)) . "'";
    }

    public static function compressedFromDb($val)
    {
        return ($val) ? gzinflate(pg_unescape_bytea($val)) : $val;
    }
    public function isLive(): bool
    {}

}

