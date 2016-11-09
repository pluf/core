<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('SaaS_Shortcuts_GetSPAOr404');
Pluf::loadFunction('SaaS_Shortcuts_GetApplicationOr404');

/**
 * مدیریت SPA
 *
 * یکی از مهم‌ترین ساختارهای داده‌ای، ساختارهایی است که برای توصیف نرم‌افزارهای
 * کاربردی به کار برده می‌شود. این نمایش تمام راهکارهای مورد نیاز برای کار با
 * این ساختار داده‌ای را در سیستم فراهم کرده است.
 *
 * در این کلاس ابزارهایی برای لود کردن برنامه‌های کاربردی در نظر گرفته شده است.
 * به عنوان نمونه اگر کاربر بخواهد برنامه کاربردی پیش فرض برای یک ملک را اجرا
 * کند در این کلاس برای آن فراخوانی در نظر گرفته شده است.
 *
 * اکثر فراخوانی‌هایی که در این لایه نمایش ایجاد شده در پرونده urls-app2.php به
 * کار گرفته شده است.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Views_SPA
{
    /**
     * یک نرم افزار جدید را در سیستم ایجاد میکند.
     *
     * این فراخوانی موظف است که یک نرم افزار جدید در سیستم ایجاد کند.
     *
     * @note در حال حاضر این امکان فراهم نشده و نرم فزارها باید به صورت دستی
     * ایجاد شوند.
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public static function create ($request, $match)
    {}

    public static function update ($request, $match)
    {}

}