<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * لایه نمایش کتاب‌ها را ایجاد می‌کند.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 */
class Wiki_Views_Book
{

    public $create_precond = array();

    public function create ($request, $match)
    {
        // initial page data
        $extra = array(
                'user' => $request->user
        );
        $form = new Wiki_Form_BookCreate(
                array_merge($request->REQUEST, $request->FILES), $extra);
        $book = $form->save();
        $request->user->setMessage(
                sprintf(__('new book \'%s\' is created.'), 
                        (string) $book->title));
        return new Pluf_HTTP_Response_Json($book);
    }

    public $get_precond = array();

    public function get ($request, $match)
    {
        // XXX: maso, 1394: بررسی حق دسترسی
        $book = Pluf_Shortcuts_GetObjectOr404('Wiki_Book', $match[1]);
        return new Pluf_HTTP_Response_Json($book);
    }

    public $update_precond = array();

    public function update ($request, $match)
    {
        // XXX: maso, 1394: بررسی حق دسترسی
        $book = Pluf_Shortcuts_GetObjectOr404('Wiki_Book', $match[1]);
        // initial page data
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

    public $delete_precond = array();

    public function delete ($request, $match)
    {
        // XXX: maso, 1394: بررسی حق دسترسی
        $book = Pluf_Shortcuts_GetObjectOr404('Wiki_Book', $match[1]);
        $book2 = new Wiki_Page($book->id);
        $book2->delete();
        return new Pluf_HTTP_Response_Json($book);
    }

    public $find_precond = array();

    public function find ($request, $match)
    {
        // maso, 1394: گرفتن فهرست مناسبی از پیام‌ها
        $pag = new Pluf_Paginator(new Wiki_Book());
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
        $pag->items_per_page = $this->getListCount($request);
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    public function labels ($request, $match)
    {
        $book = Pluf_Shortcuts_GetObjectOr404('Wiki_Book', $match[1]);
        $labels = $book->get_label_list();
        return new Pluf_HTTP_Response_Json($labels);
    }
    
    public function addLabel ($request, $match)
    {
        $book = Pluf_Shortcuts_GetObjectOr404('Wiki_Book', $match[1]);
        $label = Pluf_Shortcuts_GetObjectOr404('KM_Label', $match[2]);
        $book->setAssoc($label);
        return new Pluf_HTTP_Response_Json($book);
    }
    
    public function removeLabel ($request, $match)
    {
        $book = Pluf_Shortcuts_GetObjectOr404('Wiki_Book', $match[1]);
        $label = Pluf_Shortcuts_GetObjectOr404('KM_Label', $match[2]);
        $book->delAssoc($label);
        return new Pluf_HTTP_Response_Json($book);
    }
    
    private function getListCount ($request)
    {
        $count = 20;
        if (array_key_exists('_px_count', $request->GET)) {
            $count = $request->GET['_px_count'];
            if ($count > 20) {
                $count = 20;
            }
        }
        return $count;
    }
}