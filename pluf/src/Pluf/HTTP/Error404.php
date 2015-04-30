<?php
/**
 * ساختار خطای کلی کاربر را تعیین می‌کند
 * 
 * @author maso
 *
 */
class Pluf_HTTP_Error404 extends Pluf_Exception {
	public function __construct($message = 'Resource not found.', $code = 404, $previous = null) {
		$status = 404;
		$link = Pluf::f ( 'exception_400_link', '/wiki/page/en/internal-error' );
		$developerMessage = 'Unknown exception happend.';
		parent::__construct ( $message, $code, $previous, $status, $link, $developerMessage );
	}
}
