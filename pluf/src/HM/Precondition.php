<?php

/**
 * پیش شرط‌های استاندارد را ایجاد می‌کند.
 *
 * در بسیاری از موارد لایه نمایش تنها با در نظر گرفتن برخی پیش شرط‌ها قابل دست رسی است
 * در این کلاس پیش شرطهای استاندارد تعریف شده است.
 */
class HM_Precondition {
	
	/**
	 * بررسی مشخص بودن آپارتمان
	 *
	 * این پیش شرط بررسی می‌کند که آیا آپارتمان برای انجام پردازش‌ها تعیین شده است
	 * یا نه.
	 *
	 * @param unknown $request        	
	 * @return boolean
	 */
	static public function paymentOwner($request, $payment) {
	    if($request->user->administrator) {
	        return true;
	    }
	    $app = $payment->get_part()->get_apartment();
		return SaaS_Precondition::applicationOwner($request, $app);
	}
	
}