<?php

class Pluf_Form_Field_Point extends Pluf_Form_Field
{
    public $widget = 'Pluf_Form_Widget_TextInput';
    public $max_length = null;
    public $min_length = null;

    public function clean($value)
    {
        parent::clean($value);
        if (in_array($value, $this->empty_values)) {
            $value = '';
        }
        $value_length = mb_strlen($value);
        if ($this->max_length !== null and $value_length > $this->max_length) {
            throw new Pluf_Form_Invalid(sprintf(__('Ensure this value has at most %1$d characters (it has %2$d).'), $this->max_length, $value_length));
        }
        if ($this->min_length !== null and $value_length < $this->min_length) {
            throw new Pluf_Form_Invalid(sprintf(__('Ensure this value has at least %1$d characters (it has %2$d).'), $this->min_length, $value_length));
        }
        return $value;
    }

    public function widgetAttrs($widget)
    {
        if ($this->max_length !== null and 
            in_array(get_class($widget), 
                     array('Pluf_Form_Widget_TextInput', 
                           'Pluf_Form_Widget_PasswordInput'))) {
            return array('maxlength'=>$this->max_length);
        }
        return array();
    }
}

