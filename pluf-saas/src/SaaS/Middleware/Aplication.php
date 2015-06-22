<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

/**
 * میان افزار نرم‌افزار
 *
 * نمونه‌های متفاوتی از یک نرم‌افزار می‌تواند ایجاد شده باشد. هر یک از این
 * نرم‌افزارها برای دسترسی کاربران روشی را تعیین می‌کنند. این لایه میانی
 * بر اساس روشی دسترسی کاربر تعیین می‌کند که نرم‌افزار معادل کدام است.
 *
 * در اینجا دو روش برای تعیین داده‌های آپارتمان در نظر گرفته شده اند که به ترتیب
 * عبارتند
 * از:
 *
 * - Subdomain name
 * - Url
 *
 * در روش اول اولین بخش از نام دامنه به عنوان پروفایل آپارتمان در نظر گرفته
 * می‌شود و
 * در روش دوم بخش اول آدرس به عنوان نام آپارتمان در نظر گرفته می‌شود.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Middleware_Aplication
{
    // implements Pluf_Middleware
    
    /**
     * تقاضای وارد شده را بررسی می‌کند.
     *
     * @param
     *            Pluf_HTTP_Request The request
     * @return bool false
     */
    function process_request (&$request)
    {
        $application_id = null;
        
        if (preg_match('#^/(\d+)|(\d+)/(.+)$#', $request->query, $match)) {
            $application_id = $match[1];
        } else 
            if (isset($request->COOKIE['_saas_application_'])) {
                $application_id = self::_decodeData(
                        $request->COOKIE['_saas_application_']);
            }
        
        try {
            $application = Pluf_Shortcuts_GetObjectOr404('SaaS_Application', 
                    $application_id);
            $request->application = $application;
        } catch (Pluf_Exception $ex) {
            $request->application = null;
        }
        
        // دامه در کوکی نیز قرار داده می‌شود
        return false;
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $response            
     */
    function process_response ($request, $response)
    {
        if (isset($request->application))
            $response->cookies['_saas_application_'] = self::_encodeData(
                    $request->application->id);
        return $response;
    }

    /**
     * کوکی مورد نیاز را کدگذاری می‌کند
     *
     * در اینجا فرض شده که کوکی داده‌ای امنیتی نیست، از این رو تنها کدگذاری ساده
     * کفایت کرده است. از این داده در تعیین دامنه فعالیت نیز استفاده می‌شود.
     *
     * @param
     *            mixed Data to encode
     * @return string Encoded data ready for the cookie
     */
    public static function _encodeData ($data)
    {
        return base64_encode(serialize($data));
    }

    /**
     * کوکی ایجاد شده را بازگشایی می‌کند.
     *
     * @param
     *            string Encoded data
     * @return mixed Decoded data
     */
    public static function _decodeData ($encoded_data)
    {
        return unserialize(base64_decode($encoded_data));
    }
}
