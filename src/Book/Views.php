<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

/**
 * لایه نمایش کتاب‌ها را ایجاد می‌کند.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 */
class Book_Views
{

    /**
     * فهرست کتاب‌ها را تعیین می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function find ($request, $match)
    {
        // maso, 1394: گرفتن فهرست مناسبی از پیام‌ها
        $pag = new Pluf_Paginator(new Book_Book());
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
                'summary'
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

    /**
     * یک کتاب جدید ایجاد می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function create ($request, $match)
    {
        // initial page data
        $extra = array(
                'user' => $request->user
        );
        $form = new Book_Form_BookCreate(
                array_merge($request->REQUEST, $request->FILES), $extra);
        $book = $form->save();
        $request->user->setMessage(
                sprintf(__('new book \'%s\' is created.'), 
                        (string) $book->title));
        return new Pluf_HTTP_Response_Json($book);
    }

    /**
     * اطلاعات یک کتاب را می‌گیرد
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function get ($request, $match)
    {
        // تعیین داده‌ها
        $book = Pluf_Shortcuts_GetObjectOr404('Book_Book', $match['bookId']);
        // بررسی حق دسترسی
        Book_Precondition::userCanAccessBook($request, $book);
        // اجرای درخواست
        return new Pluf_HTTP_Response_Json($book);
    }

    /**
     * یک کتاب را به روز می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function update ($request, $match)
    {
        // تعیین داده‌ها
        $book = Pluf_Shortcuts_GetObjectOr404('Book_Book', $match['bookId']);
        // حق دسترسی
        Book_Precondition::userCanUpdateBook($request, $book);
        // اجرای درخواست
        $extra = array(
                'user' => $request->user,
                'book' => $book
        );
        $form = new Book_Form_BookUpdate(
                array_merge($request->REQUEST, $request->FILES), $extra);
        $book = $form->update();
        $request->user->setMessage(
                sprintf(__('new book \'%s\' is created.'), 
                        (string) $book->title));
        return new Pluf_HTTP_Response_Json($book);
    }

    /**
     * یک کتاب را حذف می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function delete ($request, $match)
    {
        // تعیین داده‌ها
        $book = Pluf_Shortcuts_GetObjectOr404('Book_Book', $match['bookId']);
        // بررسی حق دسترسی
        Book_Precondition::userCanDeleteBook($request, $book);
        // اجرای درخواست
        $book2 = Pluf_Shortcuts_GetObjectOr404('Book_Book', $match['bookId']);
        $book2->delete();
        return new Pluf_HTTP_Response_Json($book);
    }
}