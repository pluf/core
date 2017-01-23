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
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('KM_Shortcuts_categoryDateFactory');

/**
 * فرم به روز رسانی اطلاعات کاربر را ایجاد می‌کند.
 */
class KM_Form_CategoryUpdate extends KM_Form_CategoryCreate
{

    /**
     * مقدار دهی فیلدها.
     *
     * @see Pluf_Form::initFields()
     */
    public function initFields ($extra = array())
    {
        $this->category = $extra['category'];
        $this->fields['title'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('title'),
                        'initial' => $this->category->title
                ));
        
        $this->fields['description'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('description'),
                        'initial' => $this->category->description
                ));
        
        $this->fields['color'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('color'),
                        'initial' => $this->category->color
                ));
    }

    /**
     * داده‌های کاربر را به روز می‌کند.
     *
     * @throws Pluf_Exception
     */
    function update ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('cannot update a category from an invalid form'));
        }
        $this->category->setFromFormData($this->cleaned_data);
        // $this->category->user = $this->user;
        if ($commit) {
            $this->category->update();
        }
        return $this->category;
    }

}
