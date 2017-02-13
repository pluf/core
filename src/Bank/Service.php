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
 * سرویس پرداخت‌ها را برای ماژولهای داخلی سیستم ایجاد می کند.
 * 
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *
 */
class Bank_Service
{

    /**
     * یک پرداخت جدید ایجاد می‌کند
     *
     * روالی که برای ایجاد یک پرداخت دنبال می‌شه می‌تونه خیلی متفاوت باشه
     * و ساختارهای رو برای خودش ایجاد کنه. برای همین ما پارامترهای ارسالی در
     * در خواست رو هم ارسال می‌کنیم.
     *
     * پرداخت ایجاد شده بر اساس اطلاعاتی است که با متغیر $reciptParam ارسال
     * می‌شود. این پارامترها
     * باید به صورت یک آرایه بوده و شامل موارد زیر باشد:
     *
     * <pre><code>
     * $param = array(
     * 'amount' => 1000, // مقدار پرداخت به ریال
     * 'title' => 'payment title',
     * 'description' => 'description',
     * 'email' => 'user@email.address',
     * 'phone' => '0917222222',
     * 'callbackURL' => 'http://.....',
     * 'backend' => 2
     * );
     * </code></pre>
     *
     * <ul>
     * <li>*amount: مقدار بر اساس ریال</li>
     * <li>*title: عنوان پرداخت</li>
     * <li>*description: توضیحات</li>
     * <li>email: رایانامه مشتری</li>
     * <li>phone: شماره تماس مشتری</li>
     * <li>callbackURL: آدرسی که بعد از تکمیل باید فراخوانی شود</li>
     * <li>*backend: درگاه پرداخت مورد نظر</li>
     * </ul>
     *
     * در نهایت باید موجودیتی تعیین بشه که این پرداخت رو می‌خواهیم براش ایجاد
     * کنیم.
     *
     * @param HTTP_REQUEST $request            
     * @param array $receiptParam            
     * @param Pluf_Model $owner            
     * @return Bank_Receipt
     */
    public static function create ($request, $param, $owner = null)
    {
        $form = new Bank_Form_ReceiptNew($param);
        $receipt = $form->save(false);
        $backend = $receipt->get_backend();
        $engine = $backend->get_engine();
        $engine->create($receipt);
        if (! is_null($owner)) {
            $receipt->owner_class = $owner->getClass();
            $receipt->owner_id = $owner->getId();
        }
        $receipt->create();
        return $receipt;
    }

    /**
     * حالت یک پرداخت را به روز می‌کند
     *
     * زمانی که یک پرداخت ایجاد می‌شود نیاز هست که بررسی کنیم که آیا پرداخت در
     * سمت بانک انجام شده است. این فراخوانی این بررسی رو انجام می‌ده و حالت
     * پرداخت رو به روز می‌کنه.
     *
     * @param Bank_Receipt $receipt            
     * @return Bank_Receipt
     */
    public static function update ($receipt)
    {
        $backend = $receipt->get_backend();
        $engine = $backend->get_engine();
        if ($engine->update($receipt)) {
            $receipt->update();
        }
        return $receipt;
    }

    /**
     * فهرست متورهای پرداخت موجود را تعیین می‌کند
     *
     * @return Bank_Engine_Mellat[]|Bank_Engine_Zarinpal[]
     */
    public static function engines ()
    {
        return array(
                new Bank_Engine_Mellat(),
                new Bank_Engine_Zarinpal()
        );
    }
}