<?php

/**
 * لایه نمایش پایه را ایجاد می کند. 
 * 
 * روش‌های پرداخت در نمونه‌های متفاوت از سیستم می‌تواند با تنظیم‌ها و درگاه‌های متفاوتی
 * انجام شود. این لایه نمایش ابزارهایی را فراهم می‌کند که برنامه‌های کاربری بتوانند
 * درگاه‌های موجود را شناسایی و آنها را برای کاربران نمایش دهند. 
 * 
 * @author maso
 *
 */
class Bank_Views_Main {
	
	/**
	 * فهرست تمام درگاه‌های موجود را تعیین می‌کند.
	 *
	 * @param unknown $request        	
	 * @param unknown $match        	
	 */
	public function find($request, $match) {
		$params = array ();
		return Pluf_Shortcuts_RenderToResponse ( 'index.html', $params, $request );
	}
}
