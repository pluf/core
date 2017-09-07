<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */


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
        $pag->sort_order = array(
                'version',
                'DESC'
        );
        $pag->setFromRequest($request);
        $pag->model_view = 'join_permission';
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
        // Hadi, 1396: check user access
        $user = Pluf_Shortcuts_GetObjectOr404('Pluf_User', $match['userId']);
        $perm = Pluf_Shortcuts_GetObjectOr404('Pluf_Permission', $request->REQUEST['id']);
        Pluf_Precondition::couldAddRole($request, $user->id, $perm->id);
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
        // Hadi, 1396: check user access
        $user = Pluf_Shortcuts_GetObjectOr404('Pluf_User', $match['userId']);
        $perm = Pluf_Shortcuts_GetObjectOr404('Pluf_Permission', $match['roleId']);
        Pluf_Precondition::couldRemoveRole($request, $user->id, $perm->id);
        Pluf_RowPermission::remove($user, null, $perm);
        return new Pluf_HTTP_Response_Json($user);
    }
}
