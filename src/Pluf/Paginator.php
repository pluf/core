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
 * Model pagination
 *
 * Model paginator is used to select and perform an action on part
 * of models in the model collection.
 *
 * این صفحه بند روش‌های متفاوتی را برای تولید یک نمایش از فهرست مدلها
 * دارد که از این میان می‌تواند به روش تولید یک جدول اشاره کرد.
 * علاوه بر این، این صفحه بند آزادی عمل مناسبی را برای کاربران ایجاد
 * می‌کند تا داده‌های موجود در فهرست را به سادگی نمایش دهند.
 *
 * Here is an example showes how to use the class:
 *
 * <code>
 * $pag = new Pluf_Paginator(new Pluf_Model());
 * // Get the paginator parameters from the request
 * $pag->setFromRequest($request);
 * print $pag->render();
 * </code>
 *
 * در نمونه بالا یک روش سریع برای نمایش داده‌ها آورده شده است.
 * به بیان دیگر تمام خصوصیت‌های مورد نیاز برای صفحه بندی داده‌ها بر اساس
 * خصوصیت‌های پیش فرض صفحه‌بند تعیین می‌شود.
 *
 * @date 2016 All templates and HTML generators are removed from the
 * code. In this version just REST API is upported.
 * @date 2018 Update the code style for more matainablity.
 *
 * @author maso <mostafa.barmshory@dpq.oc.ir>
 */
class Pluf_Paginator
{

    const SEARCH_QUERY_KEY = '_px_q';

    const CURRENT_PAGE_KEY = '_px_p';

    const SORT_KEY_KEY = '_px_sk';

    const SORT_ORDER_KEY = '_px_so';

    const FILTER_KEY_KEY = '_px_fk';

    const FILTER_VALUE_KEY = '_px_fv';

    /**
     * این مدل داده‌ای صفحه بندی خواهد شد.
     */
    public $model;

    /**
     * The items being paginated.
     *
     * If no model is given when creating the paginator, it will use the items to list them.
     *
     * @var array
     */
    public $items = null;

    /**
     * The fields being shown.
     *
     * If no fields are given, the __toString representation of the
     * item will be used to show the item.
     *
     * If an item in the list_display is an array the format is the
     * following:
     * array('field', 'Custom_Function_ToApply', 'custom header name')
     *
     * The signature of the the Custom_Function_ToApply is:
     * string = Custom_Function_ToApply('field', $item);
     *
     * By using for example 'id' as field with a custom header name
     * you can create new columns in the table.
     */
    public $list_display = array();

    /**
     * List filter.
     *
     * Allow the generation of filtering options for the list. If you
     * provide a list of fields having a "choices" option, you will be
     * able to filter on the choice values.
     */
    public $list_filters = array();

    /**
     * The fields being searched.
     */
    public $search_fields = array();

    /**
     * The where clause from the search.
     */
    public $where_clause = null;

    /**
     * The forced where clause on top of the search.
     */
    public $forced_where = null;

    /**
     * نمایشی از مدل داده‌ای را تعیین می‌کند که باید در این صفحه بندی استفاده
     * شود.
     */
    public $model_view = null;

    /**
     * Maximum number of items per page.
     */
    public $items_per_page = 30;

    /**
     * Current page.
     */
    public $current_page = 1;

    /**
     * Number of pages.
     */
    public $page_number = 1;

    /**
     * Search string.
     */
    public $search_string = '';

    /**
     * Which fields of the model can be used to sort the dataset.
     * To
     * be useable these fields must be in the $list_display so that
     * the sort links can be shown for the user to click on them and
     * sort the list.
     */
    public $sort_fields = array();

    /**
     * Current sort order.
     * An array with first value the field and
     * second the order of the sort.
     */
    public $sort_order = array();

    /**
     * Keys where the sort is reversed.
     * Let say, you have a column
     * using a timestamp but displaying the information as an age. If
     * you sort "ASC" you espect to get the youngest first, but as the
     * timestamp is used, you get the oldest. But the key here and the
     * sort will be reverted.
     */
    public $sort_reverse_order = array();

    /**
     * Creates new instance of paginator
     *
     * @param Pluf_Model $model
     *            The main pagination model
     * @param array $list_display
     *            fields to display
     * @param array $search_fields
     *            fields to search in
     *            
     * @see Pluf_Paginator#$search_fields($list_display, $search_fields=array(),
     *      $sort_fields=array())
     */
    function __construct($model = null, $list_display = array(), $search_fields = array())
    {
        $this->model = $model;
        $this->configure($list_display, $search_fields);
    }

