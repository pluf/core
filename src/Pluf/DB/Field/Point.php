<?php

/**
 * فیلد مکان را تعیین می‌کند
 * 
 * یک مکان دارای طول و عرض است که به صورت یک ساختار داده‌ای یکپارچه معرفی می‌شود. اغلب پایگاه‌های داده
 * این نوع ساختارهای داده‌ای را حمایت می‌کنند و ابزارهای مناسبی مانند اندیس گذاری و مقایسه برای کار
 * با این ساختارها ارائه کرده‌اند.
 * 
 * این کلاس ساختار نقطه در سیستم‌های جغرافیایی را تعیین می‌کند.
 * 
 * @author maso
 *
 */
class Pluf_DB_Field_Point extends Pluf_DB_Field
{

    public $type = 'point';

    public $extra = array();

    function formField ($def, $form_field = 'Pluf_Form_Field_Point')
    {
        return parent::formField($def, $form_field);
    }
}
