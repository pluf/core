<?php
/**
 * خروجی معادل با منابع یافت نشده
 * 
 * در صورتی که یک منبع مورد درخواست کاربر یافت نشود، از این خروجی به عنوان نتیجه
 * استفاده خواهد شد.
 * این کلاس به صورت داخلی در سیستم استفاده می‌شود.
 * 
 * @author maso
 *
 */
class Pluf_HTTP_Response_NotFound extends Pluf_HTTP_Response {
	/**
	 * یک نمونه جدید از این شئی ایجاد می‌کند
	 *
	 * در فرآیند ساخت تلاش می‌شو که الگویی برای خطای 404 بازیابی شده و به عنوان نتیجه
	 * برگردانده شود.
	 * در صورتی که خطایی رخ دهد، یک متن پیش فرض به عنوان خطای نتیجه نمایش داده خواهد شد.
	 *
	 * @param unknown $request        	
	 */
	function __construct($request) {
		if (Pluf::f ( 'rest', false )) {
			$mimetype = Pluf::f ( 'mimetype_json', 'application/json' ) . '; charset=utf-8';
			$exception = new Pluf_HTTP_Error404 ();
			parent::__construct ( json_encode ( $exception ), $mimetype );
			$this->status_code = 404;
			return;
		}
		
		$content = '';
		try {
			$tmpl = new Pluf_Template ( '404.html' );
			$params = array (
					'query' => $request->query 
			);
			if (is_null ( $request )) {
				$context = new Pluf_Template_Context ( $params );
			} else {
				$context = new Pluf_Template_Context_Request ( $request, $params );
			}
			$content = $tmpl->render ( $context );
			$mimetype = null;
		} catch ( Exception $e ) {
			$mimetype = 'text/plain';
			$content = sprintf ( 'The requested URL %s was not found on this server.' . "\n" . 'Please check the URL and try again.' . "\n\n" . '404 - Not Found', Pluf_esc ( $request->query ) );
		}
		parent::__construct ( $content, $mimetype );
		$this->status_code = 404;
	}
}
