<?php
Pluf::loadFunction('SaaSCMS_Shortcuts_GetPageOr404');
Pluf::loadFunction('SaaSCMS_Shortcuts_GetContentOr404');

include 'SaaSCMS/Content.php';

class SaaSCMS_Views_Page
{

    public static function create($request, $match)
    {
        // initial page data
        $extra = array(
            // 'user' => $request->user,
            'tenant' => $request->tenant
        );
        $form = new SaaSCMS_Form_PageCreate(array_merge($request->REQUEST, $request->FILES), $extra);
        $page = $form->save();
        // $request->user->setMessage(
        // sprintf(__('new page \'%s\' is created.'),
        // (string) $page->title));
        return new Pluf_HTTP_Response_Json($page);
    }

    public static function find($request, $match)
    {
        $page = new Pluf_Paginator(new SaaSCMS_Page());
        $sql = new Pluf_SQL('tenant=%s', array(
            $request->tenant->id
        ));
        $page->forced_where = $sql;
        $page->list_filters = array(
            'id',
            'name',
            'content'
        );
        $list_display = array(
            'name' => __('name'),
            'content' => __('content')
        );
        $search_fields = array(
            'main',
            'content'
        );
        $sort_fields = array(
            'id',
            'name',
            'content',
            'creation_date',
            'modif_dtime'
        );
        $page->configure($list_display, $search_fields, $sort_fields);
        $page->items_per_page = 10;
        $page->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($page->render_object());
    }

    public static function get($request, $match)
    {
        // تعیین داده‌ها
        $page = SaaSCMS_Shortcuts_GetPageOr404($match[1]);
        // حق دسترسی
        // SaaSCMS_Precondition::userCanAccessPage($request, $page);
        // اجرای درخواست
        return new Pluf_HTTP_Response_Json($page);
    }

    public static function update($request, $match)
    {
        // تعیین داده‌ها
        $page = SaaSCMS_Shortcuts_GetPageOr404($match[1]);
        // حق دسترسی
        // SaaSCMS_Precondition::userCanUpdatePage($request, $page);
        // اجرای درخواست
        $extra = array(
            // 'user' => $request->user,
            'page' => $page
        );
        $form = new SaaSCMS_Form_PageUpdate(array_merge($request->REQUEST, $request->FILES), $extra);
        $page = $form->update();
        return new Pluf_HTTP_Response_Json($page);
    }

    public static function delete($request, $match)
    {
        // تعیین داده‌ها
        $page = SaaSCMS_Shortcuts_GetPageOr404($match[1]);
        // دسترسی
        // SaaSCMS_Precondition::userCanDeletePage($request, $page);
        // اجرا
        $page2 = SaaSCMS_Shortcuts_GetPageOr404($page->id);
        $page2->delete();
        return new Pluf_HTTP_Response_Json($page);
    }

//     protected static function getContentById($id)
//     {
//         // تعیین داده‌ها
//         $page = SaaSCMS_Shortcuts_GetPageOr404($id);
//         // حق دسترسی
//         // SaaSCMS_Precondition::userCanAccessPage($request, $page);
//         // اجرای درخواست
//         $content = SaaSCMS_Shortcuts_GetContentOr404($page->content);
//         return new $content;
//     }

//     protected static function getContentByName($name)
//     {
//         // حق دسترسی
//         // SaaSCMS_Precondition::userCanAccessPage($request, $page);
//         // اجرای درخواست
//         $params = array(
//             'filter' => 'name=' . '"' . $name . '"',
//             'nb' => 1
//         );
//         $pages = (new SaaSCMS_Page())->getList($params);
//         if ($pages->count() == 0)
//             throw new SaaSCMS_Exception_ObjectNotFound("SaaSCMS page with name " . $name . "not found");
//         $mypage = $pages[0];
//         $content = SaaSCMS_Shortcuts_GetContentOr404($mypage->content);
//         return $content;
//     }

//     public static function getContentFile($request, $match){
//         $content = SaaSCMS_Views_Page::getContentByName($match[1]);

//         // Do
//         // TODO: Hadi: نیاز به بازبینی دارد. در صورت امکان همان متد download از کلاس Content صدا زده شود.
//         $content->downloads += 1;
//         $content->update();
//         $response = new Pluf_HTTP_Response_File($content->file_path . '/' . $content->file_name, $content->mime_type);
//         $response->headers['Content-Disposition'] = 'attachment; filename="' . $content->file_name . '"';
//         return $response;
// //         $match[1] = $content->id;
// //         return SaaSCMS_Views_Content::download($request, $match);
//     }
    
//     public static function updateContentFile($request, $match){
//         $content = SaaSCMS_Views_Page::getContentByName($match[1]);
//         $extra = array(
//             // 'user' => $request->user,
//             'content' => $content,
//             'tenant' => $request->tenant
//         );
//         $form = new SaaSCMS_Form_ContentUpdate(array_merge($request->REQUEST, $request->FILES), $extra);
//         $content = $form->update();
//         return new Pluf_HTTP_Response_Json($content);
//     }
    
}