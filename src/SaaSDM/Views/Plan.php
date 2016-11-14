<?php
Pluf::loadFunction ( 'SaaSDM_Shortcuts_GetPlanOr404' );
class SaaSDM_Views_Plan {
	public static function create($request, $match) {
		// initial plan data
		$extra = array (
				// 'user' => $request->user,
				'tenant' => $request->tenant 
		);
		// Create plan and get its ID
		$extra ['user'] = $request->user; // added to keep user data
		$form = new SaaSDM_Form_PlanCreate ( $request->REQUEST, $extra );
		$plan = $form->save ();
		
		return new Pluf_HTTP_Response_Json ( $plan );
	}
	public static function find($request, $match) {
		$plan = new Pluf_Paginator ( new SaaSDM_Plan() );
		// $sql = new Pluf_SQL('tenant=%s', array(
		// $request->tenant->id
		// ));
		// $asset->forced_where = $sql;
		$plan->list_filters = array (
				'id',
				'name',
				'path',
				'size',
				'download',
				'driver_type',
				'driver_id' 
		);
		$list_display = array ();
		
		$search_fields = array (
				'name',
				'path',
				'size',
				'download',
				'driver_type',
				'driver_id' 
		);
		$sort_fields = array (
				'id',
				'title',
				'file_name',
				'file_size',
				'mime_type',
				'downloads',
				'creation_date',
				'modif_dtime' 
		);
		$plan->configure ( $list_display, $search_fields, $sort_fields );
		$plan->items_per_page = 10;
		$plan->setFromRequest ( $request );
		return new Pluf_HTTP_Response_Json ( $plan->render_object () );
	}
	public static function get($request, $match) {
		// تعیین داده‌ها
		$plan = SaaSDM_Shortcuts_GetPlanOr404 ( $match ["id"] );
		// حق دسترسی
		// CMS_Precondition::userCanAccessContent($request, $content);
		// اجرای درخواست
		return new Pluf_HTTP_Response_Json ( $plan );
	}
	public static function update($request, $match) {
		// تعیین داده‌ها
		$plan = SaaSDM_Shortcuts_GetPlanOr404 ( $match ["id"] );
		// حق دسترسی
		// CMS_Precondition::userCanUpdateContent($request, $content);
		// اجرای درخواست
		$extra = array (
				// 'user' => $request->user,
				'plan' => $plan,
				'tenant' => $request->tenant 
		);
		
		$form = new SaaSDM_Form_PlanUpdate ( array_merge ( $request->REQUEST ), $extra );
		$plan = $form->update ();
		return new Pluf_HTTP_Response_Json ( $plan );
	}
	public static function delete($request, $match) {
		// تعیین داده‌ها
		$plan = SaaSDM_Shortcuts_GetPlanOr404 ( $match ["id"] );
		// دسترسی
		// CMS_Precondition::userCanDeleteContent($request, $content);
		// اجرا
		$plan->delete ();
		
		// TODO: فایل مربوط به است باید حذف شود
		
		return new Pluf_HTTP_Response_Json ( $plan );
	}

	/**
	 * 
	 * @param Pluf_HTTP_Request $request
	 * @param array $match
	 */
	public static function payment($request, $match){
		
		$plan = SaaSDM_Shortcuts_GetPlanOr404($match['planId']);
		$url = $request->REQUEST['callback'];
		$user = $request->user;
		$backend = $request->REQUEST['backend'];
		

		$payment = SaaSBank_Service::create ( $request, array (
				'amount' => $plan->price, // مقدار پرداخت به ریال
				'title' => 'خرید پلن  ' . $plan->id,
				'description' => 'description',
				'email' => $user->email,
// 				'phone' => $user->phone,
				'phone' => '',
				'callbackURL' => $url,
				'backend' => $backend 
		),$plan );
		
		$plan->payment = $payment;
		$plan->update();
		return new Pluf_HTTP_Response_Json ( $payment );
	}
	/**
	 * 
	 * @param Pluf_HTTP_Request $request
	 * @param array $match
	 */
	public static function activate($request, $match){
		
		$plan = SaaSDM_Shortcuts_GetPlanOr404($match['planId']);
		
		SaaSBank_Service::update($plan->get_payment());
		
		if ($plan->get_payment()->isPayed())
			$plan->activate();
		return new Pluf_HTTP_Response_Json ( $plan );
	}
	
}