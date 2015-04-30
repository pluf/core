<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
#
# Plume Framework is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume Framework is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

/**
 * Form validation class. 
 *
 * This class is used to generate a form. You basically build it the
 * same way you build a model.
 *
 * The form handling is heavily inspired by the Django form handling.
 *
 */
class Pluf_Form implements Iterator, ArrayAccess
{
    /**
     * The fields of the form. 
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

    function __construct($data=null, $extra=array(), $label_suffix=null)
    {
        if ($data !== null) {
            $this->data = $data;
            $this->is_bound = true;
        }
        if ($label_suffix !== null) $this->label_suffix = $label_suffix;

        $this->initFields($extra);
        $this->f = new Pluf_Form_FieldProxy($this);
    }

    function initFields($extra=array())
    {
        throw new Exception('Definition of the fields not implemented.');
    }

    /**
     * Add the prefix to the form names.
     *
     * @param string Field name.
     * @return string Field name or field name with form prefix.
     */
    function addPrefix($field_name)
    {
        if ('' !== $this->prefix) {
            return $this->prefix.'-'.$field_name;
        }
        return $field_name;
    }

    /**
     * Check if the form is valid.
     *
     * It is also encoding the data in the form to be then saved.  It
     * is very simple as it leaves the work to the field. It means
     * that you can easily extend this form class to have a more
     * complex validation procedure like checking if a field is equals
     * to another in the form (like for password confirmation) etc.
     *
     * @param array Associative array of the request
     * @return array Array of errors
     */
    function isValid()
    {
        if ($this->is_valid !== null) {
            return $this->is_valid;
        }
        $this->cleaned_data = array();
        $this->errors = array();
        $form_methods = get_class_methods($this);
        $form_vars = get_object_vars($this);
        foreach ($this->fields as $name=>$field) {
            $value = $field->widget->valueFromFormData($this->addPrefix($name),
                                                       $this->data); 
            try {
                $value = $field->clean($value);
                $this->cleaned_data[$name] = $value;
                $method = 'clean_'.$name;
                if (in_array($method, $form_methods)) {
                    $value = $this->$method();
                    $this->cleaned_data[$name] = $value;
                } else if (array_key_exists($method, $form_vars) &&
                           is_callable($this->$method)) {
                    $value = call_user_func($this->$method, $this);
                    $this->cleaned_data[$name] = $value;
                }                        
            } catch (Pluf_Form_Invalid $e) {
                if (!isset($this->errors[$name])) $this->errors[$name] = array();
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
                if (!isset($this->errors['__all__'])) $this->errors['__all__'] = array();
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
     * Form wide cleaning function. That way you can check that if an
     * input is given, then another one somewhere is also given,
     * etc. If the cleaning is not ok, your method must throw a
     * Pluf_Form_Invalid exception.
     *
     * @return array Cleaned data.
     */
    public function clean()
    {
        return $this->cleaned_data;
    }

    /**
     * Method just called after the validation if the validation
     * failed.  This can be used to remove uploaded
     * files. $this->['cleaned_data'] will be available but of course
     * not fully populated and with possible garbage due to the error.
     *
     */
    public function failed()
    {
    }

    /**
     * Get initial data for a given field.
     *
     * @param string Field name.
     * @return string Initial data or '' of not defined.
     */
    public function initial($name)
    {
        if (isset($this->fields[$name])) {
            return $this->fields[$name]->initial;
        }
        return '';
    }

    /**
     * Get the top errors.
     */
    public function render_top_errors()
    {
        $top_errors = (isset($this->errors['__all__'])) ? $this->errors['__all__'] : array();
        array_walk($top_errors, 'Pluf_Form_htmlspecialcharsArray');
        return new Pluf_Template_SafeString(Pluf_Form_renderErrorsAsHTML($top_errors), true);
    }

    /**
     * Get the top errors.
     */
    public function get_top_errors()
    {
        return (isset($this->errors['__all__'])) ? $this->errors['__all__'] : array();
    }

    /**
     * Helper function to render the form.
     *
     * See render_p() for a usage example.
     *
     * @credit Django Project (http://www.djangoproject.com/)
     * @param string Normal row.
     * @param string Error row.
     * @param string Row ender.
     * @param string Help text HTML.
     * @param bool Should we display errors on a separate row.
     * @return string HTML of the form.
     */
    protected function htmlOutput($normal_row, $error_row, $row_ender, 
                                  $help_text_html, $errors_on_separate_row)
    {
        $top_errors = (isset($this->errors['__all__'])) ? $this->errors['__all__'] : array();
        array_walk($top_errors, 'Pluf_Form_htmlspecialcharsArray');
        $output = array();
        $hidden_fields = array();
        foreach ($this->fields as $name=>$field) {
            $bf = new Pluf_Form_BoundField($this, $field, $name);
            $bf_errors = $bf->errors;
            array_walk($bf_errors, 'Pluf_Form_htmlspecialcharsArray');
            if ($field->widget->is_hidden) {
                foreach ($bf_errors as $_e) {
                    $top_errors[] = sprintf(__('(Hidden field %1$s) %2$s'),
                                            $name, $_e);
                }
                $hidden_fields[] = $bf; // Not rendered
            } else {
                if ($errors_on_separate_row and count($bf_errors)) {
                    $output[] = sprintf($error_row, Pluf_Form_renderErrorsAsHTML($bf_errors));
                }
                if (strlen($bf->label) > 0) {
                    $label = htmlspecialchars($bf->label, ENT_COMPAT, 'UTF-8');
                    if ($this->label_suffix) {
                        if (!in_array(mb_substr($label, -1, 1), 
                                      array(':','?','.','!'))) {
                            $label .= $this->label_suffix;
                        }
                    }
                    $label = $bf->labelTag($label);
                } else {
                    $label = '';
                }
                if ($bf->help_text) {
                    // $bf->help_text can contains HTML and is not
                    // escaped.
                    $help_text = sprintf($help_text_html, $bf->help_text);
                } else {
                    $help_text = '';
                }
                $errors = '';
                if (!$errors_on_separate_row and count($bf_errors)) {
                    $errors = Pluf_Form_renderErrorsAsHTML($bf_errors);
                }
                $output[] = sprintf($normal_row, $errors, $label, 
                                    $bf->render_w(), $help_text);
            }
        }
        if (count($top_errors)) {
            $errors = sprintf($error_row, 
                              Pluf_Form_renderErrorsAsHTML($top_errors));
            array_unshift($output, $errors);
        }
        if (count($hidden_fields)) {
            $_tmp = '';
            foreach ($hidden_fields as $hd) {
                $_tmp .= $hd->render_w();
            }
            if (count($output)) {
                $last_row = array_pop($output);
                $last_row = substr($last_row, 0, -strlen($row_ender)).$_tmp
                    .$row_ender;
                $output[] = $last_row;
            } else {
                $output[] = $_tmp;
            }

        }
        return new Pluf_Template_SafeString(implode("\n", $output), true);
    }

    /**
     * Render the form as a list of paragraphs.
     */
    public function render_p()
    {
        return $this->htmlOutput('<p>%1$s%2$s %3$s%4$s</p>', '%s', '</p>', 
                                 ' %s', true);
    }

    /**
     * Render the form as a list without the <ul></ul>.
     */
    public function render_ul()
    {
        return $this->htmlOutput('<li>%1$s%2$s %3$s%4$s</li>', '<li>%s</li>', 
                                 '</li>', ' %s', false);
    }

    /**
     * Render the form as a table without <table></table>.
     */
    public function render_table()
    {
        return $this->htmlOutput('<tr><th>%2$s</th><td>%1$s%3$s%4$s</td></tr>',
                                 '<tr><td colspan="2">%s</td></tr>', 
                                 '</td></tr>', '<br /><span class="helptext">%s</span>', false);
    }

    /**
     * Overloading of the get method.
     *
     * The overloading is to be able to use property call in the
     * templates.
     */
    function __get($prop)
    {
        if (!in_array($prop, array('render_p', 'render_ul', 'render_table', 'render_top_errors', 'get_top_errors'))) {
            return $this->$prop;
        }
        return $this->$prop();
    }

    /**
     * Get a given field by key.
     */
    public function field($key)
    {
        return new Pluf_Form_BoundField($this, $this->fields[$key], $key);

    }

    /**
     * Iterator method to iterate over the fields.
     *
     * Get the current item.
     */
 	public function current()
    {
        $field = current($this->fields);
        $name = key($this->fields);
        return new Pluf_Form_BoundField($this, $field, $name);
    }

 	public function key()
    {
        return key($this->fields);
    }

 	public function next()
    {
        next($this->fields);
    }

 	public function rewind()
    {
        reset($this->fields);
    }

 	public function valid()
    {
        // We know that the boolean false will not be stored as a
        // field, so we can test against false to check if valid or
        // not.
        return (false !== current($this->fields));
    }

    public function offsetUnset($index) 
    {
        unset($this->fields[$index]);
    }
 
    public function offsetSet($index, $value) 
    {
        $this->fields[$index] = $value;
    }

    public function offsetGet($index) 
    {
        if (!isset($this->fields[$index])) {
            throw new Exception('Undefined index: '.$index);
        }
        return $this->fields[$index];
    }

    public function offsetExists($index) 
    {
        return (isset($this->fields[$index]));
    }
}


function Pluf_Form_htmlspecialcharsArray(&$item, $key)
{
    $item = htmlspecialchars($item, ENT_COMPAT, 'UTF-8');
}

function Pluf_Form_renderErrorsAsHTML($errors)
{
    $tmp = array();
    foreach ($errors as $err) {
        $tmp[] = '<li>'.$err.'</li>';
    }
    return '<ul class="errorlist">'.implode("\n", $tmp).'</ul>';
}
