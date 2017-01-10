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
Pluf::loadFunction('Inbox_Shortcuts_messageDataFactory');

/**
 * فرم ایجاد یک پیام جدید
 *
 * از این فرم تنها برای ایجاد یک پیام استفاده می‌شود.
 */
class Inbox_Form_Message extends Pluf_Form
{

    public $owner = null;

    public $object = null;
    
    public $message = null;

    /**
     * مقدار دهی فیلدها.
     *
     * @see Pluf_Form::initFields()
     */
    public function initFields ($extra = array())
    {
        $this->message = Inbox_Shortcuts_messageDataFactory();
        
        $this->owner = $extra['owner'];
        $this->object = $extra['object'];
        
        $this->fields['title'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => true,
                        'label' => __('message title'),
                        'initial' => $this->message->title
                ));
        
        $this->fields['content'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('message content'),
                        'initial' => $this->message->content
                ));
        
        $this->fields['summery'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('message summery'),
                        'initial' => $this->message->summery
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
                    __('Cannot save the message from an invalid form.'));
        }
        $this->message->setFromFormData($this->cleaned_data);
        $this->message->from_id = $this->owner->id;
        $this->message->from_class = $this->owner->_a['model'];
        $this->message->to_id = $this->object->id;
        $this->message->to_class = $this->object->_a['model'];
        if ($commit) {
            $this->message->create();
        }
        return $this->message;
    }
}
