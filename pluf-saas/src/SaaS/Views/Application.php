<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_RenderToResponse');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Views_Application extends Pluf_Views
{

    /**
     * پیش شرط‌های دسترسی به فهرست نرم‌افزارها
     *
     * @var unknown
     */
    public $applications_precond = array();

    /**
     * فهرستی از نرم‌افزارها ایجاد می‌کند
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function applications ($request, $match)
    {
        /*
         * TODO: maso, 1394: پارامترهای جستجو استفاده نشده است.
         * سه پارامتر زیر باید در جستجو استفاده شود، اگر توسط کاربر تعیین شده
         * باشد
         * -after
         * -before
         * -count
         */
        if ($request->method != 'GET') {
            throw new Pluf_Exception_GetMethodSuported();
        }
        // maso, 1394: گرفتن فهرست مناسبی از آپارتمان‌ها
        $pag = new Pluf_Paginator(new SaaS_Application());
        $list_display = array(
                'id' => __('application id'),
                'title' => __('title'),
                'creation_dtime' => __('create')
        );
        $search_fields = array();
        $sort_fields = array(
                'creation_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->action = array();
        $pag->items_per_page = $this->getListCount($request);
        $pag->no_results_text = __('No apartment is added yet.');
        $pag->sort_order = array(
                'creation_dtime',
                'DESC'
        );
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_GetMethodSuported
     * @throws Pluf_Exception
     * @return Pluf_HTTP_Response_Json
     */
    public function currentApplication ($request, $match)
    {
        if ($request->method != 'GET') {
            throw new Pluf_Exception_GetMethodSuported();
        }
        if (isset($request->application)) {
            return new Pluf_HTTP_Response_Json($request->application);
        }
        throw new Pluf_Exception("Application is not set!");
    }

    /**
     * پیش شرط‌های دسترسی را تعیین می‌کند
     *
     * @var unknown
     */
    public $application_precond = array();

    /**
     * دسترسی به یک نرم‌افزار کاربردی
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_GetMethodSuported
     * @return Pluf_HTTP_Response_Json
     */
    public function application ($request, $match)
    {
        $application_id = $match[1];
        $application = Pluf_Shortcuts_GetObjectOr404('SaaS_Application', 
                $application_id);
        if ($request->method == 'GET') {
            return new Pluf_HTTP_Response_Json($application);
        }
        throw new Pluf_Exception_GetMethodSuported();
    }

    /**
     *
     * @var unknown
     */
    public $members_precond = array(
            'Pluf_Precondition::loginRequired'
    );

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function members ($request, $match)
    {
        $application_id = $match[1];
        $application = Pluf_Shortcuts_GetObjectOr404('SaaS_Application', 
                $application_id);
        return new Pluf_HTTP_Response_Json(
                $application->getMembershipData('txt'));
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