<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('User_Shortcuts_RemoveSecureData');
Pluf::loadFunction('Wiki_Shortcuts_GetBookOr404');
Pluf::loadFunction('Wiki_Shortcuts_GetBookListCount');

/**
 * لایه نمایش کتاب‌ها را ایجاد می‌کند.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 */
class Wiki_Views_Book
{

    public function create ($request, $match)
    {
        // initial page data
        $extra = array(
                'user' => $request->user,
                'tenant' => $request->tenant
        );
        $form = new Wiki_Form_BookCreate(
                array_merge($request->REQUEST, $request->FILES), $extra);
        $book = $form->save();
        $request->user->setMessage(
                sprintf(__('new book \'%s\' is created.'), 
                        (string) $book->title));
        return new Pluf_HTTP_Response_Json($book);
    }

    public function get ($request, $match)
    {
        // تعیین داده‌ها
        $book = Wiki_Shortcuts_GetBookOr404($match[1]);
        // بررسی حق دسترسی
        Wiki_Precondition::userCanAccessBook($request, $book);
        // اجرای درخواست
        return new Pluf_HTTP_Response_Json($book);
    }

    public function update ($request, $match)
    {
        // تعیین داده‌ها
        $book = Wiki_Shortcuts_GetBookOr404($match[1]);
        // حق دسترسی
        Wiki_Precondition::userCanUpdateBook($request, $book);
        // اجرای درخواست
        $extra = array(
                'user' => $request->user,
                'book' => $book
        );
        $form = new Wiki_Form_BookUpdate(
                array_merge($request->REQUEST, $request->FILES), $extra);
        $book = $form->update();
        $request->user->setMessage(
                sprintf(__('new book \'%s\' is created.'), 
                        (string) $book->title));
        return new Pluf_HTTP_Response_Json($book);
    }

    public function delete ($request, $match)
    {
        // تعیین داده‌ها
        $book = Wiki_Shortcuts_GetBookOr404($match[1]);
        // بررسی حق دسترسی
        Wiki_Precondition::userCanDeleteBook($request, $book);
        // اجرای درخواست
        $book2 = Wiki_Shortcuts_GetBookOr404($match[1]);
        $book2->delete();
        return new Pluf_HTTP_Response_Json($book);
    }

