<?php
/**
 * خطای داخلی سیستم را به صورت کلی تعیین می‌کند
 * 
 * @author maso
 *
 */
class Pluf_HTTP_Error500 extends Pluf_Exception {
	public function __construct($message = null, $code = 5000, $previous = null) {
		$status = 500;
		$link = Pluf::f ( 'exception_5000_link', '/wiki/page/en/internal-error' );
		$developerMessage = __('Unknown exception happend.');
		parent::__construct ($message, $code, $previous, $status, $link, $developerMessage);
	}
}
