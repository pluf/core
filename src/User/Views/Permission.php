<?php

/**
 * لایه نمایش مدیریت کاربران را به صورت پیش فرض ایجاد می‌کند
 *
 * @author maso
 *        
 */
class User_Views_Permission
{

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function find ($request, $match)
    {
        // XXX: maso, 1395: این فراخوانی رو برای تست نوشتم. خیلی تغییر نیاز داره
        $pag = new Pluf_Paginator(new Pluf_RowPermission());
        $pag->configure(array(), 
                array( // search
                        'name',
                        'description'
                ), 
                array( // sort
                        'id',
                        'name',
                        'application',
                        'version'
                ));
        $pag->action = array();
        $pag->items_per_page = 20;
        $pag->sort_order = array(
                'version',
                'DESC'
        );
        $pag->setFromRequest($request);
        $pag->model_view = 'join_permission';
        if (! $request->user->administrator) {
            $pag->forced_where = $sql = new Pluf_SQL(
                    'owner_id=%s AND owner_class=%s AND tenant=%s', 
                    array(
                            $request->user->id,
                            $request->user->_a['model'],
                            $request->tenant->id
                    ));
        }
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function create ($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function get ($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function delete ($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }
}
