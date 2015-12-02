<?php

/**
 * مدل داده‌ای یک واحد را ایجاد می‌کند
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class HM_Part extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * تمام فیلدهای مورد نیاز برای این مدل داده‌ای در این متد تعیین شده و به
     * صورت کامل ساختار دهی می‌شود.
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'hm_part';
        $this->_a['model'] = 'HM_Part';
        $this->_model = 'HM_Part';
        
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ),
                'title' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 200
                ),
                'count' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => true
                ),
                'part_number' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'verbose' => __('Part number or Id'),
                        'size' => 50,
                        'blank' => false,
                        'unique' => false
                ),
                'apartment' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'SaaS_Application',
                        'blank' => false,
                        'relate_name' => 'part'
                ),
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true
                ),
                'modif_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true
                )
        );
        $this->_a ['views'] = array (
                'apartment_correlation' => array (
                        'select' => 'apartment, count(*) as temp_att',
                        'group' => 'apartment',
                        'props' => array (
                                'temp_att' => 'count'
                        )
                ),
        );
    }

    /**
     * \brief پیش ذخیره را انجام می‌دهد
     *
     * در این فرآیند نیازهای ابتدایی سیستم به آن اضافه می‌شود. این نیازها
     * مقادیری هستند که
     * در زمان ایجاد باید تعیین شوند. از این جمله می‌توان به کاربر و تاریخ اشاره
     * کرد.
     *
     * @param $create حالت
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave ($create = false)
    {
        if ($create) {
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

    /**
     * Check if a part is anonymous.
     *
     * @return bool True if the user is anonymous.
     */
    function isAnonymous ()
    {
        return (0 === (int) $this->id);
    }
}