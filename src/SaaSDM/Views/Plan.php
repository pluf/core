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
	
	// public static function download($request, $match)
	// {
	// // GET data
	// $app = $request->tenant;
	// $content = SaaSCMS_Shortcuts_GetContentOr404($match[1]);
	// // Check permission
	// // SaaS_Precondition::userCanAccessApplication($request, $app);
	// // SaaS_Precondition::userCanAccessResource($request, $content);
	
	// // Do
	// $content->downloads += 1;
	// $content->update();
	// $response = new Pluf_HTTP_Response_File($content->file_path . '/' . $content->id, $content->mime_type);
	// $response->headers['Content-Disposition'] = 'attachment; filename="' . $content->file_name . '"';
	// return $response;
	// }
// 	public static function updateFile($request, $match) {
// 		// GET data
// 		$app = $request->tenant;
// 		$asset = SaaSCMS_Shortcuts_GetAssetOr404 ( $match ["id"] );
// 		// Check permission
// 		// SaaS_Precondition::userCanAccessApplication($request, $app);
// 		// SaaS_Precondition::userCanAccessResource($request, $content);
		
// 		if (array_key_exists ( 'file', $request->FILES )) {
// 			$extra = array (
// 					// 'user' => $request->user,
// 					'asset' => $asset,
// 					'tenant' => $request->tenant 
// 			);
// 			$form = new SaaSCMS_Form_ContentUpdate ( array_merge ( $request->REQUEST, $request->FILES ), $extra );
// 			$asset = $form->update ();
// 			// return new Pluf_HTTP_Response_Json($content);
// 		} else {
			
// 			// Do
// 			$myfile = fopen ( $asset->path . '/' . $asset->id, "w" ) or die ( "Unable to open file!" );
// 			$entityBody = file_get_contents ( 'php://input', 'r' );
// 			fwrite ( $myfile, $entityBody );
// 			fclose ( $myfile );
// 			$asset->file_size = filesize ( $asset->path . '/' . $asset->id );
// 			$asset->update ();
// 		}
// 		return new Pluf_HTTP_Response_Json ( $asset );
// 	}
}