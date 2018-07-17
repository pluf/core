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
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');

/**
 * Model pagination builder
 *
 * Model paginator is used to select and perform an action on part
 * of models in the model collection.
 *
 * Here is an example showes how to use the class:
 *
 * <code><pre>
 * $builder = new Pluf_Paginator_Builder(new Pluf_Model())
 * ->setRequest($request)
 *
 * ->setDisplayFields($displayList)
 * ->setSearchFields($searchFields)
 * ->setSortFields($sortFields)
 *
 * ->setWhereClause($sql)
 * ->setView($viewName);
 *
 * $paginator = $bulder->build();
 * </pre></code>
 *
 * @author Pluf <info@pluf.ir>
 */
class Pluf_Paginator_Builder
{

    private $model = null;

    /**
     * Display list
     *
     * @var array
     */
    private $displayFields = null;

    /**
     * The fields being searched.
     *
     * @var array
     */
    private $searchFields = null;

    /**
     * Sort fields
     *
     * @var array
     */
    private $sortFields = null;

    /**
     * The where clause from the search.
     *
     * @var Pluf_SQL
     */
    private $whereClause = null;

    /**
     * Model view to use with query
     *
     * @var string
     */
    private $modelView = null;

    /**
     * User request
     *
     * @var Pluf_HTTP_Request
     */
    private $request = null;

    /**
     * Sort orders
     *
     * @var array
     */
    private $sortOrders = null;

    /**
     * Creates new instance of builder
     *
     * @param Pluf_Model $model
     */
    function __construct($model = null)
    {
        $this->model = $model;
    }

    /**
     * Load setting from request
     *
     * @param Pluf_HTTP_Request $request
     * @return Pluf_Paginator_Builder
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * The fields being shown.
     *
     * If no fields are given, all readable fields of the model of the
     * item will be used to show the item.
     *
     * If an item in the list_display is an array the format is the
     * following:
     *
     * array('field', 'Custom_Function_ToApply', 'custom header name')
     *
     * @param array $displayFields
     */
    public function setDisplayList($displayFields)
    {
        $this->displayFields = $displayFields;
        return $this;
    }

    /**
     * Which fields of the model can be used to sort the dataset.
     *
     * To be useable these fields must be in the $list_display so that
     * the sort links can be shown for the user to click on them and
     * sort the list.
     *
     * @param array $sortFields
     * @return Pluf_Paginator_Builder
     */
    public function setSortFields($sortFields)
    {
        $this->sortFields = $sortFields;
        return $this;
    }

    /**
     * Which fields used to search
     *
     * @param array $searchFields
     * @return Pluf_Paginator_Builder
     */
    public function setSearchFields($searchFields)
    {
        $this->searchFields = $searchFields;
        return $this;
    }

    /**
     * Additional where clause
     *
     * @param Pluf_SQL $sql
     * @return Pluf_Paginator_Builder
     */
    public function setWhereClause($sql)
    {
        $this->whereClause = $sql;
        return $this;
    }

    /**
     * Main model view
     *
     * @param string $viewName
     * @return Pluf_Paginator_Builder
     */
    public function setView($viewName)
    {
        $this->modelView = $viewName;
        return $this;
    }

    /**
     * Sets list of sort orders
     *
     * <code><pre>
     * $builder
     * ->setSortOrder(array(
     * 'id',
     * 'DESC'
     * ))
     * ->build();
     * </pre></code>
     *
     * @param array $sortOrders
     *            list of default sort orders
     */
    public function setSortOrders($sortOrders)
    {
        $this->sortOrders = $sortOrders;
        return $this;
    }

    /**
     * Build a paginator
     *
     * @return Pluf_Paginator
     */
    public function build()
    {
        $paginator = new Pluf_Paginator($this->model);
        $paginator->configure($this->loadDisplayFields(), $this->loadSearchFileds(), $this->loadSortFields());
        $paginator->model_view = $this->loadModelView();
        $paginator->list_filters = $this->loadDisplayFields();
        if (isset($this->whereClause)) {
            $paginator->forced_where = $this->whereClause->gen();
        }
        if (isset($this->request)) {
            $paginator->setFromRequest($this->request);
        }
        if (isset($this->sortOrders)) {
            $paginator->sort_order = $this->sortOrders;
        } else {
            $paginator->sort_order = array(
                'id',
                'DESC'
            );
        }
        return $paginator;
    }

    /**
     * Load display fileds
     *
     * @return array
     */
    private function loadDisplayFields()
    {
        if (isset($this->displayFields)) {
            return $this->displayFields;
        }
        // maso, 2018: load from model
        return $this->getVisibleFieldsName();
    }

    /**
     * Loads search fileds
     *
     * @return array
     */
    private function loadSearchFileds()
    {
        if (isset($this->searchFields)) {
            return $this->searchFields;
        }
        // maso, 2018: load from model
        return $this->getVisibleFieldsName();
    }

    /**
     * Loads sort fields
     *
     * @return array
     */
    private function loadSortFields()
    {
        if (isset($this->searchFields)) {
            return $this->searchFields;
        }
        // maso, 2018: load from model
        return $this->getVisibleFieldsName();
    }

    /**
     * Gets all readable fields names
     *
     * @return array
     */
    private function getVisibleFieldsName()
    {
        if (isset($this->visibleVariablesName)) {
            return $this->visibleVariablesName;
        }
        // maso, 2048: assert the condition is_null($this->model)
        if (is_null($this->model)) {
            throw new Pluf_Exception('Model is empty');
        }
        $this->visibleVariablesName = array();
        foreach ($this->model->_a['cols'] as $key => $col) {
            // maso, 2018: continue if is not readable
            if (array_key_exists('readable', $col) && ! $col['readable']) {
                continue;
            }
            $this->visibleVariablesName[] = $key;
        }
        return $this->visibleVariablesName;
    }

    /**
     * Load model view
     *
     * @return string
     */
    private function loadModelView()
    {
        // TODO: maso, 2018: check if model view exist
        return $this->modelView;
    }
}

