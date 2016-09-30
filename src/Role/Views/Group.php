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
     * Add new group to a role. In other word, grant a role to a group.
     * Id of added group should be specified in request.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function add($request, $match)
    {
        throw new Pluf_Exception('Not supported');
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
        throw new Pluf_Exception('Not supported');
    }

    /**
     * Returns information of a group of a role.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function get($request, $match)
    {
        throw new Pluf_Exception('Not supported');
    }

    /**
     * Deletes a group from a role.
     * Id of deleted group should be specified in the match.
     * 
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function delete($request, $match)
    {
        $perm = Pluf_Shortcuts_GetObjectOr404('Pluf_Permission', $match['id']);
        $owner = Pluf_Shortcuts_GetObjectOr404('Pluf_Group', $match['groupId']);
        Pluf_RowPermission::remove($owner, null, $perm, $request->tenant->id);
        return new Pluf_HTTP_Response_Json($owner);
    }
}
