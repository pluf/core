<?php

class SaaSNewspaper_Util
{

    /**
     * با این تابع می‌توان یک ایمیل ارسال کرد. تنظیمات مربوط به سرور ایمیل از pluf گرفته می شود.
     * 
     * @param unknown $from آدرس فرستنده
     * @param unknown $to آدرس گیرنده یا گیرنده‌ها. می توان یک رشته شامل آدرس تمام گیرنده‌ها که با کاما از هم
     * جدا شده‌اند در این آرگومان قرار داد و یا آرایه‌ای شامل آدرس‌های گیرنده‌ها. آدرس گیرنده‌ها باید مطابق با
     * استاندارد RFC822 باشد. 
     * @param unknown $subject موضوع ایمیل
     * @param unknown $message متن ایمیل که می تواند متن ساده یا html باشد
     * @param string $htmlMsg در صورتی که متن ایمیل html باشد این آرگومان باید true باشد. مقدار پیش‌فرض false است.
     * 
     * @return Pluf_HTTP_Response_Json در صورتی که ارسال ایمیل با خطا مواجه شود شی مربوط به خطا برگردانده می‌شود در
     * غیر این صورت مقدار true برگردانده می‌شود 
     */
    public static function sendMail($from, $to, $subject, $message, $htmlMsg = false)
    {
        $email = new Pluf_Mail($from, $to, $subject);
        // $img_id = $email->addAttachment('/var/www/html/img/pic.jpg', 'image/jpg');
        if ($htmlMsg) {
            $email->addHtmlMessage($message);
        } else {
            $email->addTextMessage($message);
        }
        $res = $email->sendMail();
        
        if (is_a($res, 'PEAR_Error')) {
            return $res;
        }
        return true;
    }

    /**
     * تابع زیر برای تست تنظیمات و صحت عملکرد ارسال ایمیل به روش smtp استفاده می‌شود
     *
     * @param unknown $host            
     * @param unknown $username            
     * @param unknown $password            
     * @param unknown $from            
     * @param unknown $to            
     */
    public static function testSendMail($host, $username, $password, $from, $to)
    {
        require_once "Mail.php";
        
        // $host = "mail.dpq.co.ir";
        // $username = "info@dpq.co.ir";
        // $password = "g1h2m3";
        $body = "Hi,\n\nHow are you?";
        
        $smtp = Mail::factory('smtp', array(
            'host' => $host,
            'auth' => true,
            'username' => $username,
            'password' => $password
        ));
        
        $headers = array(
            'From' => $from,
            'To' => $to,
            'Subject' => "Send mail test!"
        );
        
        $mail = $smtp->send($to, $headers, $body);
        
        if (PEAR::isError($mail)) {
            echo ("<p>" . $mail->getMessage() . "</p>");
        } else {
            echo ("<p>Message successfully sent!</p>");
        }
    }
}