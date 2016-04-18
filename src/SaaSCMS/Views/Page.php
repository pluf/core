<?php
Pluf::loadFunction('SaaSCMS_Shortcuts_GetPageOr404');

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

    public static function getContentById($request, $match)
    {
        // تعیین داده‌ها
        $page = SaaSCMS_Shortcuts_GetPageOr404($match[1]);
        // حق دسترسی
        // SaaSCMS_Precondition::userCanAccessPage($request, $page);
        // اجرای درخواست
        $content = SaaSCMS_Shortcuts_GetContentOr404($page->content);
        return new Pluf_HTTP_Response_Json($content);
    }

    public static function getContentByName($request, $match)
    {
        // تعیین داده‌ها
        $name = $match[1];
        // حق دسترسی
        // SaaSCMS_Precondition::userCanAccessPage($request, $page);
        // اجرای درخواست
        
        $page = new Pluf_Paginator(new SaaSCMS_Page());
        $sql = new Pluf_SQL('tenant=%s and name=%s', array(
            $request->tenant->id,
            $name
        ));
        $page->forced_where = $sql;
        $page->list_filters = array(
            'id',
            'name'
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
        // $page->setFromRequest($request);
        $paginate = $page->render_object();
        if ($paginate->counts == 0) {
            throw new SaaSCMS_Exception_ObjectNotFound("SaaSCMS page with name " . $name . "not found");
        } else
            throw new SaaSCMS_Exception_ObjectNotFound("SaaSCMS page with name found");
        
//         $mypage = $this->getPageByName($request, $name);
        $mypage = $paginate->items[0];
        $content = SaaSCMS_Shortcuts_GetContentOr404($mypage->content);
        return new Pluf_HTTP_Response_Json($content);
    }

    protected static function getPageByName($request, $name)
    {}
}