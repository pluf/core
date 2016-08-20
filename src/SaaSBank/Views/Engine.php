<?php

/**
 * 
 * 
 * @author maso <mostafa.barmsohry@dpq.co.ir>
 *        
 */
class SaaSBank_Views_Engine {
	
	/**
	 *
	 * @param unknown $request        	
	 * @param unknown $match        	
	 */
	public function find($request, $match) {
		// XXX: maso, 1395: 
		$items = SaaSBank_Service::engines();
		$page =  array(
				'items' => $items,
				'counts' => count($items),
				'current_page' => 0,
				'items_per_page' => count($items),
				'page_number' => 1
		);
        return new Pluf_HTTP_Response_Json($page);
	}

	/**
	 * 
	 * پارامترهایی که در این نمایش به عنوان ورودی در نظر گرفته می‌شوند عبارتند از:
	 * 
	 * <ul>
	 * 	<li>type: نوع متور جستجو را تعیین می‌کند</li>
	 * </ul>
	 * 
	 * @param unknown $request
	 * @param unknown $match
	 */
	public function get($request, $match) {
		// XXX: maso, 1395: 
	}
}
