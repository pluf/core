<?php
Pluf::loadFunction ( 'Pluf_HTTP_URL_urlForView' );
Pluf::loadFunction ( 'Pluf_Shortcuts_RenderToResponse' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetObjectOr404' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetFormForModel' );

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Views_Application extends Pluf_Views {
	
	/**
	 * پیش شرط‌های دسترسی به فهرست نرم‌افزارها
	 *
	 * @var unknown
	 */
	public $applications_precond = array ();
	
	/**
	 * فهرستی از نرم‌افزارها ایجاد می‌کند
	 *
	 * @param unknown_type $request        	
	 * @param unknown_type $match        	
	 */
	public function applications($request, $match) {
		/*
		 * TODO: maso, 1394: پارامترهای جستجو استفاده نشده است.
		 * سه پارامتر زیر باید در جستجو استفاده شود، اگر توسط کاربر تعیین شده باشد
		 * -after
		 * -before
		 * -count
		 */
		if ($request->method != 'GET') {
			throw new Pluf_Exception_GetMethodSuported ();
		}
		// maso, 1394: گرفتن فهرست مناسبی از آپارتمان‌ها
		$pag = new Pluf_Paginator ( new SaaS_Application () );
		$list_display = array (
				'id' => __ ( 'application id' ),
				'title' => __ ( 'title' ),
				'creation_dtime' => __ ( 'create' ) 
		);
		$search_fields = array ();
		$sort_fields = array (
				'creation_dtime' 
		);
		$pag->configure ( $list_display, $search_fields, $sort_fields );
		$pag->action = array ();
		$pag->items_per_page = 10;
		$pag->no_results_text = __ ( 'No apartment is added yet.' );
		$pag->sort_order = array (
				'creation_dtime',
				'DESC' 
		);
		$pag->setFromRequest ( $request );
		return new Pluf_HTTP_Response_Json ( $pag->render_object () );
	}
	
	/**
	 *
	 * @var unknown
	 */
	public $members_precond = array (
			'Pluf_Precondition::loginRequired'
	);
	
	/**
	 *
	 * @param unknown $request        	
	 * @param unknown $match        	
	 */
	public function members($request, $match) {
		$application_id = $match[1];
		$application = Pluf_Shortcuts_GetObjectOr404 ( 'SaaS_Application', $application_id );
		return new Pluf_HTTP_Response_Json ( $application->getMembershipData('txt') );
	}
}