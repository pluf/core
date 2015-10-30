<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Wiki_Shortcuts_GetPageOr404');

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
     * نمایش برگه اصلی سایت
     *
     * در این نمایش اطلاعات کلی کارگزار نمایش داده می‌شود. این نمایش می‌تواند در
     * حالت واسط
     * برنامه سازی نیز به کار رود.
     * این فراخوانی که معادل با ورودی کاربر به سیستم است، منجر به بازیابی
     * نرم‌افزار home
     * می‌شود.
     *
     * @param
     *            $request
     * @param
     *            $match
     */
    public function index ($request, $match)
    {
        $languate = $match[1];
        $pageTitle = $match[2];
        $repos = Pluf::f('wiki_repositories', array());
        foreach ($repos as $name => $path) {
            $filename = $path . DIRECTORY_SEPARATOR . $languate .
                     DIRECTORY_SEPARATOR . $pageTitle . ".md";
            if (is_readable($filename)) {
                $page = new Wiki_Page();
                $page->title = $pageTitle;
                $page->language = $languate;
                $page->summary = "";
                $myfile = fopen($filename, "r") or die("Unable to open file!");
                $page->content = fread($myfile, filesize($filename));
                fclose($myfile);
                $page->creation_dtime = gmdate('Y-m-d H:i:s');
                $page->modif_dtime = gmdate('Y-m-d H:i:s');
                return new Pluf_HTTP_Response_Json($page);
            }
        }
        throw new Wiki_Exception_PageNotFound(__('requeisted page not found.'));
    }

    public function create ($request, $match)
    {
        // initial page data
        $extra = array(
                'user' => $request->user
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

    public function get ($request, $match)
    {
        // XXX: maso, 1394: بررسی حق دسترسی
        $page = Wiki_Shortcuts_GetPageOr404($match[1]);
        return new Pluf_HTTP_Response_Json($page);
    }

    public function delete ($request, $match)
    {
        // XXX: maso, 1394: بررسی حق دسترسی
        $page = Wiki_Shortcuts_GetPageOr404($match[1]);
        $page2 = new Wiki_Page($page->id);
        $page2->delete();
        return new Pluf_HTTP_Response_Json($page);
    }

    public function find ($request, $match)
    {
        // maso, 1394: گرفتن فهرست مناسبی از پیام‌ها
        $pag = new Pluf_Paginator(new Wiki_Page());
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
        $pag->items_per_page = $this->getListCount($request);
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function labels ($request, $match)
    {
        $page = Wiki_Shortcuts_GetPageOr404($match[1]);
        $labels = $page->get_label_list();
        return new Pluf_HTTP_Response_Json($labels);
    }

    public function addLabel ($request, $match)
    {
        $page = Wiki_Shortcuts_GetPageOr404($match[1]);
        $label = Pluf_Shortcuts_GetObjectOr404('KM_Label', $match[2]);
        $page->setAssoc($label);
        return new Pluf_HTTP_Response_Json($page);
    }

    public function removeLabel ($request, $match)
    {
        $page = Wiki_Shortcuts_GetPageOr404($match[1]);
        $label = Pluf_Shortcuts_GetObjectOr404('KM_Label', $match[2]);
        $page->delAssoc($label);
        return new Pluf_HTTP_Response_Json($page);
    }

    public function categories ($request, $match)
    {
        $page = Wiki_Shortcuts_GetPageOr404($match[1]);
        $cats = $page->get_category_list();
        return new Pluf_HTTP_Response_Json($cats);
    }

    public function addCategory ($request, $match)
    {
        $page = Wiki_Shortcuts_GetPageOr404($match[1]);
        $cat = Pluf_Shortcuts_GetObjectOr404('KM_Category', $match[2]);
        $page->setAssoc($cat);
        return new Pluf_HTTP_Response_Json($page);
    }

    public function removeCategory ($request, $match)
    {
        $page = Wiki_Shortcuts_GetPageOr404($match[1]);
        $cat = Pluf_Shortcuts_GetObjectOr404('KM_Category', $match[2]);
        $page->delAssoc($cat);
        return new Pluf_HTTP_Response_Json($page);
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