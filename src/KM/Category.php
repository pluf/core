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

/**
 * مدل داده‌ای برای برچسب گذاری ایجاد می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 */
class KM_Category extends Pluf_Model
{

    /**
     *
     * {@inheritDoc}
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'km_category';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ),
                'user' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'Pluf_User',
                        'blank' => true
                ),
                'community' => array(
                        'type' => 'Pluf_DB_Field_Boolean',
                        'blank' => false,
                        'verbose' => __('created by community'),
                        'help_text' => __(
                                'define wether a category created by the community or not')
                ),
                'parent' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'KM_Category',
                        'blank' => true
                ),
                'title' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 250,
                        'verbose' => __('title'),
                        'help_text' => __(
                                'the title of a category must only contain letters, digits or the dash character')
                ),
                'description' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 500,
                        'verbose' => __('description'),
                        'help_text' => __(
                                'the description of a category must only contain letters')
                ),
                'color' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 100,
                        'verbose' => __('color'),
                        'help_text' => __('color is and RGB reperesentation')
                ),
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('creation date')
                ),
                'modif_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('modification date')
                )
        );
    }

    /**
     * پیش ذخیره را انجام می‌دهد
     *
     * @param $create حالت
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave ($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }

    /**
     * حالت کار ایجاد شده را به روز می‌کند
     *
     * @see Pluf_Model::postSave()
     */
    function postSave ($create = false)
    {
        //
    }
}