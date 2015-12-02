<?php

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Jayab_SearchStatistic extends Pluf_Model
{

    /**
     * {@inheritDoc}
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'jayab_search_statistic';
        $this->_a['model'] = 'Jayab_SearchStatistic';
        $this->_model = 'Jayab_SearchStatistic';
        $this->_a['cols'] = array(
               'id' =>  array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ),
                'user' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'Pluf_User',
                        'blank' => true
                ),
                'application' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'SaaS_Application',
                        'blank' => true
                ),
                'tag' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'Jayab_Tag',
                        'blank' => true
                ),
                'spa' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 100,
                ),
                'device' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 100,
                ),
                'latitude' => array(
                        'type' => 'Pluf_DB_Field_Float',
                        'blank' => true,
                ),
                'longitude' => array(
                        'type' => 'Pluf_DB_Field_Float',
                        'blank' => true,
                ),
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                ),
        );
    }

    /**
     * \brief پیش ذخیره را انجام می‌دهد
     *
     * @param $create حالت
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave ($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
    }
}