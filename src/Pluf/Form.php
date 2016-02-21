<?php

/**
 * مدیریت و اعتبار سنجی فرم‌ها
 *
 * از این کلاس برای اعتبار سنجی فرم‌ها و تولید داده‌های معادل استفاده می‌شود. از
 * این کلاس همانند مدل داده‌ای استفاده می‌شود. با استفاده از این کلاس می‌توان داده‌های
 * اضافه ارسال شده را حذف کرده و در صورت نا مناسب بودن پارامترهای دریافتی خطای
 * مناسب ایجاد کرد.
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 */
class Pluf_Form implements Iterator, ArrayAccess
{

    /**
     * The fields of the form.
     *
     *
     * They are the fully populated Pluf_Form_Field_* of the form. You
     * define them in the initFields method.
     */
    public $fields = array();

    /**
     * Prefix for the names of the fields.
     */
    public $prefix = '';

    public $id_fields = 'id_%s';

    public $data = array();

    public $cleaned_data = array();

    public $errors = array();

    public $is_bound = false;

    public $f = null;

    public $label_suffix = ':';

    protected $is_valid = null;

    function __construct ($data = null, $extra = array(), $label_suffix = null)
    {
        if ($data !== null) {
            $this->data = $data;
            $this->is_bound = true;
        }
        if ($label_suffix !== null)
            $this->label_suffix = $label_suffix;
        
        $this->initFields($extra);
        $this->f = new Pluf_Form_FieldProxy($this);
    }

    function initFields ($extra = array())
    {
        throw new Exception('Definition of the fields not implemented.');
    }

    /**
     * Add the prefix to the form names.
     *
     * @param
     *            string Field name.
     * @return string Field name or field name with form prefix.
     */
    function addPrefix ($field_name)
    {
        if ('' !== $this->prefix) {
            return $this->prefix . '-' . $field_name;
        }
        return $field_name;
    }

    /**
     * Check if the form is valid.
     *
     * It is also encoding the data in the form to be then saved. It
     * is very simple as it leaves the work to the field. It means
     * that you can easily extend this form class to have a more
     * complex validation procedure like checking if a field is equals
     * to another in the form (like for password confirmation) etc.
     *
     * @param
     *            array Associative array of the request
     * @return array Array of errors
     */
    function isValid ()
    {
        if ($this->is_valid !== null) {
            return $this->is_valid;
        }
        $this->cleaned_data = array();
        $this->errors = array();
        $form_methods = get_class_methods($this);
        $form_vars = get_object_vars($this);
        foreach ($this->fields as $name => $field) {
            $value = $field->widget->valueFromFormData($this->addPrefix($name), 
                    $this->data);
            try {
                $value = $field->clean($value);
                $this->cleaned_data[$name] = $value;
                $method = 'clean_' . $name;
                if (in_array($method, $form_methods)) {
                    $value = $this->$method();
                    $this->cleaned_data[$name] = $value;
                } else 
                    if (array_key_exists($method, $form_vars) &&
                             is_callable($this->$method)) {
                        $value = call_user_func($this->$method, $this);
                        $this->cleaned_data[$name] = $value;
                    }
            } catch (Pluf_Form_Invalid $e) {
                if (! isset($this->errors[$name]))
                    $this->errors[$name] = array();
                $this->errors[$name][] = $e->getMessage();
                if (isset($this->cleaned_data[$name])) {
                    unset($this->cleaned_data[$name]);
                }
            }
        }
        if (empty($this->errors)) {
            try {
                $this->cleaned_data = $this->clean();
            } catch (Pluf_Form_Invalid $e) {
                if (! isset($this->errors['__all__']))
                    $this->errors['__all__'] = array();
                $this->errors['__all__'][] = $e->getMessage();
            }
        }
        if (empty($this->errors)) {
            $this->is_valid = true;
            return true;
        }
        // as some errors, we do not have cleaned data available.
        $this->failed();
        $this->cleaned_data = array();
        $this->is_valid = false;
        return false;
    }

    /**
     * فرآیند اصلی پاک کردن داده‌ها در فرم
     *
     * با استفاده از این فراخوانی می‌توان تمام پارامترهای یک فرم را بررسی کرد.
     * تفاوت اصلی این بررسی با سایر موارد این است که در اینجا تمام پارامترها به
     * صورت همزمان در نظر گرفته خواهد شد.
     *
     * در صورتی که بررسی به یک خطا روبرو شده باید خطای Pluf_Form_Invalid صادر
     * شود.
     *
     * @note به صورت پیش فرض تمام داده‌هایی که تهی باشد از فرهم حذف خواهد شد. در
     * مورد
     * سایر کاربرها این متد باید بازنویسی شود.
     *
     * @return array Cleaned data.
     */
    public function clean ()
    {
        foreach ($this->cleaned_data as $key => $value) {
            if (is_null($value) || $value === '')
                unset($this->cleaned_data[$key]);
        }
        return $this->cleaned_data;
    }

    /**
     * Method just called after the validation if the validation
     * failed.
     * This can be used to remove uploaded
     * files. $this->['cleaned_data'] will be available but of course
     * not fully populated and with possible garbage due to the error.
     */
    public function failed ()
    {}

    /**
     * Get initial data for a given field.
     *
     * @param
     *            string Field name.
     * @return string Initial data or '' of not defined.
     */
    public function initial ($name)
    {
        if (isset($this->fields[$name])) {
            return $this->fields[$name]->initial;
        }
        return '';
    }

    /**
     * Get the top errors.
     */
    public function render_top_errors ()
    {
        $top_errors = (isset($this->errors['__all__'])) ? $this->errors['__all__'] : array();
        array_walk($top_errors, 'Pluf_Form_htmlspecialcharsArray');
        return new Pluf_Template_SafeString(
                Pluf_Form_renderErrorsAsHTML($top_errors), true);
    }

    /**
     * Get the top errors.
     */
    public function get_top_errors ()
    {
        return (isset($this->errors['__all__'])) ? $this->errors['__all__'] : array();
    }

    /**
     * Get a given field by key.
     */
    public function field ($key)
    {
        return new Pluf_Form_BoundField($this, $this->fields[$key], $key);
    }

    /**
     * Iterator method to iterate over the fields.
     *
     * Get the current item.
     */
    public function current ()
    {
        $field = current($this->fields);
        $name = key($this->fields);
        return new Pluf_Form_BoundField($this, $field, $name);
    }

    public function key ()
    {
        return key($this->fields);
    }

    public function next ()
    {
        next($this->fields);
    }

    public function rewind ()
    {
        reset($this->fields);
    }

    public function valid ()
    {
        // We know that the boolean false will not be stored as a
        // field, so we can test against false to check if valid or
        // not.
        return (false !== current($this->fields));
    }

    public function offsetUnset ($index)
    {
        unset($this->fields[$index]);
    }

    public function offsetSet ($index, $value)
    {
        $this->fields[$index] = $value;
    }

    public function offsetGet ($index)
    {
        if (! isset($this->fields[$index])) {
            throw new Exception('Undefined index: ' . $index);
        }
        return $this->fields[$index];
    }

    public function offsetExists ($index)
    {
        return (isset($this->fields[$index]));
    }
}
