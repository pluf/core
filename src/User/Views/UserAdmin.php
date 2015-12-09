<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');
Pluf::loadFunction('User_Shortcuts_UserJsonResponse');

/**
 * لایه نمایش مدیریت کاربران را به صورت پیش فرض ایجاد می‌کند
 *
 * @author maso
 *        
 */
class User_Views_UserAdmin extends User_Views_User
{

    /**
     * پیش نیازهای دسترسی به فهرست کاربران
     *
     * @var unknown
     */
    public $users_precond = array(
            'Pluf_Precondition::staffRequired'
    );

    /**
     * فهرست تمام کاربران را نمایش می‌دهد
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function users ($request, $match)
    {
        $pag = new Pluf_Paginator(new Pluf_User());
        $pag->list_filters = array(
                'administrator',
                'staff',
                'active'
        );
        $list_display = array(
                'login' => __('User name'),
                'first_name' => __('First name'),
                'last_name' => __('Last name')
        );
        $search_fields = array(
                'login',
                'first_name',
                'last_name',
                'email'
        );
        $sort_fields = array(
                'id',
                'login',
                'first_name',
                'last_name',
                'date_joined',
                'last_login'
        );
        $pag->model_view = 'secure';
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->items_per_page = $this->getListCount($request);
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * پیش نیازهای فهرست کردن کاربران
     *
     * @var unknown
     */
    public $getUser_precond = array(
            'Pluf_Precondition::loginRequired'
    );

    /**
     * مدیریت یک کاربر را در سیستم ایجاد می‌کند
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function getUser ($request, $match)
    {
        $user_id = $match[1];
        if ($user_id == $request->user->id) {
            return $this->account($request, $match);
        }
        throw new Pluf_Exception_NotImplemented();
    }
    
    
    /**
     * پیش نیازهای به روز رسانی یک کاربر
     * @var unknown
     */
    public $updateUser_precond = array(
            'Pluf_Precondition::loginRequired'
    );

    /**
     * مدیریت یک کاربر را در سیستم ایجاد می‌کند
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function updateUser ($request, $match)
    {
        $user_id = $match[1];
        if ($user_id == $request->user->id) {
            return $this->update($request, $match);
        }
        throw new Pluf_Exception_NotImplemented();
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
        $count = 10;
        if (array_key_exists('_px_count', $request->GET)) {
            $count = $request->GET['_px_count'];
        }
        return $count;
    }
}
