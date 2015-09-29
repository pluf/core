<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');

/**
 * صفحه بند برای نمایش فهرستی از مدلهای داده
 *
 * این صفحه بند روش‌های متفاوتی را برای تولید یک نمایش از فهرست مدلها
 * دارد که از این میان می‌تواند به روش تولید یک جدول اشاره کرد.
 * علاوه بر این، این صفحه بند آزادی عمل مناسبی را برای کاربران ایجاد
 * می‌کند تا داده‌های موجود در فهرست را به سادگی نمایش دهند.
 *
 * یک نمونه استفاده از این کلاس در زیر آورده شده است:
 *
 * <code>
 * $model = new Pluf_Permission();
 * $pag = new Pluf_Paginator($model);
 * // Get the paginator parameters from the request
 * $pag->setFromRequest($request);
 * print $pag->render();
 * </code>
 *
 * در نمونه بالا یک روش سریع برای نمایش داده‌ها آورده شده است.
 * به بیان دیگر تمام خصوصیت‌های مورد نیاز برای صفحه بندی داده‌ها بر اساس
 * خصوصیت‌های پیش فرض صفحه‌بند تعیین می‌شود.
 *
 * @date 1394 هدف اصلی توسعه این سیستم پیاده سازی یک بستر برای REST است
 * از این رو فرآیند تولید الگو از این کلاس کاملا حذف شده است.
 *
 * @author maso <mostafa.barmshory@dpq.oc.ir>
 */
class Pluf_Paginator
{

    /**
     * این مدل داده‌ای صفحه بندی خواهد شد.
     */
    protected $model;

    /**
     * The items being paginated.
     * If no model is given when creating
     * the paginator, it will use the items to list them.
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
    protected $list_display = array();

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
    protected $search_fields = array();

    /**
     * The where clause from the search.
     */
    protected $where_clause = null;

    /**
     * The forced where clause on top of the search.
     */
    public $forced_where = null;

    /**
     * View of the model to be used.
     */
    public $model_view = null;

    /**
     * Maximum number of items per page.
     */
    public $items_per_page = 50;

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
     * Total number of items.
     *
     *
     * Available only after the rendering of the paginator.
     */
    public $nb_items = 0;

    protected $active_list_filter = array();

    /**
     * یک صفحه بند را برای مدل تعیین شده ایجاد می‌کند
     *
     * @param $model مدل
     *            داده‌ای که باید صفحه بندی شود.
     * @param $list_display فهرست
     *            تمام سرآیندهایی که باید نمایش داده شود.
     * @param $search_fields فهرست
     *            پارامترهایی که می‌تواند جستجو شود.
     *            
     * @see Pluf_Paginator#$search_fields($list_display, $search_fields=array(),
     *      $sort_fields=array())
     */
    function __construct ($model = null, $list_display = array(), $search_fields = array())
    {
        $this->model = $model;
        $this->configure($list_display, $search_fields);
    }

