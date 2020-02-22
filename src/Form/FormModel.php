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
namespace Pluf;


/**
 * Dynamic form validation class.
 *
 * This class is used to generate a form for a given model.
 */
class FormModel extends Form
{

    /**
     * The model for which the form applies.
     * 
     * 
     */
    public $model = null;

    function initFields ($extra = array())
    {
        $this->model = $extra['model'];
        if (isset($extra['fields'])) {
            // Only display a subset of the fields
            $cols = array();
            foreach ($extra['fields'] as $field) {
                $cols[$field] = $this->model->_a['cols'][$field];
            }
        } else {
            $cols = $this->model->_a['cols'];
        }
        foreach ($cols as $name => $def) {
            $db_field = new $def['type']('', $name);
            $def = array_merge(
                    array(
                            'blank' => true,
                            'verbose' => $name,
                            'help_text' => '',
                            'editable' => true
                    ), $def);
            if ($def['editable']) {
                // The 'model_instance' and 'name' are used by the
                // ManyToMany field.
                $def['model_instance'] = $this->model;
                $def['name'] = $name;
                if (null !== ($form_field = $db_field->formField($def))) {
                    $this->fields[$name] = $form_field;
                }
            }
        }
    }

    /**
     * Saves or updates the model in the database.
     *
     * @param
     *            bool Commit in the database or not. If not, the object
     *            is returned but not saved in the database.
     * @return Object Model with data set from the form.
     */
    function save ($commit = true)
    {
        if ($this->isValid()) {
            $this->model->setFromFormData($this->cleaned_data);
            if ($commit && $this->model->id) {
                $this->model->update();
            } elseif ($commit) {
                $this->model->create();
            }
            return $this->model;
        }
        throw new FormException(
                __('Cannot save the model from an invalid form.'), $this);
    }
}
