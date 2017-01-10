<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

// class CMS_Views_Report
// {

//     public static $reportTypes = array(
//         'used_memory' => 'Used memory by your tenant',
//         'file_count' => "Number of files of your tenant",
//         'summary' => "Summary report"
//     );

//     public static function getTypes($request, $match)
//     {
//         // TODO: create list of types of reports in json format
//         return new Pluf_HTTP_Response_Json(CMS_Views_Report::$reportTypes);
//     }

//     public static function get($request, $match)
//     {
//         // حق دسترسی
//         // CMS_Precondition::userCanAccessReport($request, $device);
//         // اجرای درخواست
//         switch ($match[1]) {
//             case 'used_memory':
// //                 $result = CMS_Views_Report::computeUsedMemory($request);
//                 $result = CMS_Views_Report::folderSize(Pluf::f('upload_path') . '/' . $request->tenant->id);
//                 return new Pluf_HTTP_Response_Json($result);
//             case 'file_count':
//                 $result = CMS_Views_Report::fileCounter(Pluf::f('upload_path') . '/' . $request->tenant->id);
//                 return new Pluf_HTTP_Response_Json($result);
//                 break;
//             case 'summary':
//                 $result = array(
//                     'used_memory' => CMS_Views_Report::folderSize(Pluf::f('upload_path') . '/' . $request->tenant->id),
//                     'file_count' => CMS_Views_Report::fileCounter(Pluf::f('upload_path') . '/' . $request->tenant->id)
//                 ); 
//                 return new Pluf_HTTP_Response_Json($result);
//                 break;
//         }
//         return new Pluf_HTTP_Response_Json("{}");
//     }

//     protected static function computeUsedMemory($request)
//     {
//         // حق دسترسی
//         // CMS_Precondition::userCanAccessPage($request, $page);
//         // اجرای درخواست
//         $params = array(
//             'nb' => 10
//         );
//         $contents = (new CMS_Content())->getList($params);
//         $memory = 0;
//         for ($i = 0; $i < $contents->count(); $i ++) {
//             $memory = $memory + $contents[$i]->file_size;
//         }
//         return $memory;
//     }

//     protected static function folderSize($dir)
//     {
//         $count_size = 0;
//         $count = 0;
//         $dir_array = scandir($dir);
//         foreach ($dir_array as $key => $filename) {
//             if ($filename != ".." && $filename != ".") {
//                 if (is_dir($dir . "/" . $filename)) {
//                     $new_foldersize = CMS_Views_Report::foldersize($dir . "/" . $filename);
//                     $count_size = $count_size + $new_foldersize;
//                 } else 
//                     if (is_file($dir . "/" . $filename)) {
//                         $count_size = $count_size + filesize($dir . "/" . $filename);
//                         $count ++;
//                     }
//             }
//         }
//         return $count_size;
//     }
    
//     protected static function fileCounter($dir)
//     {
//         $count = 0;
//         $dir_array = scandir($dir);
//         foreach ($dir_array as $key => $filename) {
//             if ($filename != ".." && $filename != ".") {
//                 if (is_dir($dir . "/" . $filename)) {
//                     $new_counter = CMS_Views_Report::fileCounter($dir . "/" . $filename);
//                     $count = $count + $new_counter + 1;
//                 } else
//                     if (is_file($dir . "/" . $filename)) {
//                         $count ++;
//                     }
//             }
//         }
//         return $count;
//     }
// }