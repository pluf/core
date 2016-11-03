<?php

/**
 * مانیتور سیستم
 *
 * هر ماژول می‌تونه تابعی رو به عنوان مانیتور تعریف کنه. در صورتی که کاربر
 * مقدار مانیتور را بخواهد، نتیجه فراخوانی تابع به عنوان مقدار مانیتور
 * ارسال می‌شود.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Pluf_Monitor extends Pluf_Model
{

    public $_model = 'Pluf_Monitor';

    function init ()
    {
        $this->_a['table'] = 'monitor';
        $this->_a['model'] = 'Pluf_Monitor';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true,
                        'editable' => false,
                        'readable' => true
                ),
                'application' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'size' => 150,
                        'blank' => false,
                        'verbose' => __('application'),
                        'help_text' => __(
                                'The application using this permission.'),
                        'editable' => false,
                        'readable' => true
                ),
                'code_name' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 100,
                        'verbose' => __('code name'),
                        'help_text' => __(
                                'The code name must be unique for each application.'),
                        'editable' => false,
                        'readable' => true
                ),
                'level' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'editable' => true,
                        'readable' => true
                ),
                'title' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 50,
                        'editable' => true,
                        'readable' => true
                ),
                'description' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 250,
                        'editable' => true,
                        'readable' => true
                ),
                'function' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 100
                ),
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'editable' => false,
                        'readable' => true
                ),
                'modif_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'editable' => false,
                        'readable' => true
                )
        );
        
        $this->_a['idx'] = array(
                'code_name_idx' => array(
                        'type' => 'normal',
                        'col' => 'code_name'
                ),
                'application_idx' => array(
                        'type' => 'normal',
                        'col' => 'application'
                ),
                'monitor_idx' => array(
                        'col' => 'application, code_name',
                        'type' => 'unique', // normal, unique, fulltext, spatial
                        'index_type' => '', // hash, btree
                        'index_option' => '',
                        'algorithm_option' => '',
                        'lock_option' => ''
                )
        );
    }

    /**
     * فراخوانی مانیتور
     *
     * @param unknown $params            
     * @return unknown
     */
    function invoke ($request)
    {
        return call_user_func_array(explode('::', $this->function), 
                array(
                        $request
                ));
    }

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
}