    /**
     * صفحه بند را تنظیم می‌کند
     *
     * @param $list_display فهرست
     *            تمام سرآیندهایی که باید نمایش داده شود.
     * @param $search_fields فهرست
     *            پارامترهایی که می‌تواند جستجو شود.
     * @param $sort_fields فهرستی
     *            از داده‌ها که قابلیت مرتب شدن را دارند.
     */
    function configure ($list_display, $search_fields = array(), $sort_fields = array())
    {
        if (is_array($list_display)) {
            $this->list_display = array();
            foreach ($list_display as $key => $col) {
                if (! is_array($col) && ! is_null($this->model) &&
                         isset($this->model->_a['cols'][$col]['verbose'])) {
                    $this->list_display[$col] = $this->model->_a['cols'][$col]['verbose'];
                } elseif (! is_array($col)) {
                    if (is_numeric($key)) {
                        $this->list_display[$col] = $col;
                    } else {
                        $this->list_display[$key] = $col;
                    }
                } else {
                    if (count($col) == 2 && ! is_null($this->model) &&
                             isset($this->model->_a['cols'][$col[0]]['verbose'])) {
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
     * بر اساس تقاضای دریافت شده پارامترها را تنظیم می‌کند
     *
     * این پارامترها برای ایجاد یک فهرست از داده‌ها به کار گرفته می‌شوند. تمام
     * پارامترهای
     * ممکن برای این کلاس عبارتند از:
     *
     * _px_q : Query string to search.
     * _px_p : Current page.
     * _px_sk : Sort key.
     * _px_so : Sort order.
     * _px_fk : Filter key.
     * _px_fv : Filter value.
     *
     * @param
     *            Pluf_HTTP_Request The request
     */
    function setFromRequest ($request)
    {
        if (isset($request->REQUEST['_px_q'])) {
            $this->search_string = $request->REQUEST['_px_q'];
        }
        if (isset($request->REQUEST['_px_p'])) {
            $this->current_page = (int) $request->REQUEST['_px_p'];
            $this->current_page = max(1, $this->current_page);
        }
        if (isset($request->REQUEST['_px_sk']) and
                 in_array($request->REQUEST['_px_sk'], $this->sort_fields)) {
            $this->sort_order[0] = $request->REQUEST['_px_sk'];
            $this->sort_order[1] = 'ASC';
            if (isset($request->REQUEST['_px_so']) and
                     ($request->REQUEST['_px_so'] == 'd')) {
                $this->sort_order[1] = 'DESC';
            }
        }
        if (isset($request->REQUEST['_px_fk']) and
                 in_array($request->REQUEST['_px_fk'], $this->list_filters) and
                 isset($request->REQUEST['_px_fv'])) {
            // We add a forced where query
            $sql = new Pluf_SQL($request->REQUEST['_px_fk'] . '=%s', 
                    $request->REQUEST['_px_fv']);
            if (! is_null($this->forced_where)) {
                $this->forced_where->SAnd($sql);
            } else {
                $this->forced_where = $sql;
            }
            $this->active_list_filter = array(
                    $request->REQUEST['_px_fk'],
                    $request->REQUEST['_px_fv']
            );
        }
    }

    /**
     * ترجمه و ایجاد آرایه
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
    function render_array ()
    {
        if (count($this->sort_order) != 2) {
            $order = null;
        } else {
            $s = $this->sort_order[1];
            if (in_array($this->sort_order[0], $this->sort_reverse_order)) {
                $s = ($s == 'ASC') ? 'DESC' : 'ASC';
            }
            $order = $this->sort_order[0] . ' ' . $s;
        }
        if (! is_null($this->model)) {
            $items = $this->model->getList(
                    array(
                            'view' => $this->model_view,
                            'filter' => $this->filter(),
                            'order' => $order
                    ));
        } else {
            $items = $this->items;
        }
        $out = array();
        foreach ($items as $item) {
            $idata = array();
            if (! empty($this->list_display)) {
                $i = 0;
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

    /**
     * تمام گزینه‌های یافت شده را تعیین می‌کند
     *
     * با استفاده از این فراخوانی می‌توان به فهرست تمام موجودیت‌ها دست یافت.
     *
     * @return unknown
     */
    function render_object ()
    {
        $st = ($this->current_page - 1) * $this->items_per_page;
        if (count($this->sort_order) != 2) {
            $order = null;
        } else {
            $s = $this->sort_order[1];
            if (in_array($this->sort_order[0], $this->sort_reverse_order)) {
                $s = ($s == 'ASC') ? 'DESC' : 'ASC';
            }
            $order = $this->sort_order[0] . ' ' . $s;
        }
        if (! is_null($this->model)) {
            $items = $this->model->getList(
                    array(
                            'view' => $this->model_view,
                            'filter' => $this->filter(),
                            'order' => $order,
                            'start' => $st,
                            'nb' => $this->items_per_page
                    ));
        } else {
            $items = $this->items;
        }
        
        if (! is_null($this->model)) {
            $this->nb_items = $this->model->getCount(
                    array(
                            'view' => $this->model_view,
                            'filter' => $this->filter()
                    ));
        } else {
            $this->nb_items = $this->items->count();
        }
        $this->page_number = ceil($this->nb_items / $this->items_per_page);
        /**
         * ایجاد ساختار داده‌ای نهایی
         */
        return array(
                'items' => $items,
                'counts' => $items->count(),
                'current_page' => $this->current_page,
                'items_per_page' => $this->items_per_page,
                'page_number' => $this->page_number,
        );
    }

    /**
     * Generate the where clause.
     *
     * @return string The ready to use where clause.
     */
    function filter ()
    {
        if (strlen($this->where_clause) > 0) {
            return $this->where_clause;
        }
        if (! is_null($this->forced_where) or (strlen($this->search_string) > 0 &&
                 ! empty($this->search_fields))) {
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
            if (strlen($this->where_clause) == 0)
                $this->where_clause = null;
        }
        return $this->where_clause;
    }
}

