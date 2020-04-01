<?php
namespace Pluf\Db;

/**
 * This interface describes a class, which can represents a result set.
 * Some operations can be performed with this, such as
 * counting or adding conditions. This is implemented by Expression but more importantly ATK4 Data adds more implementations
 * for the ResultSet which implement NoSQL actions (such as on arrays etc).
 */
interface ResultSet
{

    public function get();

    public function getRow();

    public function getOne();
}
