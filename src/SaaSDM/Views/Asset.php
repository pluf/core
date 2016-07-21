<?php
Pluf::loadFunction('SaaSDM_Shortcuts_GetAssetOr404');

class SaaSDM_Views_Asset
{

//     public static function create($request, $match)
//     {
//         // initial content data
//         $extra = array(
//             // 'user' => $request->user,
//             'tenant' => $request->tenant
//         );
//         // Create content and get its ID
//         $form = new SaaSCMS_Form_ContentCreate($request->REQUEST, $extra);
//         $content = $form->save();
        
//         // Upload content file and extract information about it (by updating content)
//         $extra['content'] = $content;
//         $form = new SaaSCMS_Form_ContentUpdate(array_merge($request->REQUEST, $request->FILES), $extra);
//         try {
//             $content = $form->update();
//         } catch (Pluf_Exception $e) {
//             $content->delete();
//         }
        
//         return new Pluf_HTTP_Response_Json($content);
//     }

    public static function find($request, $match)
    {
        $asset = new Pluf_Paginator(new SaaSDM_Asset());
//         $sql = new Pluf_SQL('tenant=%s', array(
//             $request->tenant->id
//         ));
//         $asset->forced_where = $sql;
        $asset->list_filters = array(
            'id',
            'name',
        );
        $list_display = array(

        );
        $search_fields = array(
            'name',
        );
        $sort_fields = array(
            'id',
            'title',
            'file_name',
            'file_size',
            'mime_type',
            'downloads',
            'creation_date',
            'modif_dtime'
        );
        $asset->configure($list_display, $search_fields, $sort_fields);
        $asset->items_per_page = 10;
        $asset->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($asset->render_object());
    }

    public static function get($request, $match)
    {
        // تعیین داده‌ها
        $asset = SaaSDM_Shortcuts_GetAssetOr404($match["id"]);
        // حق دسترسی
        // SaaSCMS_Precondition::userCanAccessContent($request, $content);
        // اجرای درخواست
        return new Pluf_HTTP_Response_Json($asset);
    }

    public static function update($request, $match)
    {
        // تعیین داده‌ها
        $asset = SaaSDM_Shortcuts_GetAssetOr404($match["id"]);
        // حق دسترسی
        // SaaSCMS_Precondition::userCanUpdateContent($request, $content);
        // اجرای درخواست
        $extra = array(
            // 'user' => $request->user,
            'asset' => $asset
            //'tenant' => $request->tenant
        );
        $form = new SaaSDM_Form_AssetUpdate(array_merge($request->REQUEST, $request->FILES), $extra);
        $asset = $form->update();
        return new Pluf_HTTP_Response_Json($asset);
    }

//     public static function delete($request, $match)
//     {
//         // تعیین داده‌ها
//         $content = SaaSCMS_Shortcuts_GetContentOr404($match[1]);
//         // دسترسی
//         // SaaSCMS_Precondition::userCanDeleteContent($request, $content);
//         // اجرا
//         $content2 = SaaSCMS_Shortcuts_GetContentOr404($content->id);
//         $content->delete();
        
//         // TODO: فایل مربوط به کانتنت باید حذف شود
        
//         return new Pluf_HTTP_Response_Json($content2);
//     }

//     public static function download($request, $match)
//     {
//         // GET data
//         $app = $request->tenant;
//         $content = SaaSCMS_Shortcuts_GetContentOr404($match[1]);
//         // Check permission
//         // SaaS_Precondition::userCanAccessApplication($request, $app);
//         // SaaS_Precondition::userCanAccessResource($request, $content);
        
//         // Do
//         $content->downloads += 1;
//         $content->update();
//         $response = new Pluf_HTTP_Response_File($content->file_path . '/' . $content->id, $content->mime_type);
//         $response->headers['Content-Disposition'] = 'attachment; filename="' . $content->file_name . '"';
//         return $response;
//     }

//     public static function updateFile($request, $match)
//     {
//         // GET data
//         $app = $request->tenant;
//         $content = SaaSCMS_Shortcuts_GetContentOr404($match[1]);
//         // Check permission
//         // SaaS_Precondition::userCanAccessApplication($request, $app);
//         // SaaS_Precondition::userCanAccessResource($request, $content);
        
//         if (array_key_exists('file', $request->FILES)) {
//             $extra = array(
//                 // 'user' => $request->user,
//                 'content' => $content,
//                 'tenant' => $request->tenant
//             );
//             $form = new SaaSCMS_Form_ContentUpdate(array_merge($request->REQUEST, $request->FILES), $extra);
//             $content = $form->update();
// //             return new Pluf_HTTP_Response_Json($content);
//         } else {
            
//             // Do
//             $myfile = fopen($content->file_path . '/' . $content->id, "w") or die("Unable to open file!");
//             $entityBody = file_get_contents('php://input', 'r');
//             fwrite($myfile, $entityBody);
//             fclose($myfile);
//             $content->file_size = filesize($content->file_path . '/' . $content->id);
//             $content->update();
//         }
//         return new Pluf_HTTP_Response_Json($content);
//     }
}