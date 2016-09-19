<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Views_ApplicationMember
{

    /**
     * خلاصه‌ای از کاربران سیستم
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function summery ($request, $match)
    {
        return new Pluf_HTTP_Response_Json(
                $request->application->getMembershipData('txt'));
    }

    /**
     * فهرست تمام اعضا بر اساس نوع دسترسی
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function find ($request, $match)
    {
        // (owner|member|authorize)
        $permStr = $this->toPermission($match['role']);
        return $this->listUsers($request, $permStr);
    }

    /**
     * اضافه کردن یک دسترسی به یک کاربر
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function memberAdd ($request, $match)
    {
        // (owner|member|authorize) : permission
        $permStr = $this->toPermission($match[1]);
        // (\d+) : user
        $user = Pluf_Shortcuts_GetObjectOr404('Pluf_User', $match[2]);
        Pluf_RowPermission::add($user, $request->tenant, $permStr);
        return new Pluf_HTTP_Response_Json($request->tenant);
    }

    /**
     * حذف یک دسترسی از یک کاربر
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function memberRemove ($request, $match)
    {
        // (owner|member|authorize) : permission
        $permStr = $this->toPermission($match[1]);
        // (\d+) : user
        $user = Pluf_Shortcuts_GetObjectOr404('Pluf_User', $match[2]);
        Pluf_RowPermission::remove($user, $request->tenant, $permStr);
        return new Pluf_HTTP_Response_Json($request->tenant);
    }

    /**
     * فهرست کردن کاربران بر اساس نوع دسترسی
     *
     * در صورتی که نوع دسترسی مشخص باشد، با این فراخوانی می‌توانید فهرست کاربران
     * را ایجاد کنید.
     *
     * @param unknown $request            
     * @param unknown $permStr            
     */
    private function listUsers ($request, $permStr)
    {
        $operm = Pluf_Permission::getFromString($permStr);
        $db = & Pluf::db();
        $false = Pluf_DB_BooleanToDb(false, $db);
        $sql = new Pluf_SQL(
                'model_class=%s AND model_id=%s AND owner_class=%s AND permission=%s AND negative=' .
                         $false, 
                        array(
                                'SaaS_Application',
                                $request->tenant->id,
                                'Pluf_User',
                                $operm->id
                        ));
        
        $pag = new Pluf_Paginator(new Pluf_User());
        $pag->model_view = 'user_permission';
        $pag->forced_where = $sql;
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
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->items_per_page = $this->getListCount($request);
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    private function getListCount ($request)
    {
        $count = 10;
        if (array_key_exists('_px_count', $request->GET)) {
            $count = $request->GET['_px_count'];
        }
        return $count;
    }

    private function toPermission ($perm)
    {
        // (owner|member|authorize) : permission
        return 'SaaS.'.$perm;
    }
}