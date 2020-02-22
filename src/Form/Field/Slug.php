<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. http://dpq.co.ir
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace Pluf\Form\Field;

use Pluf;
use Pluf\Form\Field;
use Pluf\FormInvalidException;

class Slug extends Field
{

    /**
     * Name of the widget to use for build the forms.
     *
     * @var string
     */
    public $widget = 'Pluf_Form_Widget_TextInput';

    /**
     * Minimum size of field.
     *
     * Default to 1.
     *
     * @var int
     */
    public $min_size = 1;

    /**
     * Maximum size of field.
     *
     * Default to 50.
     *
     * @var int
     */
    public $max_size = 50;

    protected $_error_messages = array();

    public function __construct($params = array())
    {
        if (in_array($this->help_text, $this->empty_values)) {
            $this->help_text = __('The &#8220;slug&#8221; is the URL-friendly' . ' version of the name, consisting of ' . 'letters, numbers, underscores or hyphens.');
        }
        $this->_error_messages = array(
            'min_size' => __('Ensure this value has at most %1$d characters (it has %2$d).'),
            'max_size' => __('Ensure this value has at least %1$d characters (it has %2$d).')
        );

        parent::__construct($params);
    }

    /**
     * Removes any character not allowed and valid the size of the field.
     *
     * @see FormInvalidException::clean()
     * @throws FormInvalidException If the lenght of the field has not a valid size.
     */
    public function clean($value)
    {
        parent::clean($value);
        if ($value) {
            $value = \Pluf\DB\Field\Slug::slugify($value);
            $len = mb_strlen($value, Pluf::f('encoding', 'UTF-8'));
            if ($this->max_size < $len) {
                throw new FormInvalidException(sprintf($this->_error_messages['max_size'], $this->max_size, $len));
            }
            if ($this->min_size > $len) {
                throw new FormInvalidException(sprintf($this->_error_messages['min_size'], $this->min_size, $len));
            }
        } else
            $value = '';

        return $value;
    }

    /**
     *
     * @see \Pluf\Form\Field::widgetAttrs()
     */
    public function widgetAttrs($widget)
    {
        $attrs = array();
        if (! isset($widget->attrs['maxlength'])) {
            $attrs['maxlength'] = $this->max_size;
        } else {
            $this->max_size = $widget->attrs['maxlength'];
        }

        return $attrs;
    }
}
