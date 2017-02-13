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
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * Manages roles of a group.
 *
 * @author maso
 * @author hadi
 *        
 */
class Group_Views_Role extends Pluf_Views
{

    /**
     * Adds a role to list of roles of a group.
     * Id of added role should be specified in request.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function add($request, $match)
    {
        $group = Pluf_Shortcuts_GetObjectOr404('Pluf_Group', $match['groupId']);
        $permission = Pluf_Shortcuts_GetObjectOr404('Pluf_Permission', $request->REQUEST['role']);
        $row = Pluf_RowPermission::add($group, 'Pluf_Group', $permission, false);
        // $group->setAssoc($permission);
        return new Pluf_HTTP_Response_Json($row);
    }

    /**
     * Returns list of roles of a group.
     * Returned list can be customized with some filter, condition and sort.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function find($request, $match)
    {
        // $group = Pluf_Shortcuts_GetObjectOr404('Pluf_Group', $match['groupId']);
        // $roles = $group->get_permissions_list();
        // return new Pluf_HTTP_Response_Json($roles);
        $pag = new Pluf_Paginator(new Pluf_Permission());
        $pag->items_per_page = Group_Views::getListCount($request);
        $sql = new Pluf_SQL('pluf_group_id=%s', array(
            $match['groupId']
        ));
        $pag->forced_where = $sql;
        $pag->list_filters = array(
            'name',
            'code_name',
            'application'
        );
        $search_fields = array(
            'name',
            'code_name',
            'description',
            'application'
        );
        $list_display = array(
            'name' => __('name'),
            'code_name' => __('code name'),
            'application' => __('application'),
            'description' => __('description')
        );
        $sort_fields = array(
            'id',
            'name',
            'code_name',
            'application',
            'description'
        );
        $pag->model_view = 'join_group';
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * Returns information of a role of a group.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function get($request, $match)
    {
        $group = Pluf_Shortcuts_GetObjectOr404('Pluf_Group', $match['groupId']);
        $roleModel = new Pluf_Permission();
        $param = array(
            'view' => 'join_group',
            'filter' => array(
                'id=' . $match['roleId'],
                'pluf_group_id=' . $group->id
            )
        );
        $roles = $roleModel->getList($param);
        if ($roles->count() == 0) {
            throw new Pluf_Exception_DoesNotExist('Group has not such role');
        }
        return new Pluf_HTTP_Response_Json($roles);
    }

    /**
     * Deletes a role from roles of a group.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function delete($request, $match)
    {
        $group = Pluf_Shortcuts_GetObjectOr404('Pluf_Group', $match['groupId']);
        $permission = Pluf_Shortcuts_GetObjectOr404('Pluf_Permission', $match['roleId']);
        $row = Pluf_RowPermission::remove($group, 'Pluf_Group', $permission);
        // $group->delAssoc($permission);
        return new Pluf_HTTP_Response_Json($row);
    }
}
