<?php

/**
 * واسطه‌هایی را تعیین می‌کند که برای پرداخت از درگاه بانک ملت به کار می‌رود.
 * 
 * 
 * @author maso <mostafa.barmsohry@dpq.co.ir>
 *        
 */
class Bank_Views_Mellat {
	
	/**
	 * فراخوانی و دریافت بازخورد از درگاه بانک ملت
	 *
	 * @param unknown $request        	
	 * @param unknown $match        	
	 */
	public function mellat($request, $match) {
		$params = array ();
		return Pluf_Shortcuts_RenderToResponse ( 'index.html', $params, $request );
	}
}
