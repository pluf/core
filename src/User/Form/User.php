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
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('User_Shortcuts_UserDateFactory');

/**
 * فرم به روز رسانی اطلاعات کاربر را ایجاد می‌کند.
 */
class User_Form_User extends Pluf_Form
{

    public $user_data = null;

    /**
     * مقدار دهی فیلدها.
     *
     * @see Pluf_Form::initFields()
     */
    public function initFields ($extra = array())
    {
        if (array_key_exists('user', $extra))
            $this->user_data = $extra['user'];
        $this->user_data = User_Shortcuts_UserDateFactory($this->user_data);
        
        $this->fields['login'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => true,
                        'label' => __('login'),
                        'initial' => $this->user_data->login
                ));
        
        $this->fields['first_name'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('first name'),
                        'initial' => $this->user_data->first_name
                ));
        
        $this->fields['last_name'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('last name'),
                        'initial' => $this->user_data->last_name
                ));
        
        $this->fields['language'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('language'),
                        'initial' => $this->user_data->language
                ));
        
        $this->fields['password'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('your password'),
                        'initial' => ''
                ));
        
        $this->fields['email'] = new Pluf_Form_Field_Email(
                array(
                        'required' => !Pluf::f('user_signup_active', false),
                        'label' => __('Email address'),
                        'initial' => $this->user_data->email
                ));
        $this->fields['administrator'] = new Pluf_Form_Field_Boolean(
                array(
                        'required' => false,
                        'label' => __('administrator'),
                        'initial' => $this->user_data->administrator
                ));
        $this->fields['staff'] = new Pluf_Form_Field_Boolean(
                array(
                        'required' => false,
                        'label' => __('staff'),
                        'initial' => $this->user_data->staff
                ));
        $this->fields['active'] = new Pluf_Form_Field_Boolean(
                array(
                        'required' => false,
                        'label' => __('active'),
                        'initial' => $this->user_data->active
                ));
    }

    /**
     * مدل داده‌ای را ذخیره می‌کند
     *
     * مدل داده‌ای را بر اساس تغییرات تعیین شده توسط کاربر به روز می‌کند. در
     * صورتی
     * که پارامتر ورودی با نا درستی مقدار دهی شود تغییراد ذخیره نمی شود در غیر
     * این
     * صورت داده‌ها در پایگاه داده ذخیره می‌شود.
     *
     * @param $commit داده‌ها
     *            ذخیره شود یا نه
     * @return مدل داده‌ای ایجاد شده
     */
    function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('Cannot save the model from an invalid form.'));
        }
        $this->user_data->setFromFormData($this->cleaned_data);
        $user_active = Pluf::f('user_signup_active', false);
        $this->user_data->active = $user_active;
        if ($commit) {
            if (! $this->user_data->create()) {
                throw new Pluf_Exception(__('Fail to create new user?!'));
            }
        }
        return $this->user_data;
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
                    __('Cannot save the model from an invalid form.'));
        }
        $this->user_data->setFromFormData($this->cleaned_data);
        if ($commit) {
            // FIXME: maso, 1394: بررسی صحت رایانامه
            $this->user_data->update();
        }
        return $this->user_data;
    }

    /**
     * بررسی صحت نام خانوادگی
     *
     * @return string|unknown
     */
    function clean_last_name ()
    {
        $last_name = trim($this->cleaned_data['last_name']);
        if ($last_name == mb_strtoupper($last_name)) {
            return mb_convert_case(mb_strtolower($last_name), MB_CASE_TITLE, 
                    'UTF-8');
        }
        return $last_name;
    }

    /**
     * بررسی صحت نام
     *
     * @return string|unknown
     */
    function clean_first_name ()
    {
        $first_name = trim($this->cleaned_data['first_name']);
        if ($first_name == mb_strtoupper($first_name)) {
            return mb_convert_case(mb_strtolower($first_name), MB_CASE_TITLE, 
                    'UTF-8');
        }
        return $first_name;
    }

    /**
     * بررسی صحت رایانامه
     *
     * @throws Pluf_Form_Invalid
     * @return multitype:
     */
    function clean_email ()
    {
        $this->cleaned_data['email'] = mb_strtolower(
                trim($this->cleaned_data['email']));
        return $this->cleaned_data['email'];
    }
}
