<?php
namespace Pluf\Data;

use Pluf\HTTP\Error500;
use Pluf\HTTP\Request;

/**
 * Crates a new query
 *
 * @author maso
 *        
 */
class QueryBuilder
{

    private $view;

    private ?bool $count = false;

    private ?string $select = null;

    private int $start = 0;

    private int $limit = - 1;

    private array $filters = [];

    private array $orders = [];

    /**
     * Creates new instance of the builder
     *
     * @param Request|array $config
     * @return QueryBuilder
     */
    public static function getInstance($config): QueryBuilder
    {
        if ($config instanceof \Pluf\HTTP\Request) {
            return new QueryBuilder\RequestQueryBuilder($config);
        }
        return new QueryBuilder();
    }

    /**
     * Sets query view
     *
     * @param array $view
     * @return QueryBuilder
     */
    public function setView($view): QueryBuilder
    {
        $this->view = $view;
        return $this;
    }

    /**
     * Start index of the result
     *
     * @param int $start
     * @return QueryBuilder
     */
    public function setStart(int $start): QueryBuilder
    {
        if ($start < 0) {
            throw new Error500('Start must be positive.');
        }
        $this->start = $start;
        return $this;
    }

    public function setLimit($limit): QueryBuilder
    {
        if ($limit < 0) {
            throw new Error500('Query limit must be positive.');
        }
        $this->limit = $limit;
        return $this;
    }

    public function setOrder($key, $order): QueryBuilder
    {
        foreach ($this->orders as $order) {
            if ($order[0] === $key) {
                throw new Error500('Dublicated order key');
            }
        }
        $this->orders[] = [
            $key,
            $order
        ];
        return $this;
    }

    public function addFilter($key, $value): QueryBuilder
    {
        $this->filters[] = [
            $key,
            '=',
            $value
        ];
        return $this;
    }

    /**
     * Sets select query
     *
     * @param string $select
     *            A query to perform on object.
     * @return QueryBuilder current builder
     */
    public function setSelect(string $select): QueryBuilder
    {
        $this->select = $select;
        return $this;
    }

    /**
     * Builds a query based on configurations.
     *
     * @return Query
     */
    public function build(): Query
    {
        return new Query([
            'view' => $this->view,
            'filter' => $this->filters,
            'order' => $this->orders,
            'select' => $this->select,
            'start' => $this->start,
            'limit' => $this->limit,
            'count' => $this->count
        ]);
    }
}

