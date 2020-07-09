<?php
namespace Pluf\Data;

use Pluf\DiContainerTrait;
use Pluf\HTTP\Error500;
use Pluf;

/**
 * Crates a new query
 *
 * @author maso
 *        
 */
class QueryBuilder
{
    use DiContainerTrait;

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
     * @param mixed $config
     * @return QueryBuilder
     */
    public static function getInstance($config): QueryBuilder
    {
        if ($config instanceof \Pluf\HTTP\Request) {
            return new QueryBuilder\RequestQueryBuilder($config);
        }
        if ($config instanceof Query) {
            $qb = new QueryBuilder();
            $qb->setDefaults([
                'view' => $config->getView(),
                'count' => $config->getCount(),
                'select' => $config->getSelect(),
                'start' => $config->getStart(),
                'limit' => $config->getLimit(),
                'filters' => $config->getFilter(),
                'orders' => $config->getOrder()
            ]);
            return $qb;
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

    public function addFilter(): QueryBuilder
    {
        $args = func_get_args();
        switch (count($args)) {
            case 0:
                throw new Error500('A parameter is required.');
            case 1:
                $this->filters[] = $args[0];
                break;
            case 2:
                $this->filters[] = [
                    $args[0],
                    '=',
                    $args[1]
                ];
                break;
            case 3:
                $this->filters[] = $args;
                break;
            default:
                throw new Error500('Maximum allowed paramiter is 3');
        }
        return $this;
    }

    /**
     * Sets select query
     *
     * @param string $select
     *            A query to perform on object.
     * @return QueryBuilder current builder
     */
    public function setSelect(?string $select = null): QueryBuilder
    {
        $this->select = $select;
        return $this;
    }

    public function optimize(): QueryBuilder
    {
        return $this->optimizeFilters();
    }

    public function optimizeFilters(): QueryBuilder
    {
        $categories = [];
        $other = [];
        foreach ($this->filters as $filter) {
            if (! is_array($filter)) {
                $other[] = $filter;
                continue;
            }
            $key = $filter[0];
            $opr = $filter[1];
            $val = $filter[2];
            switch ($opr) {
                case '=':
                    if (! array_key_exists($key, $categories)) {
                        $categories[$key] = [
                            $key,
                            'in',
                            []
                        ];
                    }
                    $categories[$key][2][] = $val;
                    break;
                case 'in':
                    if (! array_key_exists($key, $categories)) {
                        $categories[$key] = [
                            $key,
                            'in',
                            []
                        ];
                    }
                    if (! is_array($val)) {
                        throw new Error500('Invalid value for in operation');
                    }
                    $categories[$key][2] = array_merge($categories[$key][2], $val);
                    break;
                default:
                    $other[] = $filter;
            }
        }
        $this->filters = array_merge($other, $categories);
        return $this;
    }

    /**
     * Builds a query based on configurations.
     *
     * @return Query
     */
    public function build(): Query
    {
        // optimize query
        if (Pluf::getConfig('data.query.optimization.enable', true)) {
            $this->optimize();
        }
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

