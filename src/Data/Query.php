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
namespace Pluf\Data;

/**
 *
 * Here is list of attributes to build a query
 *
 *
 * - view
 * - filter
 * - order
 * - start
 * - limit
 * - select
 * - count
 *
 *
 * ## View
 *
 * View is named Model View which must be defined in the model descriptions.
 * For example suppose there is a view 'viewName' in the model and you are
 * about to run a query:
 *
 * ```PHP
 * $query = new Query([
 * 'view' => 'viewName',
 * ...
 * ]);
 * ```
 *
 * @author maso
 *        
 */
class Query
{

    const ORDER_ASC = 'asc';

    const ORDER_DESC = 'desc';

    const MAX_RESULT_SIZE = 300;

    use \Pluf\DiContainerTrait;

    /**
     * A view to used in query.
     *
     * You are free to create a new view or call an existed view from the repository.
     *
     * @var array|string
     */
    private $view = null;

    private ?array $filter = null;

    private ?array $order = null;

    /**
     * Start position of the table to query
     *
     * @var int
     */
    private int $start = 0;

    /**
     * Maximum result list size
     *
     * @var int
     */
    private ?int $limit = null;

    private bool $count = false;

    private ?string $select = null;

    /**
     * Creates new instance of the Query
     *
     * To build a query:
     *
     * - view: a view to apply
     * - filter: list of where clouse
     * - order: list of order by
     * - start: start index of results
     * - limit: Limit of resoult count
     * - select: a query to search
     * - count: returns counts of items
     *
     * @param array $param
     */
    function __construct(?array $param = [])
    {
        $param = array_merge(array(
            'view' => null,
            'filter' => [],
            'order' => [],
            'select' => null,
            'start' => 0,
            'limit' => - 1,
            'count' => false
        ), $param);
        $this->setDefaults($param);
    }

    /**
     * Gets select query
     *
     * @return string
     */
    public function getSelect(): string
    {
        return $this->select;
    }

    /**
     * Get current view of the query
     *
     * @return string view
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     *
     * @return array of filters
     */
    public function getFilter(): array
    {
        return $this->filter;
    }

    /**
     *
     * @return array order
     */
    public function getOrder(): array
    {
        return $this->order;
    }

    /**
     *
     * @return number
     */
    public function getStart(): int
    {
        return $this->start;
    }

    /**
     *
     * @return number
     */
    public function getLimit(): int
    {
        if ($this->limit < 1) {
            return Query::MAX_RESULT_SIZE;
        }
        return $this->limit;
    }

    /**
     *
     * @return number of item
     */
    public function getCount(): bool
    {
        return $this->count;
    }

    /**
     * Checks if there is a view in the query
     *
     * @return bool true if there is a veiw
     */
    public function hasView(): bool
    {
        if (is_array($this->view)) {
            return true;
        }
        return isset($this->view) && strlen($this->view) > 0;
    }

    /**
     *
     * @param mixed $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     *
     * @param mixed $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    /**
     * Adds filter into the filter list
     *
     * @param mixed $filter
     */
    public function addFilter($filter)
    {
        if (! isset($this->filter)) {
            $this->filter = [];
        }
        $this->filter[] = $filter;
    }

    /**
     *
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     *
     * @param number $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     *
     * @param number $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     *
     * @param boolean $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }
}

