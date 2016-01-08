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
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function membersList ($request, $match)
    {
        return new Pluf_HTTP_Response_Json(
                $request->application->getMembershipData('txt'));
    }

    public function owners ($request, $match)
    {
        $operm = Pluf_Permission::getFromString('SaaS.software-owner');
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
        return $this->listUsers($request, $sql);
    }

    public function ownerAdd ($request, $match)
    {
        $user = new Pluf_User($match[1]);
        Pluf_RowPermission::add($user, $request->tenant, 'SaaS.software-owner');
        return new Pluf_HTTP_Response_Json($request->tenant);
    }

    public function ownerRemove ($request, $match)
    {
        $user = new Pluf_User($match[1]);
        Pluf_RowPermission::remove($user, $request->tenant, 'SaaS.software-owner');
        return new Pluf_HTTP_Response_Json($request->tenant);
    }
    
    
    private function listUsers ($request, $sql)
    {
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
}