<?php
/**
 * واسط میان‌افزارها را تعیین می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
interface Pluf_Middleware {
	public function process_request(&$request);
	public function process_response($request, $response);
}