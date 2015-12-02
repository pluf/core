<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * واسط برنامه سازی پیام‌های سیستم را ایجاد می‌کند.
 *
 * @date 1394 پیاده سازی اولیه از واسط مدیریت پیام‌ها
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class HM_Views_Message
{

    /**
     * پیش شرط‌های دسترسی به فهرست پیام‌ها
     *
     * @var unknown
     */
    public $messages_precond = array(
            'SaaS_Precondition::baseAccess'
    );

    /**
     * \breif فهرستی از تمام پیام‌ها ایجاد می‌کند.
     *
     * نتیجه فراخوانی این واسط تعیین تمام پیام‌هایی است که برای دامنه تعیین شده
     * است.
     * به صورت پیش فرض یک سقف برای این پیام‌ها در نظر گرفته شده است که تعداد
     * پیام‌ها
     * به آن محدود می‌وشد.
     *
     * \note این نمایش تنها از متد GET حمایت می‌کند.
     *
     * نکته اینکه در حال حاضر هیچ راهکاری برای تعیین تعداد پیام‌ها در نظر گرفته
     * نشده است.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function messages ($request, $match)
    {
        /*
         * TODO: maso, 1394: پارامترهای جستجو استفاده نشده است.
         * سه پارامتر زیر باید در جستجو استفاده شود، اگر توسط کاربر تعیین شده
         * باشد
         * -after
         * -before
         */
        
        // maso, 1394: گرفتن فهرست مناسبی از پیام‌ها
        // Paginator to paginate messages
        $pag = new Pluf_Paginator(new HM_Message());
        $pag->list_filters = array(
                'reporter',
                'community',
        );
        $pag->forced_where = new Pluf_SQL('apartment=%s', 
                array(
                        $request->application->id
                ));
        $list_display = array(
                'title' => __('part title')
        );
        $search_fields = array(
                'id',
                'title',
                'message'
        );
        $sort_fields = array(
                'id',
                'title',
                'creation_dtime',
                'modif_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->items_per_page = $this->getListCount($request);
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * پیش شرط‌های اضافه کردن یک پیام
     *
     * @var unknown
     */
    public $create_precond = array(
            'SaaS_Precondition::applicationOwner'
    );

    /**
     * @breif ایجاد یک پیام را انجام می‌دهد
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function create ($request, $match)
    {
        $params = array(
                'application' => $request->application,
                'message' => null
        );
        $form = new HM_Form_Message(array_merge($request->REQUEST, $request->FILES), 
                $params);
        $message = $form->save();
        return new Pluf_HTTP_Response_Json($message);
    }

    public $get_precond = array(
            'SaaS_Precondition::baseAccess'
    );

    /**
     * گرفتن یک پیام
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function get ($request, $match)
    {
        $message = Pluf_Shortcuts_GetObjectOr404('HM_Message', $match[2]);
        if ($message->apartment !== $request->application->id) {
            throw new Pluf_Exception_PermissionDenied();
        }
        return new Pluf_HTTP_Response_Json($message);
    }

    /**
     * پیش شرط‌های به روز رسانی یک پیام را تعیین می‌کند
     * 
     * @var unknown
     */
    public $update_precond = array(
            'SaaS_Precondition::applicationOwner'
    );

    /**
     * به روز کردن یک پیام
     * 
     * @param unknown $request
     * @param unknown $match
     * @throws Pluf_Exception_PermissionDenied
     * @return Pluf_HTTP_Response_Json
     */
    public function update ($request, $match)
    {
        $message = Pluf_Shortcuts_GetObjectOr404('HM_Message', $match[2]);
        if ($message->apartment !== $request->application->id) {
            throw new Pluf_Exception_PermissionDenied();
        }
        $params = array(
                'application' => $request->application,
                'message' => $message
        );
        $form = new HM_Form_Message(array_merge($request->POST, $request->FILES), 
                $params);
        $message = $form->update();
        return new Pluf_HTTP_Response_Json($message);
    }

    /**
     * پیش شرط‌های حذف یک پیام را تعیین می‌کند
     * @var unknown
     */
    public $delete_precond = array(
            'SaaS_Precondition::applicationOwner'
    );

    /**
     * یک پیام را حذف می‌کند.
     * 
     * @param unknown $request
     * @param unknown $match
     * @throws Pluf_Exception_PermissionDenied
     * @return Pluf_HTTP_Response_Json
     */
    public function delete ($request, $match)
    {
        $message = Pluf_Shortcuts_GetObjectOr404('HM_Message', $match[2]);
        if ($message->apartment !== $request->application->id) {
            throw new Pluf_Exception_PermissionDenied();
        }
        $message->delete();
        return new Pluf_HTTP_Response_Json($message);
    }

    /**
     * تعداد گزینه‌های یک لیست را تعیین می‌کند.
     *
     * TODO: maso, 1394: این تعداد می‌تواند برای کاربران متفاوت باشد.
     *
     * @param unknown $request            
     * @return number
     */
    private function getListCount ($request)
    {
        $count = 5;
        if (array_key_exists('_px_count', $request->GET)) {
            $count = $request->GET['_px_count'];
            if ($count > 20) {
                $count = 20;
            }
        }
        return $count;
    }
}
