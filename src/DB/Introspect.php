<?php
namespace Pluf\DB;

use Pluf\NotImplementedException;

class Pluf_DB_Introspect
{

    protected $int = null;

    protected $backend = '';

    public function __construct($db)
    {
        // create engine
        $engine = '\\Pluf\\DB\Schema\\' . $db->engine;
        $this->int = new $engine($db);
        $this->backend = $db->engine;
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
        if (! method_exists($this->int, 'listTables')) {
            throw new NotImplementedException();
        }
        return $this->int->listTables();
    }
}


