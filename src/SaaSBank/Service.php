<?php

/**
 * سرویس پرداخت‌ها را برای ماژولهای داخلی سیستم ایجاد می کند.
 * 
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaSBank_Service
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
     * @return SaaSBank_Receipt
     */
    public static function create ($request, $param, $owner = null)
    {
        $extra = array(
                'tenant' => $request->tenant
        );
        $form = new SaaSBank_Form_ReceiptNew($param, $extra);
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
     * @param SaaSBank_Receipt $receipt            
     * @return SaaSBank_Receipt
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
     * @return SaaSBank_Engine_Mellat[]|SaaSBank_Engine_Zarinpal[]
     */
    public static function engines ()
    {
        return array(
                new SaaSBank_Engine_Mellat(),
                new SaaSBank_Engine_Zarinpal()
        );
    }
}