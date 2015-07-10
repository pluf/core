<?php
Pluf::loadFunction ( 'Pluf_HTTP_URL_urlForView' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetObjectOr404' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetFormForModel' );

/**
 * @ingroup views
 * @brief این کلاس نمایش‌های اصلی سیستم را ایجاد می‌کند.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *         @date 1394
 */
class Wiki_Views_Page {
	
	/**
	 * پیش شرط‌های دستیابی به نرم‌افزار صفحه اصلی
	 *
	 * @var array $house_precond
	 */
	public $index_precond = array ();
	
	/**
	 * نمایش برگه اصلی سایت
	 *
	 * در این نمایش اطلاعات کلی کارگزار نمایش داده می‌شود. این نمایش می‌تواند در حالت واسط
	 * برنامه سازی نیز به کار رود.
	 * این فراخوانی که معادل با ورودی کاربر به سیستم است، منجر به بازیابی نرم‌افزار home
	 * می‌شود.
	 *
	 * @param
	 *        	$request
	 * @param
	 *        	$match
	 */
	public function index($request, $match) {
		$languate = $match [1];
		$pageTitle = $match [2];
		$repos = Pluf::f ( 'wiki_repositories', array () );
		foreach ( $repos as $name => $path ) {
			$filename = $path . DIRECTORY_SEPARATOR . $languate . DIRECTORY_SEPARATOR . $pageTitle . ".md";
			if (is_readable ( $filename )) {
				$page = new Wiki_Models_Page ();
				$page->title = $pageTitle;
				$page->language = $languate;
				$page->summary = "";
				$myfile = fopen($filename, "r") or die("Unable to open file!");
				$page->content = fread($myfile,filesize($filename));
				fclose($myfile);
				$page->creation_dtime = gmdate ( 'Y-m-d H:i:s' );
				$page->modif_dtime = gmdate ( 'Y-m-d H:i:s' );
				return new Pluf_HTTP_Response_Json ( $page );
			}
		}
		throw new Pluf_Exception ("Page not found.");
	}
}