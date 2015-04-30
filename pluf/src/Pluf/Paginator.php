<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
#
# Plume Framework is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume Framework is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

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
 * // Set the action to the page listing the permissions
 * $pag->action = 'view_name'; 
 * // Get the paginator parameters from the request
 * $pag->setFromRequest($request);
 * print $pag->render();
 * </code>
 * 
 * در نمونه بالا یک روش سریع برای نمایش داده‌ها آورده شده است.
 * به بیان دیگر تمام خصوصیت‌های مورد نیاز برای صفحه بندی داده‌ها بر اساس
 * خصوصیت‌های پیش فرض صفحه‌بند تعیین می‌شود.
 */
class Pluf_Paginator
{
    /**
     * این مدل داده‌ای صفحه بندی خواهد شد.
     */
    protected $model;

    /**
     * The items being paginated. If no model is given when creating
     * the paginator, it will use the items to list them.
     */
    public $items = null;

    /**
     * Extra property/value for the items.
     *
     * This can be practical if you want some values for the edit
     * action which are not available in the model data.
     */
    public $item_extra_props = array();

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
     * Extra classes that will be applied to the td of each cell of
     * each column.
     *
     * If you have 3 columns and put array('one', '', 'foo') all the
     * td of the first column will have the class 'one' set and the
     * tds of the last column will have the 'foo' class set.
     */
    public $extra_classes = array();

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
     * Text to display when no results are found.
     */
    public $no_results_text = 'No items found';

    /**
     * Which fields of the model can be used to sort the dataset. To
     * be useable these fields must be in the $list_display so that
     * the sort links can be shown for the user to click on them and
     * sort the list.
     */
    public $sort_fields = array();

    /**
     * Current sort order. An array with first value the field and
     * second the order of the sort.
     */
    public $sort_order = array();

    /**
     * Keys where the sort is reversed. Let say, you have a column
     * using a timestamp but displaying the information as an age. If
     * you sort "ASC" you espect to get the youngest first, but as the
     * timestamp is used, you get the oldest. But the key here and the
     * sort will be reverted.
     */
    public $sort_reverse_order = array();
    
    /**
     *
     * Do not add the little sort links but directly make the title of
     * the column a link to sort.
     */
    public $sort_link_title = false;
    
    /**
     * Edit action.
     *
     */
    public $edit_action  = '';

    /**
     * Action for search/next/previous.
     */
    public $action = '';

    /**
     * Id/class of the generated table.
     */
    public $id = '';
    public $class = '';

    /**
     * Extra parameters for the modification function call.
     */
    public $extra = null;

    /**
     * Summary for the table.
     */
    public $summary = '';

    /**
     * Total number of items. 
     *
     * Available only after the rendering of the paginator.
     */
    public $nb_items = 0;

    protected $active_list_filter = array();

    /**
     * Maximum number of pages to be displayed
     *
     * Instead of showing by default unlimited number of pages,
     * limit to this value.
     * 0 is unlimited (default).
     *
     * Ex: max_number_pages = 3 will produce
     * Prev 1 ... 498 499 500 ... 1678 Next
     */
    public $max_number_pages = 0;
    public $max_number_pages_separator = '...';

    public $custom_max_items = false;

    /**
     * First, Previous, Next and Last page display
     * Default First = 1, Last = last page num
     * Prev and Next are initialized to null. In the footer() we will
     * set Prev = __('Prev') and Next = __('Next') if not set
     * Last has to be set during render if not set so that we know
     * the number of pages
     */
    public $symbol_first = '1';
    public $symbol_last = null;
    public $symbol_prev = null;
    public $symbol_next = null;

    /**
     * یک صفحه بند را برای مدل تعیین شده ایجاد می‌کند
     *
     * @param $model مدل داده‌ای که باید صفحه بندی شود.
     * @param $list_display فهرست تمام سرآیندهایی که باید نمایش داده شود.
     * @param $search_fields فهرست پارامترهایی که می‌تواند جستجو شود.
     * 
     * @see Pluf_Paginator#$search_fields($list_display, $search_fields=array(), $sort_fields=array())
     */
    function __construct($model=null, $list_display=array(), 
                         $search_fields=array())
    {
        $this->model = $model;
        $this->configure($list_display, $search_fields);
    }

