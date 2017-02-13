<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

/**
 * @ingroup views
 * @brief این کلاس نمایش‌های اصلی سیستم را ایجاد می‌کند.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *         @date 1394
 */
class Book_Views_Page
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
        $book = Pluf_Shortcuts_GetObjectOr404('Book_Book', $match['bookId']);
        Book_Precondition::userCanCreatePage($request, $book);
        // اجرای درخواست
        $extra = array(
                'user' => $request->user,
                'book' => $book
        );
        $form = new Book_Form_PageCreate(
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
        $page = Pluf_Shortcuts_GetObjectOr404('Book_Page', $match['pageId']);
        $book = Pluf_Shortcuts_GetObjectOr404('Book_Book', $match['bookId']);
        // حق دسترسی
        Book_Precondition::userCanAccessPage($request, $page, $book);
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
        $page = Pluf_Shortcuts_GetObjectOr404('Book_Page', $match['pageId']);
        $book = Pluf_Shortcuts_GetObjectOr404('Book_Book', $match['bookId']);
        // حق دسترسی
        Book_Precondition::userCanUpdatePage($request, $page, $book);
        // اجرای درخواست
        $extra = array(
                'user' => $request->user,
                'page' => $page
        );
        $form = new Book_Form_PageUpdate(
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
        $page = Pluf_Shortcuts_GetObjectOr404('Book_Page', $match['pageId']);
        $book = Pluf_Shortcuts_GetObjectOr404('Book_Book', $match['bookId']);
        // دسترسی
        Book_Precondition::userCanDeletePage($request, $page);
        // اجرا
        $page2 = new Book_Page($page->id);
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
        $book = Pluf_Shortcuts_GetObjectOr404('Book_Book', $match['bookId']);
        // maso, 1394: گرفتن فهرست مناسبی از پیام‌ها
        $pag = new Pluf_Paginator(new Book_Page());
        $sql = new Pluf_SQL('book=%s', 
                array(
                        $book->id,
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
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }
}