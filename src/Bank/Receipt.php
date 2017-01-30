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
 * ساختارهای داده‌ای برای رسید را ایجاد می‌کند.
 * 
 * رسید عبارت است از یک مجموعه از داده‌ها که برای پرداخت به بانک ارسال 
 * می‌شود. این رسید زمانی که بانک تایید کند به روز شده و اطلاعات دریافتی
 * از بانک نیز به آن اضافه می شود.
 * 
 * از رسید در کارهای متفاوتی می‌توان استفاده کرد. برای نمونه پرداخت‌هایی
 * که برای خرید یک کالا توسط یک فرد انجام می‌شود در ساختار رسید قرار می‌گیرد.
 * 
 * @author maso
 *
 */
class Bank_Receipt extends Pluf_Model
{

    public $data = array();

    public $touched = false;

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * تمام فیلدهای مورد نیاز برای این مدل داده‌ای در این متد تعیین شده و به
     * صورت کامل ساختار دهی می‌شود.
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'bank_receipt';
        $this->_a['cols'] = array (
				/*
				 * داده‌های عمومی برای یک پرداخت
				 */
				'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ),
                'secure_id' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 64
                ),
                'amount' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'unique' => false
                ),
                'title' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 50
                ),
                'description' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 200
                ),
                'email' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 100
                ),
                'phone' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 100
                ),
                // مسیر را تعیین می‌کند که بعد از تکمیل باید فراخوانی شود
                'callbackURL' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 200
                ),
                
                'payRef' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 200
                ),
                // مسیری رو تعیین می‌کنه که برای تکمیل خرید باید دنبال کنیم
                'callURL' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 200
                ),
                'payMeta' => array(
                        'type' => 'Pluf_DB_Field_Text',
                        'blank' => false
                ),
                'backend' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'Bank_Backend',
                        'blank' => false,
                        'relate_name' => 'backend'
                ),
				/*
				 * مالک این پرداخت را تعیین می‌کند. این مالک می‌تواند هر موجودیتی در
				 * سیستم باشد.
				 */
                'owner_id' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'verbose' => 'owner ID'
                ),
                'owner_class' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 50
                ),
                
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => 'creation date'
                ),
                'modif_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => 'modification date'
                )
        );
        $this->_a['views'] = array();
    }

    /**
     * پیش ذخیره را انجام می‌دهد
     *
     * در این فرآیند نیازهای ابتدایی سیستم به آن اضافه می‌شود. این نیازها
     * مقادیری هستند که
     * در زمان ایجاد باید تعیین شوند. از این جمله می‌توان به کاربر و تاریخ اشاره
     * کرد.
     *
     * @param $create حالت
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave ($create = false)
    {
        $this->payMeta = serialize($this->data);
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }

    /**
     * حالت آپارتمان ایجاد شده را به روز می‌کند
     *
     * @see Pluf_Model::postSave()
     */
    function postSave ($create = false)
    {
        if (! is_null($this->callbackURL) &&
                 strpos($this->callbackURL, '{secure_id}')) {
            $this->callbackURL = str_replace('{secure_id}', $this->secure_id, 
                    $this->callbackURL);
            $this->update();
        }
    }

    /**
     * داده‌های ذخیره شده را بازیابی می‌کند
     *
     * تمام داده‌هایی که با کلید payMeta ذخیره شده را بازیابی می‌کند.
     */
    function restore ()
    {
        $this->data = unserialize($this->payMeta);
    }

    /**
     * تمام داده‌های موجود در نشت را پاک می‌کند.
     *
     * تمام داده‌های ذخیره شده در نشست را پاک می‌کند.
     */
    function clear ()
    {
        $this->data = array();
        $this->touched = true;
    }

    /**
     * تعیین یک داده در نشست
     *
     * با استفاده از این فراخوانی می‌توان یک داده با کلید جدید در نشست ایجاد
     * کرد. این کلید برای دستیابی‌های
     * بعد مورد استفاده قرار خواهد گرفت.
     *
     * @param
     *            کلید داده
     * @param
     *            داده مورد نظر. در صورتی که مقدار آن تهی باشد به معنی
     *            حذف است.
     */
    function setMeta ($key, $value = null)
    {
        if (is_null($value)) {
            unset($this->data[$key]);
        } else {
            $this->data[$key] = $value;
        }
        $this->touched = true;
    }

    /**
     * داده معادل با کلید تعیین شده را برمی‌گرداند
     *
     * در صورتی که داده تعیین نشده بود مقدار پیش فرض تعیین شده به عنوان نتیجه
     * این فراخوانی
     * برگردانده خواهد شد.
     */
    function getMeta ($key = null, $default = '')
    {
        if (is_null($key)) {
            return parent::getData();
        }
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return $default;
        }
    }

    /**
     * آیا پرداخت انجام شده یا نه
     *
     * در صورتی که پرداخت انجام شده باشد برای آن لینک مرجع وجود دارد. این
     * فراخوانی بررسی می‌کند که آیا شمار مرجع وچود دارد یا نه.
     *
     * @return boolean
     */
    function isPayed ()
    {
        return ! is_null($this->payRef);
    }
}