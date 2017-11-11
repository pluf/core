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

    /**
     * 
     * {@inheritDoc}
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'monitor';
        $this->_a['multitenant'] = false;
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true,
                        'editable' => false,
                        'readable' => true
                ),
                'bean' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'size' => 150,
                        'blank' => false,
                        'verbose' => __('bean'),
                        'help_text' => __('The bean using this monitor.'),
                        'editable' => false,
                        'readable' => true
                ),
                'property' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 100,
                        'verbose' => __('property name'),
                        'help_text' => __(
                                'The property name must be unique for each application.'),
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
                'monitor_idx' => array(
                        'col' => 'bean, property',
                        'type' => 'unique', // normal, unique, fulltext, spatial
                        'index_type' => '', // hash, btree
                        'index_option' => '',
                        'algorithm_option' => '',
                        'lock_option' => ''
                )
        );
        
        $this->_a['views'] = array(
                'all' => array(
                        'select' => $this->getSelect()
                ),
                'beans' => array(
                        'select' => 'bean AS bean_id, title, description, level',
                        'group' => 'bean',
                        'props' => array(
                                'bean_id' => 'id'
                        )
                ),
                'properties' => array(
                        'select' => 'property AS property_id, title, description, level',
                        'props' => array(
                                'property_id' => 'id'
                        )
                )
        );
    }

    /**
     * فراخوانی مانیتور
     *
     * @param unknown $params            
     * @return unknown
     */
    function invoke ($request, $match = array())
    {
        $match['property'] = $this->property;
        return call_user_func_array(explode('::', $this->function), 
                array(
                        $request,
                        $match
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