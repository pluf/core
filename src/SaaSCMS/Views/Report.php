<?php

class SaaSCMS_Views_Report
{
	
	public static function getTypes($request, $match)
	{
        // TODO: create list of types of reports in json format
        return new Pluf_HTTP_Response_Json("{}");
    }

    public static function get ($request, $match)
    {
        $reportType = $request->type;
        // TODO: ایجاد گزارش از نوع خواسته شده و ارسال ان به صورت جیسون
//     	// تعیین داده‌ها
//     	$device = SaaSCMS_Shortcuts_GetReportOr404($match[1]);
//     	// حق دسترسی
// //     	SaaSCMS_Precondition::userCanAccessReport($request, $device);
//     	// اجرای درخواست
    	return new Pluf_HTTP_Response_Json("{}");
    }
    
}