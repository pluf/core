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

use Pluf\Form\BoundField;

/**
 * Field proxy to access a form field through {$form.f.fieldname} in a
 * template.
 */
class FieldProxy
{

    protected $form = null;

    public function __construct(&$form)
    {
        $this->form = $form;
    }

    /**
     * No control are performed.
     * If you access a non existing field it
     * will simply throw an error.
     */
    public function __get($field)
    {
        return new BoundField($this->form, $this->form->fields[$field], $field);
    }
}
