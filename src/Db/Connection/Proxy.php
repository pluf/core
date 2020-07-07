<?php

namespace Pluf\Db\Connection;

use Pluf\Db\Expression;

/**
 *
 * @license MIT
 * @copyright Agile Toolkit (c) http://agiletoolkit.org/
 */
class Proxy extends \Pluf\Db\Connection
{

    /**
     * Specifying $properties to constructors will override default
     * property values of this class.
     *
     * @param array $properties
     */
    public function __construct($properties = [])
    {
        parent::__construct($properties);

        if ($this->connection instanceof \Pluf\Db\Connection && $this->connection->driver) {
            $this->driver = $this->connection->driver;
        }
    }

    public function connection()
    {
        return $this->connection->connection();
    }

    public function dsql($properties = [])
    {
        $dsql = $this->connection->dsql($properties);
        $dsql->connection = $this;

        return $dsql;
    }

    public function expr($properties = [], $arguments = null)
    {
        $expr = $this->connection->expr($properties, $arguments);
        $expr->connection = $this;

        return $expr;
    }

    public function execute(Expression $expr)
    {
        return $this->connection->execute($expr);
    }
}
