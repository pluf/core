<?php
namespace Pluf\DB;

use Pluf\DB;

abstract class Engine
{

    /**
     * The last query, set with debug().
     * Used when an error is returned.
     */
    public string $lastquery = '';

    public string $engine;

    public $type_cast = array();

    public $con_id;

    public string $pfx = '';

    function __construct(string $pfx)
    {
        $refObject = new \ReflectionObject($this);
        
        $this->engine = $refObject->getShortName();
        $this->type_cast = DB::defaultTypecast();
        $this->pfx = $pfx;
    }

    public abstract function select($query);

    public abstract function execute($query);

    public abstract function begin();

    public abstract function commit();

    public abstract function rollback();

    /**
     * ********************************************
     * POD Support
     * ********************************************
     */

    // errorCode
    // errorInfo
    public function exec($query)
    {
        return $this->execute($query);
    }

    // getAttribute
    // getAvailableDrivers
    // inTransaction
    // lastInsertId
    // prepare
    // query
    // quote
    // rollBack
    // setAttribute

    /**
     * String reperesentation of the engine
     *
     * @return string
     */
    function __toString()
    {
        $objRef = new \ReflectionObject($this);
        return '<\\' . $objRef->getName() . '(' . $this->con_id . ')>';
    }

    /**
     * Log the queries.
     *
     * Keep track of all queris if in debug mode
     *
     * @param
     *            string Query to keep track
     */
    protected function debug(string $query): void
    {
        $this->lastquery = $query;
        \Pluf\Log::debug($query);
    }
}

