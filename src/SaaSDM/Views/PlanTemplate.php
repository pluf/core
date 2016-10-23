<?php
Pluf::loadFunction ( 'SaaSDM_Shortcuts_GetPlanTemplateOr404' );
class SaaSDM_Views_PlanTemplate {
	public static function create($request, $match) {
		// initial plan template data
		$plantemplate = array (
				// 'user' => $request->user,
				'tenant' => $request->tenant 
		);
		// Create plan template and get its ID
		$form = new SaaSDM_Form_PlanTemplateCreate ( $request->REQUEST, $plantemplate );
		$plantemplate = $form->save ();
		
		return new Pluf_HTTP_Response_Json ( $plantemplate );
	}
	public static function find($request, $match) {
		$plantemplate = new Pluf_Paginator ( new SaaSDM_Asset () );
		// $sql = new Pluf_SQL('tenant=%s', array(
		// $request->tenant->id
		// ));
		// $asset->forced_where = $sql;
		$plantemplate->list_filters = array (
				'label',
				'description',
				'content_name',
				'period',
				'max_count',
				'max_volume',
				'price',
				'off' 
		);
		$list_display = array ();
		
		$search_fields = array (
				'label',
				'description',
				'content_name',
				'period',
				'max_count',
				'max_volume',
				'price',
				'off' 
		);
		$sort_fields = array (
				'label',
				'description',
				'content_name',
				'period',
				'max_count',
				'max_volume',
				'price',
				'off' 
		);
		$plantemplate->configure ( $list_display, $search_fields, $sort_fields );
		$plantemplate->items_per_page = 10;
		$plantemplate->setFromRequest ( $request );
		return new Pluf_HTTP_Response_Json ( $plantemplate->render_object () );
	}
	public static function get($request, $match) {
		// تعیین داده‌ها
		$plantemplate = SaaSDM_Shortcuts_GetPlanTemplateOr404 ( $match ["id"] );
		// حق دسترسی
		// CMS_Precondition::userCanAccessContent($request, $content);
		// اجرای درخواست
		return new Pluf_HTTP_Response_Json ( $plantemplate );
	}
	public static function update($request, $match) {
		// تعیین داده‌ها
		$plantemplate = SaaSDM_Shortcuts_GetPlanTemplateOr404 ( $match ["id"] );
		// حق دسترسی
		// CMS_Precondition::userCanUpdateContent($request, $content);
		// اجرای درخواست
		$extra = array (
				// 'user' => $request->user,
				'tenant' => $request->tenant,
				// 'user' => $request->user,
				'plantemplate' => $plantemplate 
		);
		// 'tenant' => $request->tenant
		
		$form = new SaaSDM_Form_PlanTemplateUpdate ( array_merge ( $request->REQUEST ), $extra );
		$plantemplate = $form->update ();
		return new Pluf_HTTP_Response_Json ( $plantemplate );
	}
	public static function delete($request, $match) {
		// تعیین داده‌ها
		$plantemplate = SaaSDM_Shortcuts_GetPlanTemplateOr404 ( $match ["id"] );
		// دسترسی
		// CMS_Precondition::userCanDeleteContent($request, $content);
		// اجرا
		$plantemplate->delete ();
		
		// TODO: فایل مربوط به کانتنت باید حذف شود
		
		return new Pluf_HTTP_Response_Json ( $plantemplate );
	}
}