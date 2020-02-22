<?php

/**
 * فیلد پیش فرض برای پایگاه داده را تعریف می‌کند.
 */
class Pluf_DB_Field
{
    /**
     * نوع یک فیلد برای نگاشی این فیلد به فیلدهای واقعی پایگاه داده استفاده 
     * می‌شود. برای نمونه کلاس Pluf_DB_Schema_MySQL در یک متغیر mapping این 
     * نگاشت را نگهداری کرده است.
     */
    public $type = '';

    /**
     * The column name of the field.
     */
    public $column = '';

    /**
     * مقدار جاری از فیلد را تعیین می‌کند.
     */
    public $value;

    /**
     * سایر پارامترهای فیلد را تعیین می‌کند
     */
    public $extra = array();

    /**
     * سایر متدهایی را تعیین می‌کند که توسط زیر کلاس‌های فیلد اضافه شده‌اند.
     */
    public $methods = array();

    /**
     * یک نمونه جدید از این کلاس ایجاد می‌کند.
     *
     * @param mixed Value ('')
     * @param string Column name ('')
     */
    function __construct($value='', $column='', $extra=array())
    {
        $this->value = $value;
        $this->column = $column;
        if ($extra) {
            $this->extra = array_merge($this->extra, $extra);
        }
    }

    /**
     * Get the form field for this field.
     *
     * We put this method at the field level as it allows us to easily
     * create a new DB field and a new Form field and use them without
     * the need to modify another place where the mapping would be
     * performed.
     *
     * @param array Definition of the field.
     * @param string Form field class.
     */
    function formField($def, $form_field='Pluf_Form_Field_Varchar')
    {
        Pluf::loadClass('Pluf_Form_BoundField'); // To get mb_ucfirst
        $defaults = array('required' => !$def['blank'], 
                          'label' => mb_ucfirst($def['verbose']), 
                          'help_text' => $def['help_text']);
        unset($def['blank'], $def['verbose'], $def['help_text']);
        if (isset($def['default'])) {
            $defaults['initial'] = $def['default'];
            unset($def['default']);
        }
        if (isset($def['choices'])) {
            $defaults['widget'] = 'Pluf_Form_Widget_SelectInput';
            if (isset($def['widget_attrs'])) {
                $def['widget_attrs']['choices'] = $def['choices'];
            } else {
                $def['widget_attrs'] = array('choices' => $def['choices']);
            }
        }
        foreach (array_keys($def) as $key) {
            if (!in_array($key, array('widget', 'label', 'required', 'multiple',
                                      'initial', 'choices', 'widget_attrs'))) {
                unset($def[$key]);
            }
        }
        $params = array_merge($defaults, $def);
        return new $form_field($params);
    }

}

