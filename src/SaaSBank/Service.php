<?php

/**
 * سرویس پرداخت‌ها را برای ماژولهای داخلی سیستم ایجاد می کند.
 * 
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaSBank_Service {
	
	/**
	 * یک پرداخت جدید ایجاد می‌کند
	 * 
	 * روالی که برای ایجاد یک پرداخت دنبال می‌شه می‌تونه خیلی متفاوت باشه 
	 * و ساختارهای رو برای خودش ایجاد کنه. برای همین ما پارامترهای ارسالی در
	 * در خواست رو هم ارسال می‌کنیم.
	 * 
	 * پرداخت ایجاد شده بر اساس اطلاعاتی است که با متغیر $reciptParam ارسال می‌شود. این پارامترها
	 * باید به صورت یک آرایه بوده و شامل موارد زیر باشد:
	 * 
	 * <pre><code>
	 * $param = array(
	 * 	'amount' => 1000, // مقدار پرداخت به ریال
	 * 	'title' => 'payment title',
	 * 	'description' => 'description',
	 * 	'email' => 'user@email.address'
	 * );
	 * </code></pre>
	 * 
	 * در نهایت باید موجودیتی تعیین بشه که این پرداخت رو می‌خواهیم براش ایجاد
	 * کنیم.
	 * 
	 * @param HTTP_REQUEST $request
	 * @param array $receiptParam
	 * @param Pluf_Model $owner
	 * @return SaaSBank_Receipt
	 */
	public static function create($request, $receiptParam, $owner) {
		$receipt = new SaaSBank_Receipt ();
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
	public static function update($receipt) {
		// XXX: maso, 1395: به روز کردن حالت پرداخت
		return $receipt;
	}
	
	/**
	 * فهرست متورهای پرداخت موجود را تعیین می‌کند
	 * 
	 * @return SaaSBank_Engine_Mellat[]|SaaSBank_Engine_Zarinpal[]
	 */
	public static function engines(){
		return array(
				new SaaSBank_Engine_Mellat(),
				new SaaSBank_Engine_Zarinpal()
		);
	}
}