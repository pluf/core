<?php

class SaaSNewspaper_Views_Follower
{
	
	public static function create ($request, $match)
	{
		// initial page data
		$extra = array(
				'tenant' => $request->tenant
		);
		$form = new SaaSNewspaper_Form_FollowerCreate($request->REQUEST, $extra);
		$follower = $form->save();
		return new Pluf_HTTP_Response_Json($follower);
	}
	
// 	public static function find($request, $match)
// 	{
//         $pag = new Pluf_Paginator(new SaaSNewspaper_Follower());
//         $sql = new Pluf_SQL('tenant=%s',
//                 array(
//                         $request->tenant->id
//                 ));
//         $pag->forced_where = $sql;
//         $pag->list_filters = array(
//                 'id',
//                 'title'
//         );
//         $list_display = array(
//                 'title' => __('title'),
//                 'description' => __('description')
//         );
//         $search_fields = array(
//                 'title',
//                 'description'
//         );
//         $sort_fields = array(
//                 'id',
//                 'title',
//                 'creation_date',
//                 'modif_dtime'
//         );
//         $pag->configure($list_display, $search_fields, $sort_fields);
//         $pag->items_per_page = 10;
//         $pag->setFromRequest($request);
//         return new Pluf_HTTP_Response_Json($pag->render_object());
//     }

//     /**
//      * یک دستگاه را با شناسه تعیین می‌کند
//      *
//      * @param unknown $request
//      * @param unknown $match
//      * @return Pluf_HTTP_Response_Json
//      */
//     public static function get ($request, $match)
//     {
//     	// تعیین داده‌ها
//     	$device = DigiDoci_Shortcuts_GetDeviceOr404($match[1]);
//     	// حق دسترسی
// //     	DigiDoci_Precondition::userCanAccessDevice($request, $device);
//     	// اجرای درخواست
//     	return new Pluf_HTTP_Response_Json($device);
//     }
    
//     /**
//      * دستگاه را به روز می‌کند
//      *
//      * @param unknown $request
//      * @param unknown $match
//      */
//     public static function update ($request, $match)
//     {
//     	// تعیین داده‌ها
//     	$device = DigiDoci_Shortcuts_GetDeviceOr404($match[1]);
//     	// حق دسترسی
// //     	DigiDoci_Precondition::userCanUpdateDevice($request, $device);
//     	// اجرای درخواست
//     	$extra = array(
// //     			'user' => $request->user,
//     			'device' => $device
//     	);
//     	// TODO: در اینجا از یک فرم استفاده شده برای به روزرسانی.
//     	// نمی‌دونم این فرم چیه باید درست بشه مثلا متد update که از این فرم
//     	// صدا زده شده رو مستقیما استفاده کنم
//     	$form = new DigiDoci_Form_DeviceUpdate(
//     			array_merge($request->REQUEST, $request->FILES), $extra);
//     	$device = $form->update();
//     	return new Pluf_HTTP_Response_Json($device);
//     }
    
//     /**
//      * دستگاه را حذف می‌کند.
//      *
//      * @param unknown $request
//      * @param unknown $match
//      * @return Pluf_HTTP_Response_Json
//      */
//     public static function delete ($request, $match)
//     {
//     	// تعیین داده‌ها
//     	$device = DigiDoci_Shortcuts_GetDeviceOr404($match[1]);
//     	// دسترسی
// //     	DigiDoci_Precondition::userCanDeleteDevice($request, $device);
//     	// اجرا
//     	$device2 = new DigiDoci_Device($device->id);
//     	$device2->delete();
//     	return new Pluf_HTTP_Response_Json($device);
//     }
}