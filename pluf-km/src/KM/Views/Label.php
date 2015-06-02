<?php
Pluf::loadFunction ( 'Pluf_HTTP_URL_urlForView' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetObjectOr404' );
Pluf::loadFunction ( 'Pluf_Shortcuts_GetFormForModel' );

/**
 *
 * @date 1394
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 */
class KM_Views_Label {
	
	/**
	 * پیش شرط‌های دستیابی به نرم‌افزار صفحه اصلی
	 *
	 * @var array $house_precond
	 */
	public $labels_precond = array (
			'Pluf_Precondition::loginRequired' 
	);
	
	/**
	 *
	 * @param
	 *        	$request
	 * @param
	 *        	$match
	 */
	public function labels($request, $match) {
		if ($request->method != 'GET') {
			throw new Pluf_Exception_GetMethodSuported ();
		}
		/*
		 * maso, 1394: پارامترهای جستجو استفاده نشده است.
		 * سه پارامتر زیر باید در جستجو استفاده شود، اگر توسط کاربر تعیین شده باشد
		 * -after
		 * -before
		 * -count
		 */
		
		// maso, 1394: گرفتن فهرست مناسبی از پیام‌ها
		// Paginator to paginate messages
		$pag = new Pluf_Paginator ( new KM_Label () );
		$pag->list_filters = array (
				'user',
				'title',
				'community' 
		);
		$pag->forced_where = new Pluf_SQL ( 'user=%s', array (
				$request->user->id 
		) );
		$pag->action = array (
				'Label_Views_Label::label' 
		);
		$list_display = array (
				'title' => __ ( 'Message title' ),
				'description' => __ ( 'description' ),
				'color' => __ ( 'color' ) 
		);
		$search_fields = array (
				'title',
				'description' 
		);
		$sort_fields = array (
				'id',
				'title',
				'description',
				'color',
				'creation_date',
				'modif_dtime' 
		);
		$pag->configure ( $list_display, $search_fields, $sort_fields );
		$pag->items_per_page = $this->getListCount ( $request );
		$pag->setFromRequest ( $request );
		return new Pluf_HTTP_Response_Json ( $pag->render_object () );
	}
	
	/**
	 * پیش نیازهای ایجاد یک برچسب را تعیین می‌کند
	 *
	 * @var unknown
	 */
	public $create_precond = array (
			'Pluf_Precondition::loginRequired' 
	);
	
	/**
	 * یک برچسب جدید در سیستم ایجاد می‌کند
	 *
	 * @param unknown $request        	
	 * @param unknown $match        	
	 * @throws Pluf_Exception_NotImplemented
	 */
	public function create($request, $match) {
		if ($request->method != 'POST') {
			throw new Pluf_Exception_PostMethodSuported ();
		}
		$extra = array (
				'user' => $request->user 
		);
		$form = new KM_Form_Label ( array_merge ( $request->POST, $request->FILES ), $extra );
		$cuser = $form->save ();
		$request->user->setMessage ( sprintf ( __ ( 'The label %s has been created.' ), ( string ) $cuser ) );
		
		// Return response
		return new Pluf_HTTP_Response_Json ( $cuser );
	}
	
	/**
	 * پیش نیازهای دستکاری یک برچسب را تعیین می‌کند.
	 *
	 * @var unknown
	 */
	public $label_precond = array (
			'Pluf_Precondition::loginRequired' 
	);
	
	/**
	 * فرآیند دستکاری یک برچسب را ایجاد می‌کند.
	 *
	 * @param unknown $request        	
	 * @param unknown $match        	
	 * @throws Pluf_Exception_NotImplemented
	 */
	public function label($request, $match) {
		$label_id = $match [1];
		$label = Pluf_Shortcuts_GetObjectOr404 ( 'KM_Label', $label_id );
		if ($label->user != $request->user->id) {
			throw new Pluf_Exception_PermissionDenied ( __ ( 'You are not the laberl owner.' ) );
		}
		
		if ($request->method === 'DELETE') {
			$label->delete ();
			return new Pluf_HTTP_Response_Json ( $label );
		}
		
		if ($request->method === 'GET') {
			return new Pluf_HTTP_Response_Json ( $label );
		}
		
		if ($request->method === 'POST') {
			$extra = array (
					'user' => $request->user,
					'label' => $label 
			);
			$form = new KM_Form_Label ( array_merge ( $request->POST, $request->FILES ), $extra );
			$cuser = $form->update ();
			return new Pluf_HTTP_Response_Json ( $cuser );
		}
		
		throw new Pluf_Exception_NotImplemented ();
	}
	
	/**
	 * تعداد گزینه‌های یک لیست را تعیین می‌کند.
	 *
	 * TODO: maso, 1394: این تعداد می‌تواند برای کاربران متفاوت باشد.
	 *
	 * @param unknown $request        	
	 * @return number
	 */
	private function getListCount($request) {
		$count = 20;
		if (array_key_exists ( '_px_count', $request->GET )) {
			$count = $request->GET ['_px_count'];
			if ($count > 20) {
				$count = 20;
			}
		}
		return $count;
	}
}