    /**
     * Configure paginated list
     *
     * @param array $list_display
     *            تمام سرآیندهایی که باید نمایش داده شود.
     * @param array $search_fields
     *            پارامترهایی که می‌تواند جستجو شود.
     * @param array $sort_fields
     *            از داده‌ها که قابلیت مرتب شدن را دارند.
     */
    function configure($list_display, $search_fields = array(), $sort_fields = array())
    {
        if (is_array($list_display)) {
            $this->list_display = array();
            // TODO: maso, 2018: replace with builder
            foreach ($list_display as $key => $col) {
                if (! is_array($col) && ! is_null($this->model) && isset($this->model->_a['cols'][$col]['verbose'])) {
                    $this->list_display[$col] = $this->model->_a['cols'][$col]['verbose'];
                } elseif (! is_array($col)) {
                    if (is_numeric($key)) {
                        $this->list_display[$col] = $col;
                    } else {
                        $this->list_display[$key] = $col;
                    }
                } else {
                    if (count($col) == 2 && ! is_null($this->model) && isset($this->model->_a['cols'][$col[0]]['verbose'])) {
                        $col[2] = $this->model->_a['cols'][$col[0]]['verbose'];
                    } elseif (count($col) == 2) {
                        $col[2] = $col[0];
                    }
                    $this->list_display[] = $col;
                }
            }
        }
        if (is_array($search_fields)) {
            $this->search_fields = $search_fields;
        }
        if (is_array($sort_fields)) {
            $this->sort_fields = $sort_fields;
        }
    }

    /**
     * Load options from user request
     *
     * Here is list of all possible values
     *
     * <ul>
     * <li>_px_q : Query string to search.</li>
     * <li>_px_p : Current page.</li>
     * <li>_px_sk : Sort key.</li>
     * <li>_px_so : Sort order.</li>
     * <li>_px_fk : Filter key.</li>
     * <li>_px_fv : Filter value.</li>
     * <ul>
     *
     * @param
     *            Pluf_HTTP_Request The request
     */
    function setFromRequest($request)
    {
        // load query
        if (isset($request->REQUEST[self::SEARCH_QUERY_KEY])) {
            $this->search_string = $request->REQUEST[self::SEARCH_QUERY_KEY];
        }
        // load current page
        if (isset($request->REQUEST[self::CURRENT_PAGE_KEY])) {
            $this->current_page = (int) $request->REQUEST[self::CURRENT_PAGE_KEY];
            $this->current_page = max(1, $this->current_page);
        }

        // Load options
        $this->loadSortOptions($request);
        $this->loadFilterOptions($request);
    }

    /**
     * Creates data array from the request
     *
     * آرایه ایجاد شده هیچ محدودیتی ندارد و شامل تمام مواردی است که قبل در
     * سیستم ایجاد می‌شود.
     * علاوه بر این داده‌هایی که از پایگاه داده به دست آمده اند به صورت مستقیم
     * برگردانده می‌شوند و شامل هیچ ساختاری نیستند.
     * این روش برای استفاده از داده‌ها در ساختارهایی مانند JSON بسیار مناسب
     * خواهد بود.
     *
     * @return Array.
     */
    function render_array()
    {
        $items = $this->fetchItems();
        return $this->filterDisplayData($items);
    }

    /**
     * Render object in a page
     *
     * A page is an object with the following fields:
     *
     * @return array
     */
    function render_object()
    {
        $items = $this->fetchItems();
        $nb_items = $this->fetchItemsCount();
        $this->page_number = ceil($nb_items / $this->items_per_page);
        /**
         * ایجاد ساختار داده‌ای نهایی
         */
        return array(
            'items' => $items->getArrayCopy(),
            'counts' => $items->count(),
            'current_page' => $this->current_page,
            'items_per_page' => $this->items_per_page,
            'page_number' => $this->page_number
        );
    }

    /**
     * Fetch itmes from DB
     *
     * @return array|ArrayObject
     */
    private function fetchItems()
    {
        // Load pre defined items
        if (is_null($this->model)) {
            return $this->items;
        }

        // fetch from DB
        $st = ($this->current_page - 1) * $this->items_per_page;
        return $this->model->getList(array(
            'view' => $this->model_view,
            'filter' => $this->getFilters(),
            'order' => $this->getOrders(),
            'start' => $st,
            'nb' => $this->items_per_page
        ));
    }

    /**
     * Fetch count of itmes
     *
     * @return number of all items
     */
    private function fetchItemsCount()
    {
        if (is_null($this->model)) {
            return $this->items->count();
        }
        return $this->model->getCount(array(
            'view' => $this->model_view,
            'filter' => $this->getFilters()
        ));
    }

    /**
     * Generate the where clause.
     *
     * @return string The ready to use where clause.
     */
    private function getFilters()
    {
        if (strlen($this->where_clause) > 0) {
            return $this->where_clause;
        }
        if (! is_null($this->forced_where) || (strlen($this->search_string) > 0 && ! empty($this->search_fields))) {
            $lastsql = new Pluf_SQL();
            $keywords = $lastsql->keywords($this->search_string);
            foreach ($keywords as $key) {
                $sql = new Pluf_SQL();
                foreach ($this->search_fields as $field) {
                    $sqlor = new Pluf_SQL();
                    $sqlor->Q($field . ' LIKE %s', '%' . $key . '%');
                    $sql->SOr($sqlor);
                }
                $lastsql->SAnd($sql);
            }
            if (! is_null($this->forced_where)) {
                $lastsql->SAnd($this->forced_where);
            }
            $this->where_clause = $lastsql->gen();
            if (strlen($this->where_clause) == 0) {
                $this->where_clause = null;
            }
        }
        return $this->where_clause;
    }

