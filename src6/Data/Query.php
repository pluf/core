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

class Query
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

    function __construct(?array $param = [])
    {
        $param = array_merge(array(
            'view' => null,
            'filter' => null,
            'order' => null,
            'start' => 0,
            'select' => null,
            'nb' => - 1,
            'count' => false
        ), $param);

        $this->view = $param['view'];
        $this->filter = $param['filter'];
        $this->order = $param['order'];
        $this->start = $param['start'];
        $this->limit = $param['nb'];
        $this->count = $param['count'];
    }

    /**
     * Get current view of the query
     *
     * @return string view
     */
    public function getView(): string
    {
        return $this->view;
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
     * @return array of filters
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     *
     * @param array $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    /**
     *
     * @return array order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     *
     * @param
     *            array orders
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     *
     * @return number
     */
    public function getStart()
    {
        return $this->start;
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
     * @return number
     */
    public function getLimit()
    {
        return $this->limit;
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
     * @return number of item
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     *
     * @param
     *            Ambigous <boolean, mixed> $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * Converts the query object to the old query model of Pluf
     *
     * @return array
     */
    public function toArray(): array
    {
        // general value
        $res = array(
            'view' => $this->view,
            'filter' => $this->filter
        );

        // check count query
        if ($this->count) {
            $res['count'] = true;
        } else {
            $res['count'] = false;
            $res['order'] = $this->order;
            $res['start'] = $this->start;
            if ($this->limit > 0) {
                $res['nb'] = $this->limit;
            }
        }

        // result
        return $res;
    }
}

