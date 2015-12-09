<?php
/**
 * خروجی معادل با منابع مشغول
 *
 * در صورتی که یک منبع مورد درخواست کاربر در حال حاضر در دسترس نباشد، از این خروجی به عنوان نتیجه
 * استفاده خواهد شد.
 * این کلاس به صورت داخلی در سیستم استفاده می‌شود.
 *
 * @author maso
 *
 */
class Pluf_HTTP_Response_NotAvailable extends Pluf_HTTP_Response
{
	/**
	 * یک نمونه جدید از این شئی ایجاد می‌کند
	 * 
	 * در فرآیند ساخت تلاش می‌شو که الگویی برای خطای 503 بازیابی شده و به عنوان نتیجه
	 * برگردانده شود.
	 * در صورتی که خطایی رخ دهد، یک متن پیش فرض به عنوان خطای نتیجه نمایش داده خواهد شد.
	 * 
	 * @param unknown $request
	 */
    function __construct($request)
    {
        $content = '';
        try {
        	$tmpl = new Pluf_Template('503.html');
        	$params = array(
        			'query' => $request->query
        	);
        	if (is_null($request)) {
        		$context = new Pluf_Template_Context($params);
        	} else {
        		$context = new Pluf_Template_Context_Request($request, $params);
        	}
        	$content = $tmpl->render($context);
        	$mimetype = null;
        } catch (Exception $e) {
            $mimetype = 'text/plain';
            $content = sprintf('The requested URL %s is not available at the moment.'."\n"
                               .'Please try again later.'."\n\n".'503 - Service Unavailable',
                               Pluf_esc($request->query));
        }
        parent::__construct($content, $mimetype);
        $this->status_code = 503;
        $this->headers['Retry-After'] = 300; // retry after 5 minutes
    }
}
