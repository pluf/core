<?php

/**
 * ساختار داده‌ای یک کتاب ویکی را تعیین می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Wiki_Book extends Pluf_Model
{

    /**
     * مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_model = 'Wiki_Book';
        
        $this->_a['table'] = 'wiki_book';
        $this->_a['model'] = 'Wiki_Book';
        $this->_a['cols'] = array(
                // شناسه‌ها
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ),
                // فیلدها
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
                                'the language of the page must only contain letters. for example: en.')
                ),
                'summary' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 250,
                        'verbose' => __('summary'),
                        'help_text' => __(
                                'a one line description of the page content.')
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
                'interested' => array(
                        'type' => 'Pluf_DB_Field_Manytomany',
                        'model' => 'Pluf_User',
                        'blank' => true,
                        'verbose' => __('interested users'),
                        'help_text' => __(
                                'interested users will get an email notification when the wiki page is changed.')
                ),
                'submitter' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'Pluf_User',
                        'blank' => false,
                        'verbose' => __('submitter'),
                        'relate_name' => 'submitted_wikipages'
                ),
                'label' => array(
                        'type' => 'Pluf_DB_Field_Manytomany',
                        'model' => 'KM_Label',
                        'blank' => true,
                        'verbose' => __('labels'),
                        'help_text' => __('lables')
                ),
                'category' => array(
                        'type' => 'Pluf_DB_Field_Manytomany',
                        'model' => 'KM_Category',
                        'blank' => true,
                        'verbose' => __('categories'),
                        'help_text' => __('categories')
                )
        );
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