<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. http://dpq.co.ir
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
 * Manages groups of a role.
 *
 * @author hadi
 *        
 */
class Role_Views_Group extends Pluf_Views
{

    /**
     * Add new group to a role.
     * In other word, grant a role to a group.
     * Id of added group should be specified in request.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function add($request, $match)
    {
        $perm = Pluf_Shortcuts_GetObjectOr404('Pluf_Permission', $match['id']);
        $group = Pluf_Shortcuts_GetObjectOr404('Pluf_Group', $request->REQUEST['group']);
        $row = Pluf_RowPermission::add($group, null, $perm, false, $request->tenant->id);
        return new Pluf_HTTP_Response_Json($row);
    }

    /**
     * Returns list of groups of a role.
     * Resulted list can be customized by using filters, conditions and sort rules.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function find($request, $match)
    {
        $perm = new Pluf_Permission($match['id']);
        $grModel = new Pluf_Group();
        $pag = new Pluf_Paginator($grModel);
        $pag->items_per_page = Role_Views::getListCount($request);
        $perm_id_col = Pluf::f('pluf_use_rowpermission', false) ? 'permission' : 'pluf_permission_id';
        $sql = new Pluf_SQL($grModel->_a['table'].'.tenant=%s AND '.$perm_id_col.'=%s', array(
            $request->tenant->id,
            $perm->id
        ));
        $pag->forced_where = $sql;
        $pag->list_filters = array(
            'tenant',
            'version',
            'name',
            'description'
        );
        $pag->sort_order = array(
            'id',
            'ASC'
        );
        $search_fields = array(
            'name',
            'description'
        );
        $list_display = array(
            'name' => __('name'),
            'description' => __('description')
        );
        $sort_fields = array(
            'id',
            'name',
            'description'
        );
        $pag->model_view = 'group_permission';
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * Returns information of a group of a role.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function get($request, $match)
    {
        $perm = Pluf_Shortcuts_GetObjectOr404('Pluf_Permission', $match['id']);
        $groupModel = new Pluf_Group();
        $perm_id_col = Pluf::f('pluf_use_rowpermission', false) ? 'permission' : 'pluf_permission_id';
        $param = array(
            'view' => 'group_permission',
            'filter' => array(
                $groupModel->_a['table'].'.id=' . $match['groupId'],
                $groupModel->_a['table'].'.tenant=' . $request->tenant->id,
                $perm_id_col. '=' . $perm->id
            )
        );
        $groups = $groupModel->getList($param);
        if($groups->count() == 0){
            throw new Pluf_Exception_DoesNotExist('Group has not such role');
        }
        return new Pluf_HTTP_Response_Json($groups);
    }

    /**
     * Deletes a group from a role.
     * Id of deleted group should be specify in the match.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function delete($request, $match)
    {
        $perm = Pluf_Shortcuts_GetObjectOr404('Pluf_Permission', $match['id']);
        $owner = Pluf_Shortcuts_GetObjectOr404('Pluf_Group', $match['groupId']);
        $row = Pluf_RowPermission::remove($owner, null, $perm, $request->tenant->id);
        return new Pluf_HTTP_Response_Json($row);
    }
}
