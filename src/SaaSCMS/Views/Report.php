<?php
// pluf::loadClass('SaaSCMS_Content');
include 'SaaSCMS/Content.php';

class SaaSCMS_Views_Report
{

    public static $reportTypes = array(
        'used_memory' => 'Used memory by your tenant',
        'file_count' => "Number of files of your tenant"
    );

    public static function getTypes($request, $match)
    {
        // TODO: create list of types of reports in json format
        return new Pluf_HTTP_Response_Json(SaaSCMS_Views_Report::$reportTypes);
    }

    public static function get($request, $match)
    {
        // حق دسترسی
        // SaaSCMS_Precondition::userCanAccessReport($request, $device);
        // اجرای درخواست
        switch ($match[1]) {
            case 'used_memory':
                $result = SaaSCMS_Views_Report::computeUsedMemory($request);
//                 $result = SaaSCMS_Views_Report::folderSize(Pluf::f('upload_path') . '/' . $request->tenant->id);
                return new Pluf_HTTP_Response_Json($result);
            case 'file_count':
                break;
        }
        
        return new Pluf_HTTP_Response_Json("{}");
    }

    protected static function computeUsedMemory($request)
    {
        // حق دسترسی
        // SaaSCMS_Precondition::userCanAccessPage($request, $page);
        // اجرای درخواست
        $params = array(
            'nb' => 10
        );
        $contents = (new SaaSCMS_Content())->getList($params);
        $memory = 0;
        for ($i = 0; $i < $contents->count(); $i ++) {
            $memory = $memory + $contents[$i]->file_size;
        }
        return $memory;
    }

    public static function folderSize($dir)
    {
        $count_size = 0;
        $count = 0;
        $dir_array = scandir($dir);
        foreach ($dir_array as $key => $filename) {
            if ($filename != ".." && $filename != ".") {
                if (is_dir($dir . "/" . $filename)) {
                    $new_foldersize = SaaSCMS_Views_Report::foldersize($dir . "/" . $filename);
                    $count_size = $count_size + $new_foldersize;
                } else 
                    if (is_file($dir . "/" . $filename)) {
                        $count_size = $count_size + filesize($dir . "/" . $filename);
                        $count ++;
                    }
            }
        }
        return $count_size;
    }
    
    public static function fileCounter($dir)
    {
        $count_size = 0;
        $fileCount = 0;
        $folderCount = 0;
        $dir_array = scandir($dir);
        foreach ($dir_array as $key => $filename) {
            if ($filename != ".." && $filename != ".") {
                if (is_dir($dir . "/" . $filename)) {
                    $folderCount ++;
                    $new_folderCount = SaaSCMS_Views_Report::fileCounter($dir . "/" . $filename);
                    $folderCount = $count_size + $new_foldersize;
                } else
                    if (is_file($dir . "/" . $filename)) {
                        $count_size = $count_size + filesize($dir . "/" . $filename);
                        $fileCount ++;
                    }
            }
        }
        return $count_size;
    }
}