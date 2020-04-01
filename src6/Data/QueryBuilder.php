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

class QueryBuilder
{

    private ?string $view = null;

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
    private int $limit = - 1;

    private bool $count = false;

    /**
     *
     * @param mixed $view
     */
    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     *
     * @param multitype: $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     *
     * @param multitype: $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     *
     * @param number $start
     */
    public function setStart($start)
    {
        $this->start = $start;
        return $this;
    }

    /**
     *
     * @param number $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     *
     * @param boolean $count
     */
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

    public function build(): Query
    {
        $query = new Query();

        $query->setView($this->view);
        $query->setFilter($this->filter);

        $query->setCount($this->count);

        $query->setOrder($this->order);
        $query->setLimit($this->limit);
        $query->setStart($this->start);

        return $query;
    }

    public static function getInstance(): QueryBuilder
    {
        return new QueryBuilder();
    }
}

