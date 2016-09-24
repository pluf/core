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
		$form = new SaaSDM_Form_PlanCreate ( $request->REQUEST, $extra );
		$plan = $form->save ();
				
		return new Pluf_HTTP_Response_Json ( $plan );
	}
	public static function find($request, $match) {
		$plan = new Pluf_Paginator ( new SaaSDM_Asset () );
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
		$plan = SaaSDM_Shortcuts_GetPlanOr404( $match ["id"] );
		// حق دسترسی
		// SaaSCMS_Precondition::userCanAccessContent($request, $content);
		// اجرای درخواست
		return new Pluf_HTTP_Response_Json ( $plan );
	}
	public static function update($request, $match) {
		// تعیین داده‌ها
		$plan = SaaSDM_Shortcuts_GetPlanOr404 ( $match ["id"] );
		// حق دسترسی
		// SaaSCMS_Precondition::userCanUpdateContent($request, $content);
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
		// SaaSCMS_Precondition::userCanDeleteContent($request, $content);
		// اجرا
		$plan->delete ();
		
		// TODO: فایل مربوط به است باید حذف شود
		
		return new Pluf_HTTP_Response_Json ( $plan );
	}
}