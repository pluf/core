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
/**
 * 
 * @author pluf.ir
 * @since 2.0.0
 */
class Pluf_Form_Field_Varchar extends Pluf_Form_Field
{

    public $widget = 'Pluf_Form_Widget_TextInput';

    public $max_length = null;

    public $min_length = null;

    public function clean ($value)
    {
        parent::clean($value);
        if (in_array($value, $this->empty_values)) {
            $value = '';
        }
        $value_length = mb_strlen($value);
        if ($this->max_length !== null and $value_length > $this->max_length) {
            throw new Pluf_Form_Invalid(
                    sprintf(
                            __(
                                    'Ensure this value has at most %1$d characters (it has %2$d).'), 
                            $this->max_length, $value_length));
        }
        if ($this->min_length !== null and $value_length < $this->min_length) {
            throw new Pluf_Form_Invalid(
                    sprintf(
                            __(
                                    'Ensure this value has at least %1$d characters (it has %2$d).'), 
                            $this->min_length, $value_length));
        }
        return $value;
    }

    public function widgetAttrs ($widget)
    {
        if ($this->max_length !== null and in_array(get_class($widget), 
                array(
                        'Pluf_Form_Widget_TextInput',
                        'Pluf_Form_Widget_PasswordInput'
                ))) {
            return array(
                    'maxlength' => $this->max_length
            );
        }
        return array();
    }
}