    /**
     * Generates an order list and return
     *
     * All sort options will be loaded form $this->sort_order.
     *
     * You can set soert order as follow
     *
     * ```
     * $pag->sort_order = array(
     * 'param',
     * 'DESC'
     * );
     * ```
     * The result value is
     *
     * param DESC
     *
     * For moltiple options:
     *
     *
     * ```
     * $pag->sort_order = array(
     * array(
     * 'param1',
     * 'DESC'
     * ),
     * array(
     * 'param2',
     * 'ASC'
     * )
     * );
     * ```
     *
     * The result value is
     *
     * param1 DESC, param2 ASC
     *
     * @see #sort_order
     * @return NULL|string
     */
    private function getOrders()
    {
        // Convert a single sort option into the multiple
        if (sizeof($this->sort_order) > 0 && (! is_array($this->sort_order[0]))) {
            $this->sort_order = array(
                $this->sort_order
            );
        }

        $sort = '';
        for ($i = 0; $i < sizeof($this->sort_order); $i ++) {
            $order = $this->sort_order[$i];
            $s = $order[1];
            if (in_array($order[0], $this->sort_reverse_order)) {
                $s = ($s == 'ASC') ? 'DESC' : 'ASC';
            }
            $sort = $order[0] . ' ' . $s;
            if ($i < sizeof($this->sort_order) - 1) {
                $sort = $sort . ', ';
            }
        }
        return $sort;
    }

    /**
     * Filter all input data based on display list
     *
     * It the display list is empty {id} is used as default list.
     *
     * @param array $items
     *            to filter
     * @return array[]
     */
    private function filterDisplayData($items)
    {
        $out = array();
        foreach ($items as $item) {
            $idata = array();
            if (! empty($this->list_display)) {
                foreach ($this->list_display as $key => $col) {
                    if (! is_array($col)) {
                        $idata[$key] = $item->$key;
                    } else {
                        $_col = $col[0];
                        $idata[$col[0]] = $item->$_col;
                    }
                }
            } else {
                $idata = $item->id;
            }
            $out[] = $idata;
        }
        return $out;
    }

    /*
     * Load sort option from
     */
    private function loadSortOptions($request)
    {
        if (! isset($request->REQUEST[self::SORT_KEY_KEY])) {
            $this->sort_order = array();
            return;
        }
        // Sort orders
        $keys = $request->REQUEST[self::SORT_KEY_KEY];
        $vals = $request->REQUEST[self::SORT_ORDER_KEY];

        if (! is_array($keys)) {
            $keys = array(
                $keys
            );
            $vals = array(
                $vals
            );
        }

        $this->sort_order = array();
        for ($i = 0; $i < sizeof($keys); $i ++) {
            $key = $keys[$i];
            $order = $vals[$i];
            if (in_array($key, $this->sort_fields)) {
                $order = 'ASC';
                if ($order == 'd') {
                    $order = 'DESC';
                }
                $this->sort_order[] = array(
                    $key,
                    $order
                );
            }
        }
    }

    /*
     * Load filters
     */
    private function loadFilterOptions($request)
    {
        // check filter option
        if (! array_key_exists(self::FILTER_KEY_KEY, $request->REQUEST)) {
            return;
        }

        $keys = $request->REQUEST[self::FILTER_KEY_KEY];
        $vals = $request->REQUEST[self::FILTER_VALUE_KEY];
        // convert to array

        if (! is_array($keys)) {
            $keys = array(
                $keys
            );
            $vals = array(
                $vals
            );
        }

        // categorize filters
        $categories = array();
        for ($i = 0; $i < sizeof($keys); $i ++) {
            $key = $keys[$i];
            $val = $vals[$i];
            if (! in_array($key, $this->list_filters) || ! isset($val)) {
                continue;
            }
            if (array_key_exists($key, $categories)) {
                $categories[$key][] = $val;
            } else {
                $categories[$key] = array(
                    $val
                );
            }
        }

        // filter to query
        foreach ($categories as $key => $vals) {
            if (sizeof($vals) > 1) {
                $sql = new Pluf_SQL($key . ' in (%s)', array(
                    $vals
                ));
            } else {
                $sql = new Pluf_SQL($key . '=%s', $vals[0]);
            }
            // We add a forced where query
            if (! is_null($this->forced_where)) {
                $this->forced_where->SAnd($sql);
            } else {
                $this->forced_where = $sql;
            }
        }
    }
}