    public function find ($request, $match)
    {
        // maso, 1394: گرفتن فهرست مناسبی از پیام‌ها
        $pag = new Pluf_Paginator(new Wiki_Book());
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
                'summary'
        );
        $sort_fields = array(
                'id',
                'title',
                'creation_date',
                'modif_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->items_per_page = Wiki_Shortcuts_GetBookListCount($request);
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    public function interestedUsers ($request, $match)
    { // تعیین داده‌ها
        $book = Wiki_Shortcuts_GetBookOr404($match[1]);
        // بررسی دسترسی
        Wiki_Precondition::userCanAccessBook($request, $book);
        // اجرای درخواست
        $intre = $book->get_interested_list();
        foreach ($intre as $key => $value) {
            User_Shortcuts_RemoveSecureData($value);
        }
        return new Pluf_HTTP_Response_Json($intre);
    }

    public function addInterestedUser ($request, $match)
    {
        // تعیین داده‌ها
        $book = Wiki_Shortcuts_GetBookOr404($match[1]);
        // بررسی دسترسی
        Wiki_Precondition::userCanInterestedInBook($request, $book);
        // اجرای درخواست
        $book->setAssoc($request->user);
        return new Pluf_HTTP_Response_Json($book);
    }

    public function removeInterestedUser ($request, $match)
    {
        // تعیین داده‌ها
        $book = Wiki_Shortcuts_GetBookOr404($match[1]);
        // اجرای درخواست
        $book->delAssoc($request->user);
        return new Pluf_HTTP_Response_Json($book);
    }

    public function labels ($request, $match)
    {
        // تعیین داده‌ها
        $book = Wiki_Shortcuts_GetBookOr404($match[1]);
        // بررسی حق دسترسی
        Wiki_Precondition::userCanAccessBook($request, $book);
        // اجرای درخواست
        $labels = $book->get_label_list();
        return new Pluf_HTTP_Response_Json($labels);
    }

    public function addLabel ($request, $match)
    {
        // تعیین داده‌ها
        $book = Wiki_Shortcuts_GetBookOr404($match[1]);
        $label = Pluf_Shortcuts_GetObjectOr404('KM_Label', $match[2]);
        // بررسی دسترسی
        Wiki_Precondition::userCanUpdateBook($request, $book);
        // اجرای درخواست
        $book->setAssoc($label);
        return new Pluf_HTTP_Response_Json($book);
    }

    public function removeLabel ($request, $match)
    {
        // تعیین داده‌ها
        $book = Wiki_Shortcuts_GetBookOr404($match[1]);
        $label = Pluf_Shortcuts_GetObjectOr404('KM_Label', $match[2]);
        // بررسی دسترسی
        Wiki_Precondition::userCanUpdateBook($request, $book);
        // اجرای درخواست
        $book->delAssoc($label);
        return new Pluf_HTTP_Response_Json($book);
    }

    public function categories ($request, $match)
    {
        // تعیین داده‌ها
        $book = Wiki_Shortcuts_GetBookOr404($match[1]);
        // تعیین دسترسی
        Wiki_Precondition::userCanAccessBook($request, $book);
        // اجرای درخواست
        $cats = $book->get_category_list();
        return new Pluf_HTTP_Response_Json($cats);
    }

    public function addCategory ($request, $match)
    {
        // تعیین داده‌ها
        $book = Wiki_Shortcuts_GetBookOr404($match[1]);
        $cat = Pluf_Shortcuts_GetObjectOr404('KM_Category', $match[2]);
        // بررسی دسترسی
        Wiki_Precondition::userCanUpdateBook($request, $book);
        // اجرای درخواست
        $book->setAssoc($cat);
        return new Pluf_HTTP_Response_Json($book);
    }

    public function removeCategory ($request, $match)
    {
        // تعیین داده‌ها
        $book = Wiki_Shortcuts_GetBookOr404($match[1]);
        $cat = Pluf_Shortcuts_GetObjectOr404('KM_Category', $match[2]);
        // تعیین دسترسی
        Wiki_Precondition::userCanUpdateBook($request, $book);
        // اجرای دستور
        $book->delAssoc($cat);
        return new Pluf_HTTP_Response_Json($book);
    }

    /**
     * فهرست تمام صفحه‌ها را به صورت آرایه تعیین می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function pages ($request, $match)
    {
        // تعیین داده‌ها
        $book = Wiki_Shortcuts_GetBookOr404($match[1]);
        $page = new Wiki_Page();
        // تعیین دسترسی‌ها
        Wiki_Precondition::userCanAccessBook($request, $book);
        // اجرای دستور
        $pages = $page->getList(
                array(
                        'view' => 'page_list',
                        'filter' => 'book=' . $book->id
                ));
        return new Pluf_HTTP_Response_Json($pages->getArrayCopy());
    }

    /**
     * صفحه را به کتاب اضافه می‌کند
     * 
     * @param unknown $request
     * @param unknown $match
     * @throws Pluf_Exception
     * @return Pluf_HTTP_Response_Json
     */
    public function addPage ($request, $match)
    {
        // تعیین داده‌ها
        $book = Wiki_Shortcuts_GetBookOr404($match[1]);
        $page = Wiki_Shortcuts_GetPageOr404($match[2]);
        // بررسی دسترسی‌ها
        Wiki_Precondition::userCanUpdateBook($request, $book);
        Wiki_Precondition::userCanUpdatePage($request, $page);
        if($book->tenant != $page->tenant) {
            throw new Pluf_Exception("You are about to add a page from other tenant.");
        }
        // اجرای دستور
        if ($page->book != 0) {
            throw new Pluf_Exception("Page is added into the another book.");
        }
        $page->book = $book;
        $page->update();
        return new Pluf_HTTP_Response_Json($book);
    }

    /**
     * یک صفحه را از کتاب حذف می‌کند
     * 
     * @param unknown $request
     * @param unknown $match
     * @throws Pluf_Exception
     * @return Pluf_HTTP_Response_Json
     */
    public function removePage ($request, $match)
    {
        // تعیین داده‌ها
        $book = Wiki_Shortcuts_GetBookOr404($match[1]);
        $page = Pluf_Shortcuts_GetObjectOr404('Wiki_Page', $match[2]);
        // تعیین دسترسی
        Wiki_Precondition::userCanUpdateBook($request, $book);
        // اجرای دستور
        if ($page->book != $book->id) {
            throw new Pluf_Exception("Page is not part of the book.");
        }
        $page->book = new Wiki_Book();
        $page->update();
        return new Pluf_HTTP_Response_Json($book);
    }
}