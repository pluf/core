<?php
namespace Pluf\Data;

/**
 * Crates a new query
 *
 * @author maso
 *        
 */
class QueryBuilder
{

    public static function getInstance($config): QueryBuilder
    {
        if ($config instanceof \Pluf\HTTP\Request) {
            return new QueryBuilder\RequestQueryBuilder($config);
        }
        return new QueryBuilder();
    }

    public function setView($view): QueryBuilder
    {
        return $this;
    }

    public function setStart($start): QueryBuilder
    {
        return $this;
    }

    public function setLimit($limit): QueryBuilder
    {
        return $this;
    }

    public function setOrder($key, $order): QueryBuilder
    {
        return $this;
    }

    public function addFilter($key, $value): QueryBuilder
    {
        return $this;
    }

    public function build(): Query
    {
        return null;
    }
}

