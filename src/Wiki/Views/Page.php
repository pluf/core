<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Wiki_Shortcuts_GetPageOr404');
Pluf::loadFunction('Wiki_Shortcuts_GetPageListCount');

/**
 * @ingroup views
 * @brief این کلاس نمایش‌های اصلی سیستم را ایجاد می‌کند.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *         @date 1394
 */
class Wiki_Views_Page
{

    /**
     * یک صفحه جدید را ایجاد می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function create ($request, $match)
    {
        // تعیین دسترسی
        Wiki_Precondition::userCanCreatePage($request);
        // اجرای درخواست
        $extra = array(
                'user' => $request->user,
                'tenant' => $request->tenant
        );
        $form = new Wiki_Form_PageCreate(
                array_merge($request->REQUEST, $request->FILES), $extra);
        $page = $form->save();
        $request->user->setMessage(
                sprintf(__('new page \'%s\' is created.'), 
                        (string) $page->title));
        // Return response
        return new Pluf_HTTP_Response_Json($page);
    }

    /**
     * یک صفحه را با شناسه تعیین می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function get ($request, $match)
    {
        // تعیین داده‌ها
        $page = Wiki_Shortcuts_GetPageOr404($match[1]);
        // حق دسترسی
        Wiki_Precondition::userCanAccessPage($request, $page);
        // اجرای درخواست
        return new Pluf_HTTP_Response_Json($page);
    }

    /**
     * صفحه را به روز می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function update ($request, $match)
    {
        // تعیین داده‌ها
        $page = Wiki_Shortcuts_GetPageOr404($match[1]);
        // حق دسترسی
        Wiki_Precondition::userCanUpdatePage($request, $page);
        // اجرای درخواست
        $extra = array(
                'user' => $request->user,
                'page' => $page
        );
        $form = new Wiki_Form_PageUpdate(
                array_merge($request->REQUEST, $request->FILES), $extra);
        $page = $form->update();
        return new Pluf_HTTP_Response_Json($page);
    }

    /**
     * صفحه را حذف می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function delete ($request, $match)
    {
        // تعیین داده‌ها
        $page = Wiki_Shortcuts_GetPageOr404($match[1]);
        // دسترسی
        Wiki_Precondition::userCanDeletePage($request, $page);
        // اجرا
        $page2 = new Wiki_Page($page->id);
        $page2->delete();
        return new Pluf_HTTP_Response_Json($page);
    }

    /**
     * جستجوی صفحه‌ها را انجام می‌دهد
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function find ($request, $match)
    {
        // maso, 1394: گرفتن فهرست مناسبی از پیام‌ها
        $pag = new Pluf_Paginator(new Wiki_Page());
        $sql = new Pluf_SQL('tenant=%s', 
                array(
                        $request->tenant->id
                ));
        $pag->forced_where = $sql;
        $pag->list_filters = array(
                'id',
                'title'
        );
        $list_display = array(
                'title' => __('title'),
                'summary' => __('summary')
        );
        $search_fields = array(
                'title',
                'summary',
                'content'
        );
        $sort_fields = array(
                'id',
                'title',
                'creation_date',
                'modif_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->items_per_page = Wiki_Shortcuts_GetPageListCount($request);
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }
}