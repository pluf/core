<?php
Pluf::loadFunction ( 'Pluf_Shortcuts_GetObjectOr404' );
Pluf::loadFunction ( 'HM_Shortcuts_apartmentFactory' );
Pluf::loadFunction ( 'HM_Shortcuts_partFactory' );

/**
 * میان افزار نرم‌افزار
 *
 * نمونه‌های متفاوتی از یک نرم‌افزار می‌تواند ایجاد شده باشد. هر یک از این
 * نرم‌افزارها برای دسترسی کاربران روشی را تعیین می‌کنند. این لایه میانی
 * بر اساس روشی دسترسی کاربر تعیین می‌کند که نرم‌افزار معادل کدام است.
 *
 * در اینجا دو روش برای تعیین داده‌های آپارتمان در نظر گرفته شده اند که به ترتیب عبارتند
 * از:
 *
 * - Subdomain name
 * - Url
 *
 * در روش اول اولین بخش از نام دامنه به عنوان پروفایل آپارتمان در نظر گرفته می‌شود و
 * در روش دوم بخش اول آدرس به عنوان نام آپارتمان در نظر گرفته می‌شود.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Middleware_Aplication { // implements Pluf_Middleware
	
	/**
	 * تقاضای وارد شده را بررسی می‌کند.
	 *
	 * @param
	 *        	Pluf_HTTP_Request The request
	 * @return bool false
	 */
	function process_request(&$request) {
		$application_id = null;
		
		if (preg_match ( '#^/(\d+)|(\d+)/(.+)$#', $request->query, $match )) {
			$application_id = $match [1];
		}
		
		try{
			$application = Pluf_Shortcuts_GetObjectOr404 ( 'SaaS_Application', $application_id );
			$request->application = $application;
		} catch (Pluf_Exception $ex){
			$request->application = null;
		}
		
		// دامه در کوکی نیز قرار داده می‌شود
		return false;
	}
	
}
