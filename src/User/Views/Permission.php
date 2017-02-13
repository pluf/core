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
        // XXX: maso, 1395: check user access.
        $model = Pluf_Shortcuts_GetObjectOr404('Pluf_User', $match['userId']);
        $pag = new Pluf_Paginator(new Pluf_Permission());
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
        $pag->sort_order = array(
                'version',
                'DESC'
        );
        $pag->setFromRequest($request);
        $pag->model_view = 'join_row_permission';
        $pag->forced_where = new Pluf_SQL(
                'rowpermissions.owner_id=%s AND rowpermissions.owner_class=%s', 
                array(
                        $model->id,
                        $model->_a['model']
        ));
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function create ($request, $match)
    {
        // XXX: maso, 1395: check user access.
        $user = Pluf_Shortcuts_GetObjectOr404('Pluf_User', $match['userId']);
        $perm = Pluf_Shortcuts_GetObjectOr404('Pluf_Permission', $request->REQUEST['id']);
        Pluf_RowPermission::add($user, null, $perm, false);
        return new Pluf_HTTP_Response_Json($user);
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function get ($request, $match)
    {
        $perm = Pluf_Shortcuts_GetObjectOr404('Pluf_Permission', $match['roleId']);
        return new Pluf_HTTP_Response_Json($perm);
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function delete ($request, $match)
    {
        // XXX: maso, 1395: check user access.
        $user = Pluf_Shortcuts_GetObjectOr404('Pluf_User', $match['userId']);
        $perm = Pluf_Shortcuts_GetObjectOr404('Pluf_Permission', $match['roleId']);
        Pluf_RowPermission::remove($user, null, $perm);
        return new Pluf_HTTP_Response_Json($user);
    }
}
