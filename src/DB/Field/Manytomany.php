<?php

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
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
namespace Pluf\DB\Field;

use Pluf\DB\Field;

class Manytomany extends Field
{

    public $type = 'manytomany';

    function formField($def, $form_field = 'Pluf_Form_Field_Integer')
    {
        $method = 'get_' . $def['name'] . '_list';
        $def['multiple'] = true;
        $def['initial'] = array();
        foreach ($def['model_instance']->$method() as $item) {
            $def['initial'][(string) $item] = $item->id;
        }
        $def['choices'] = array();
        $model = new $def['model']();
        foreach ($model->getList() as $item) {
            $def['choices'][(string) $item] = $item->id;
        }
        if (! isset($def['widget'])) {
            $def['widget'] = 'Pluf_Form_Widget_SelectMultipleInput';
        }
        return parent::formField($def, $form_field);
    }
}
