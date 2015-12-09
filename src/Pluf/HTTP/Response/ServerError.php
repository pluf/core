<?php

/**
 * خرجی معادل با خطای داخلی سیستم
 * 
 * در صوورتی که سیستم با خطای داخلی متوقف شود، این خروجی به عنوان نتیجه تولید خواهد
 * شد.
 * 
 * @author maso
 *
 */
class Pluf_HTTP_Response_ServerError extends Pluf_HTTP_Response
{

    /**
     * یک نمونه جدید از این شئی ایجاد می‌کند
     *
     * در فرآیند ساخت تلاش می‌شو که الگویی برای خطای 500 بازیابی شده و به عنوان
     * نتیجه
     * برگردانده شود.
     * در صورتی که خطایی رخ دهد، یک متن پیش فرض به عنوان خطای نتیجه نمایش داده
     * خواهد شد.
     *
     * @param unknown $request            
     */
    function __construct ($exception, $mimetype = null, $request = null)
    {
        $admins = Pluf::f('admins', array());
        if (! ($exception instanceof Pluf_Exception)) {
            $exception = new Pluf_HTTP_Error500('Unknown exception', 5000, 
                    $exception);
        }
        
        /*
         * ارسال رایانامه برای تمام مدیران سیستم
         */
        if (count($admins) > 0) {
            // FIXME: maso, 1394: Get a nice stack trace and send it by emails.
            $stack = json_encode($exception->getTrace());
            $subject = $exception->getMessage();
            $subject = substr(strip_tags(nl2br($subject)), 0, 50) . '...';
            foreach ($admins as $admin) {
                $email = new Pluf_Mail($admin[1], $admin[1], $subject);
                $email->addTextMessage($stack);
                $email->sendMail();
            }
        }
        
        /*
         * ایجاد پیام مناسب برای کاربر
         */
        $mimetype = Pluf::f('mimetype_json', 'application/json') .
                 '; charset=utf-8';
        parent::__construct(json_encode($exception), $mimetype);
        $this->status_code = $exception->getStatus();
        return;
    }
}

