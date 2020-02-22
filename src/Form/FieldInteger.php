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
namespace Pluf\Form;

use Pluf\FormInvalidException;

class FieldInteger extends Field
{

    public $widget = 'Pluf_Form_Widget_TextInput';

    public $max = null;

    public $min = null;

    public function clean($value)
    {
        parent::clean($value);
        $value = $this->setDefaultEmpty($value);
        if ($this->multiple) {
            return $this->multiClean($value);
        } else {
            if ($value == '')
                return $value;
            if (! preg_match('/^[\+\-]?[0-9]+$/', $value)) {
                throw new FormInvalidException('The value must be an integer.');
            }
            $this->checkMinMax($value);
        }
        return (int) $value;
    }

    protected function checkMinMax($value)
    {
        if ($this->max !== null and $value > $this->max) {
            throw new FormInvalidException(sprintf('Ensure that this value is not greater than %1$d.', $this->max));
        }
        if ($this->min !== null and $value < $this->min) {
            throw new FormInvalidException(sprintf('Ensure that this value is not lower than %1$d.', $this->min));
        }
    }
}

