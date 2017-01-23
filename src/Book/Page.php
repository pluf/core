<?php

/**
 * ساختار داده‌ای یک خانه را تعیین می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Book_Page extends Pluf_Model
{

    /**
     * مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'book_page';
        $this->_a['cols'] = array(
                // کلید
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ),
                // فیلدها
                'priority' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'unique' => false
                ),
                'state' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'unique' => false
                ),
                'title' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 250,
                        'verbose' => __('title'),
                        'help_text' => __(
                                'the title of the page must only contain letters, digits or the dash character. For example: My-new-Wiki-Page.')
                ),
                'language' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 50,
                        'verbose' => __('language'),
                        'help_text' => __(
                                'the language of the page must only contain letters. For example: en.')
                ),
                'summary' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 250,
                        'verbose' => __('summary'),
                        'help_text' => __(
                                'a one line description of the page content.')
                ),
                'content' => array(
                        'type' => 'Pluf_DB_Field_Compressed',
                        'blank' => false,
                        'verbose' => __('content')
                ),
                'content_type' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 50,
                        'verbose' => __('content type'),
                        'help_text' => __(
                                'the content type of the page is a mime type. For example: text/plain.')
                ),
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('creation date')
                ),
                'modif_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('modification date')
                ),
                // رابطه‌ها
                'submitter' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'Pluf_User',
                        'blank' => false,
                        'verbose' => __('submitter')
                ),
                'book' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'Pluf_Book',
                        'blank' => true,
                        'verbose' => __('book')
                )
        );
        $this->_a['views'] = array(
//                 'page_list' => array(
//                         'select' => $this->getCustomSelect('page_list', 
//                                 array(
//                                         'id',
//                                         'title',
//                                         'priority'
//                                 ))
//                 ),
//                 'page_list_summary' => array(
//                         'select' => $this->getCustomSelect('page_list', 
//                                 array(
//                                         'id',
//                                         'title',
//                                         'priority',
//                                         'state',
//                                         'language',
//                                         'summary'
//                                 ))
//                 )
        );
    }

//     function getCustomSelect ($cache_key, $keys = array())
//     {
//         if (isset($this->_cache[$cache_key]))
//             return $this->_cache[$cache_key];
//         $select = array();
//         $table = $this->getSqlTable();
//         foreach ($keys as $col) {
//             $val = $this->_a['cols'][$col];
//             if ($val['type'] != 'Pluf_DB_Field_Manytomany') {
//                 $select[] = $table . '.' . $this->_con->qn($col) . ' AS ' .
//                          $this->_con->qn($col);
//             }
//         }
//         $this->_cache['getSelect'] = implode(', ', $select);
//         return $this->_cache['getSelect'];
//     }

    /**
     * پیش ذخیره را انجام می‌دهد
     *
     * @param $create حالت
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave ($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }

    /**
     * حالت کار ایجاد شده را به روز می‌کند
     *
     * @see Pluf_Model::postSave()
     */
    function postSave ($create = false)
    {
        //
    }
}