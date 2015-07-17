<?php

/**
 * ساختار داده‌ای برای تعیین یک پرنده را تعیین می‌کند.
 * 
 * @author maso
 *
 */
class Pluf_DB_Field_File extends Pluf_DB_Field
{
    /**
     * See definition in Pluf_DB_Field.
     */
    public $type = 'file';
    public $column = '';
    public $value;
    public $extra = array();
    public $methods = array();

    /**
     * Constructor.
     *
     * @param mixed Value ('')
     * @param string Column name ('')
     */
    function __construct($value='', $column='', $extra=array())
    {
        parent::__construct($value, $column, $extra);
        $this->methods = array(array(strtolower($column).'_url', 'Pluf_DB_Field_File_Url'),
                               array(strtolower($column).'_path', 'Pluf_DB_Field_File_Path')
                               );
    }

    function formField($def, $form_field='Pluf_Form_Field_File')
    {
        return parent::formField($def, $form_field);
    }
}

/**
 * Returns the url to access the file.
 */
function Pluf_DB_Field_File_Url($field, $method, $model, $args=null)
{
    if (strlen($model->$field) != 0) {
        return Pluf::f('upload_url').'/'.$model->$field;
    }
    return  '';
}

/**
 * Returns the path to access the file.
 */
function Pluf_DB_Field_File_Path($field, $method, $model, $args=null)
{
    if (strlen($model->$field) != 0) {
        return Pluf::f('upload_path').'/'.$model->$field;
    }
    return '';
}