    /**
     * صفحه بند را تنظیم می‌کند
     *
     * @param $list_display فهرست تمام سرآیندهایی که باید نمایش داده شود.
     * @param $search_fields فهرست پارامترهایی که می‌تواند جستجو شود.
     * @param $sort_fields فهرستی از داده‌ها که قابلیت مرتب شدن را دارند.
     */
    function configure($list_display, $search_fields=array(), $sort_fields=array())
    {
        if (is_array($list_display)) {
            $this->list_display = array();
            foreach ($list_display as $key=>$col) {
                if (!is_array($col) && !is_null($this->model) && isset($this->model->_a['cols'][$col]['verbose'])) {
                    $this->list_display[$col] = $this->model->_a['cols'][$col]['verbose'];
                } elseif (!is_array($col)) {
                    if (is_numeric($key)) {
                        $this->list_display[$col] = $col;
                    } else {
                        $this->list_display[$key] = $col;
                    }
                } else {
                    if (count($col) == 2 
                        && !is_null($this->model)
                        && isset($this->model->_a['cols'][$col[0]]['verbose'])) {
                        $col[2] = $this->model->_a['cols'][$col[0]]['verbose'];
                    } elseif (count($col) == 2 ) {
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
     * این پارامترها برای ایجاد یک فهرست از داده‌ها به کار گرفته می‌شوند. تمام پارامترهای
     * ممکن برای این کلاس عبارتند از:
     * 
     * _px_q : Query string to search.
     * _px_p : Current page.
     * _px_sk : Sort key.
     * _px_so : Sort order.
     * _px_fk : Filter key.
     * _px_fv : Filter value.
     *
     * @param Pluf_HTTP_Request The request
     */
    function setFromRequest($request)
    {
        if (isset($request->REQUEST['_px_q'])) {
            $this->search_string = $request->REQUEST['_px_q'];
        }
        if (isset($request->REQUEST['_px_p'])) {
            $this->current_page = (int) $request->REQUEST['_px_p'];
            $this->current_page = max(1, $this->current_page);
        }
        if (isset($request->REQUEST['_px_sk']) 
            and in_array($request->REQUEST['_px_sk'], $this->sort_fields)) {
            $this->sort_order[0] = $request->REQUEST['_px_sk'];
            $this->sort_order[1] = 'ASC';
            if (isset($request->REQUEST['_px_so']) 
                and ($request->REQUEST['_px_so'] == 'd')) {
                $this->sort_order[1] = 'DESC';
            }
        }
        if (isset($request->REQUEST['_px_fk']) 
            and in_array($request->REQUEST['_px_fk'], $this->list_filters)
            and isset($request->REQUEST['_px_fv'])) {
            // We add a forced where query
            $sql = new Pluf_SQL($request->REQUEST['_px_fk'].'=%s',
                                $request->REQUEST['_px_fv']);
            if (!is_null($this->forced_where)) {
                $this->forced_where->SAnd($sql);
            } else {
                $this->forced_where = $sql;
            }
            $this->active_list_filter = array($request->REQUEST['_px_fk'],
                                              $request->REQUEST['_px_fv']);
        }
    }
        

    /**
     * ترجمه و ایجاد جدول کامل
     *
     * When an id is provided, the generated table receive this id.
     *
     * @param string Table id ('')
     */
    function render($id='')
    {
        $this->id = $id;
        $_sum = '';
        if (strlen($this->summary)) {
            $_sum = ' summary="'.htmlspecialchars($this->summary).'"';
        }
        $out = '<table'.$_sum.(($this->class) ? ' class="'.$this->class.'"' : '').(($this->id) ? ' id="'.$this->id.'">' : '>')."\n";
        $out .= '<thead>'."\n";
        $out .= $this->searchField();
        $out .= $this->colHeaders();
        $out .= '</thead>'."\n";
        // Opt: Generate the footer of the table with the next/previous links
        $out .= $this->footer();
        // Generate the body of the table with the items
        $out .= $this->body();
        $out .= '</table>'."\n";
        return new Pluf_Template_SafeString($out, true);
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
    function render_array()
    {
        if (count($this->sort_order) != 2) {
            $order = null;
        } else {
            $s = $this->sort_order[1];
            if (in_array($this->sort_order[0], $this->sort_reverse_order)) {
                $s = ($s == 'ASC') ? 'DESC' : 'ASC';
            }
            $order = $this->sort_order[0].' '.$s;
        }
        if (!is_null($this->model)) {
            $items = $this->model->getList(array('view' => $this->model_view, 
                                                 'filter' => $this->filter(), 
                                                 'order' => $order, 
                                                 ));
        } else {
            $items = $this->items;
        }
        $out = array();
        foreach ($items as $item) {
            $idata = array();
            if (!empty($this->list_display)) {
                $i = 0;
                foreach ($this->list_display as $key=>$col) {
                    if (!is_array($col)) {
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
    
    function render_object(){
    	$st = ($this->current_page-1) * $this->items_per_page;
    	if (count($this->sort_order) != 2) {
    		$order = null;
    	} else {
    		$s = $this->sort_order[1];
    		if (in_array($this->sort_order[0], $this->sort_reverse_order)) {
    			$s = ($s == 'ASC') ? 'DESC' : 'ASC';
    		}
    		$order = $this->sort_order[0].' '.$s;
    	}
    	if (!is_null($this->model)) {
    		$items = $this->model->getList(array(
    				'view' => $this->model_view,
    				'filter' => $this->filter(),
    				'order' => $order,
    				'start' => $st,
    				'nb' => $this->items_per_page));
    	} else {
    		$items = $this->items;
    	}
    	return $items;
    }

    /**
     * Generate the footer of the table.
     */
    function footer()
    {
        if (!is_null($this->model)) {
            $nb_items = $this->model->getCount(array('view' => $this->model_view,  'filter' => $this->filter()));
        } else {
            $nb_items = $this->items->count();
        }
        $this->nb_items = $nb_items;
        if ($nb_items <= $this->items_per_page) {
            return '';
        }
        $out = '<tfoot><tr><th colspan="'.count($this->list_display).'">'
        		.$this->footer_plain()
        		.'</th></tr></tfoot>'."\n";
        return $out;
    }
    
    function footer_plain()
    {
    	// depending on the search string, the result set can be
    	// limited. So we need first to count the total number of
    	// corresponding items. Then get a slice of them in the
    	// generation of the body.
    	if (!is_null($this->model)) {
    		$nb_items = $this->model->getCount(array('view' => $this->model_view,  'filter' => $this->filter()));
    	} else {
    		$nb_items = $this->items->count();
    	}
    	$this->nb_items = $nb_items;
    	if ($nb_items <= $this->items_per_page) {
    		return '';
    	}
    	$this->page_number = ceil($nb_items / $this->items_per_page);
    	if ($this->current_page > $this->page_number) {
    		$this->current_page = 1;
    	}
    	$params = array();
    	if (!empty($this->search_fields)) {
    		$params['_px_q'] = $this->search_string;
    		$params['_px_p'] = $this->current_page;
    	}
    	if (!empty($this->sort_order)) {
    		$params['_px_sk'] = $this->sort_order[0];
    		$params['_px_so'] = ($this->sort_order[1] == 'ASC') ? 'a' : 'd';
    	}
    	// Add the filtering
    	if (!empty($this->active_list_filter)) {
    		$params['_px_fk'] = $this->active_list_filter[0];
    		$params['_px_fv'] = $this->active_list_filter[1];
    	}
    	$out = '';
    	if ($this->current_page != 1) {
    		$params['_px_p'] = $this->current_page - 1;
    		$url = $this->getUrl($params);
    		$this->symbol_prev = ($this->symbol_prev ==null) ?__('Prev') : $this->symbol_prev;
    		$out .= '<a href="'.$url.'">'.$this->symbol_prev.'</a> ';
    	}
    	// Always display the link to Page#1
    	$i=1;
    	$params['_px_p'] = $i;
    	$class = ($i == $this->current_page) ? ' class="px-current-page"' : '';
    	$url = $this->getUrl($params);
    	$out .= '<a'.$class.' href="'.$url.'">'.$this->symbol_first.'</a> ';
    	// Display the number of pages given $this->max_number_pages
    	if ($this->max_number_pages > 0) {
    		$nb_pa = floor($this->max_number_pages/2);
    		$imin = $this->current_page - $nb_pa;
    		$imax = $this->current_page + $nb_pa;
    		// We put the separator if $imin is at leat greater than 2
    		if ($imin > 2) $out .= ' '.$this->max_number_pages_separator.' ';
    		if ($imin <= 1) $imin=2;
    		if ($imax >= $this->page_number) $imax = $this->page_number - 1;
    	} else {
    		$imin = 2;
    		$imax = $this->page_number - 1;
    	}
    	for ($i=$imin; $i<=$imax; $i++) {
    		$params['_px_p'] = $i;
    		$class = ($i == $this->current_page) ? ' class="px-current-page"' : '';
    		$url = $this->getUrl($params);
    		$out .= '<a'.$class.' href="'.$url.'">'.$i.'</a> ';
    	}
    	if (($this->max_number_pages > 0) && $imax < ($this->page_number - 1)) {
    		$out .= ' '.$this->max_number_pages_separator.' ';
    	}
    	// Always display the link to last Page
    	$i = $this->page_number;
    	$params['_px_p'] = $i;
    	$class = ($i == $this->current_page) ? ' class="px-current-page"' : '';
    	$url = $this->getUrl($params);
    	if ($this->symbol_last == null) $this->symbol_last=$i;
    	$out .= '<a'.$class.' href="'.$url.'">'.$this->symbol_last.'</a> ';
    	if ($this->current_page != $this->page_number) {
    		$params['_px_p'] = $this->current_page + 1;
    		$url = $this->getUrl($params);
    		$this->symbol_next = ($this->symbol_next == null) ? __('Next') : $this->symbol_next;
    		$out .= '<a href="'.$url.'">'.$this->symbol_next.'</a> ';
    	}
    	return $out;
    }

    /**
     * Generate the body of the list.
     */
    function body()
    {
        $st = ($this->current_page-1) * $this->items_per_page;
        if (count($this->sort_order) != 2) {
            $order = null;
        } else {
            $s = $this->sort_order[1];
            if (in_array($this->sort_order[0], $this->sort_reverse_order)) {
                $s = ($s == 'ASC') ? 'DESC' : 'ASC';
            }
            $order = $this->sort_order[0].' '.$s;
        }
        if (!is_null($this->model)) {
            $items = $this->model->getList(array('view' => $this->model_view, 
                                                 'filter' => $this->filter(), 
                                                 'order' => $order, 
                                                 'start' => $st, 
                                                 'nb' => $this->items_per_page));
        } else {
            $items = $this->items;
        }
        $out = '';
        $total = $items->count();
        $count = 1;
        foreach ($items as $item) {
            $item->_paginator_count = $count;
            $item->_paginator_total_page = $total;
            foreach ($this->item_extra_props as $key=>$val) {
                $item->$key = $val;
            }
            $out .= $this->bodyLine($item);
            $count++;
        }
        if (strlen($out) == 0) {
            $out = '<tr><td colspan="'
                .count($this->list_display).'">'.$this->no_results_text
                .'</td></tr>'."\n";
        }
        return '<tbody>'.$out.'</tbody>'."\n";
    }
    
    /**
     * Generate a standard "line" of the body
     */
    function bodyLine($item)
    {
        $out = '<tr>';
        if (!empty($this->list_display)) {
            $i = 0;
            foreach ($this->list_display as $key=>$col) {
                $text = '';
                if (!is_array($col)) {
                    $text = Pluf_esc($item->$key);
                } else {
                    if (is_null($this->extra)) {
                        $text = $col[1]($col[0], $item);
                    } else {
                        $text = $col[1]($col[0], $item, $this->extra);
                    }
                }
                if ($i == 0) {
                    $text = $this->getEditAction($text, $item);
                }
                $class = (isset($this->extra_classes[$i]) and $this->extra_classes[$i] != '') ? ' class="'.$this->extra_classes[$i].'"' : '';
                $out.='<td'.$class.'>'.$text.'</td>';
                $i++;
            }
        } else {
            $out.='<td>'.$this->getEditAction(Pluf_esc($item), $item).'</td>';
        }
        $out .= '</tr>'."\n";
        return $out;
    }

    /**
     * Get the edit action.
     *
     * @param string Text to put in the action. 
     *               No escaping of the text is performed.
     * @param object Model for the action.
     * @return string Ready to use string.
     */
    function getEditAction($text, $item)
    {
        $edit_action = $this->edit_action;
        if (!empty($edit_action)) {
            if (!is_array($edit_action)) {
                $params = array($edit_action, $item->id);
            } else {
                $params = array(array_shift($edit_action));
                foreach ($edit_action as $field) {
                    $params[] = $item->$field;
                }
            }
            $view = array_shift($params);
            $url = Pluf_HTTP_URL_urlForView($view, $params);
            return  '<a href="'.$url.'">'.$text.'</a>';
        } else {
            return $text;
        }
    }

    /**
     * Generate the where clause.
     *
     * @return string The ready to use where clause.
     */
    function filter()
    {
        if (strlen($this->where_clause) > 0) {
            return $this->where_clause;
        }
        if (!is_null($this->forced_where) 
            or (strlen($this->search_string) > 0
                && !empty($this->search_fields))) {
            $lastsql = new Pluf_SQL();
            $keywords = $lastsql->keywords($this->search_string);
            foreach ($keywords as $key) {
                $sql = new Pluf_SQL();
                foreach ($this->search_fields as $field) {
                    $sqlor = new Pluf_SQL();
                    $sqlor->Q($field.' LIKE %s', '%'.$key.'%');
                    $sql->SOr($sqlor);
                }
                $lastsql->SAnd($sql);
            }
            if (!is_null($this->forced_where)) {
                $lastsql->SAnd($this->forced_where);
            }
            $this->where_clause = $lastsql->gen();
            if (strlen($this->where_clause) == 0) 
                $this->where_clause = null;
        }            
        return $this->where_clause;
    }

    /**
     * Generate the column headers for the table.
     */
    function colHeaders()
    {
        if (empty($this->list_display)) {
            return '<tr><th>'.__('Name').'</th></tr>'."\n";
        } else {
            $out = '<tr>';
            foreach ($this->list_display as $key=>$col) {
                if (is_array($col)) {
                    $field = $col[0];
                    $name = $col[2];
                    Pluf::loadFunction($col[1]);
                } else {
                    $name = $col;
                    $field = $key;
                }
                if (!$this->sort_link_title) {
                    $out .= '<th><span class="px-header-title">'.Pluf_esc(ucfirst($name)).'</span>'.$this->headerSortLinks($field).'</th>';
                } else {
                    $out .= '<th><span class="px-header-title">'.$this->headerSortLinks($field, Pluf_esc(ucfirst($name))).'</span></th>';
                }
            }
            $out .= '</tr>'."\n";
            return $out;
        }
    }

    /**
     * Generate the little text on the header to allow sorting if
     * available.
     *
     * If the title is set, the link is directly made on the title.
     *
     * @param string Name of the field
     * @param string Title ('')
     * @return string HTML fragment with the links to 
     *                sort ASC/DESC on this field.
     */
    function headerSortLinks($field, $title='')
    {
        if (!in_array($field, $this->sort_fields)) {
            return $title;
        }
        $params = array();
        if (!empty($this->search_fields)) {
            $params['_px_q'] = $this->search_string;
        }
        if (!empty($this->active_list_filter)) {
            $params['_px_fk'] = $this->active_list_filter[0];
            $params['_px_fv'] = $this->active_list_filter[1];
        }
        $params['_px_sk'] = $field;
        $out = '<span class="px-sort">'.__('Sort').' %s/%s</span>';
        $params['_px_so'] = 'a';
        $aurl = $this->getUrl($params);
        $asc = '<a href="'.$aurl.'" >'.__('asc').'</a>';
        $params['_px_so'] = 'd';
        $durl = $this->getUrl($params);
        $desc = '<a href="'.$durl.'" >'.__('desc').'</a>';
        if (strlen($title)) {
            if (count($this->sort_order) == 2
                and $this->sort_order[0] == $field 
                and $this->sort_order[1] == 'ASC') {
                return '<a href="'.$durl.'" >'.$title.'</a>';
            }
            return '<a href="'.$aurl.'" >'.$title.'</a>';
        }
        return sprintf($out, $asc, $desc);
    }

    /**
     * Get the search field XHTML.
     */
    function searchField()
    {
        if (empty($this->search_fields)) {
            return '';
        }
        $url = $this->getUrl();
        return '<tr><th class="px-table-search" colspan="'
            .count($this->list_display).'">'
            .'<form method="get" action="'.$url.'">'
            .'<label for="px-q">'.__('Filter the list:').'</label> '
            .'<input type="text" name="_px_q" id="px-q" size="30"'
            .' value="'.htmlspecialchars($this->search_string).'" />'
            .'<input type="submit" name="submit" value="'.__('Filter').'" />'
            .'</form></th></tr>'."\n";
        
    }

    /**
     * Using $this->action and the $get_params array, generate the URL
     * with the data.
     *
     * @param array Get parameters (array()).
     * @param bool Encoded to be put in href="" (true).
     * @return string Url.
     */
    function getUrl($get_params=array(), $encoded=true)
    {
        // Default values
        $params = array();
        $by_name = false;
        $view = '';
        if (is_array($this->action)) {
            $view = $this->action[0];
            if (isset($this->action[1])) {
                $params = $this->action[1];
            }
        } else {
            $view = $this->action;
        }
        return Pluf_HTTP_URL_urlForView($view, $params, $get_params, $encoded);
    }

    /**
     * بازنویسی فهرست خصوصیت‌ها
     * 
     * یک خصوصیت مجازی برای این کلاس در نظر گرفته شده است که معادل با خروجی فراخوانی
     * متد render است.
     * 
     * @param string Property to get
     */
    function __get($prop)
    {
        if ($prop == 'render') 
        	return $this->render();
        if ($prop == 'objects')
        	return $this->render_object();
        if ($prop == 'footer')
        	return $this->footer_plain();
        return $this->$prop;
    }

}

/**
 * Returns the string representation of an item.
 *
 * @param string Field (not used)
 * @param Object Item
 * @return string Representation of the item
 */
function Pluf_Paginator_ToString($field, $item)
{
    return Pluf_esc($item);
}

/**
 * Returns the item referenced as foreign key as a string.
 */
function Pluf_Paginator_FkToString($field, $item)
{
    $method = 'get_'.$field;
    $fk = $item->$method();
    return Pluf_esc($fk);
}

function Pluf_Paginator_DateYMDHMS($field, $item)
{
    Pluf::loadFunction('Pluf_Template_dateFormat');
    return Pluf_Template_dateFormat($item->$field, '%Y-%m-%d %H:%M:%S');
}

function Pluf_Paginator_DateYMDHM($field, $item)
{
    Pluf::loadFunction('Pluf_Template_dateFormat');
    return Pluf_Template_dateFormat($item->$field, '%Y-%m-%d %H:%M');
}

function Pluf_Paginator_DateYMD($field, $item)
{
    Pluf::loadFunction('Pluf_Template_dateFormat');
    return Pluf_Template_dateFormat($item->$field, '%Y-%m-%d');
}

function Pluf_Paginator_DisplayVal($field, $item)
{
    return $item->displayVal($field);
}

function Pluf_Paginator_DateAgo($field, $item)
{
    Pluf::loadFunction('Pluf_Date_Easy');
    Pluf::loadFunction('Pluf_Template_dateFormat');
    $date = Pluf_Template_dateFormat($item->$field, '%Y-%m-%d %H:%M:%S');
    return Pluf_Date_Easy($date, null, 2, __('now'));
}