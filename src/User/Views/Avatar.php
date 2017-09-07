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
Pluf::loadFunction('User_Shortcuts_GetAvatar');
Pluf::loadFunction('User_Shortcuts_DeleteAvatar');
Pluf::loadFunction('User_Shortcuts_UpdateAvatar');

/**
 * Manage avatar image of user
 *
 * @author maso
 * @author hadi
 *        
 */
class User_Views_Avatar extends Pluf_Views
{

    /**
     * Returns avatar image of user.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function get($request, $match)
    {
        $user = Pluf_Shortcuts_GetObjectOr404('Pluf_User', $match['userId']);
        return User_Shortcuts_GetAvatar($user);
    }

    /**
     * Updates avatar image of user.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function update($request, $match)
    {
        if ($request->user->getId() != $match['userId']) {
            throw new Pluf_Exception_PermissionDenied();
        }        
        $user = Pluf_Shortcuts_GetObjectOr404('Pluf_User', $match['userId']);
        return User_Shortcuts_UpdateAvatar($user, array_merge($request->REQUEST, $request->FILES));
    }

    /**
     * Deletes avatar images of user.
     * This action may set default avatar for user.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function delete($request, $match)
    {
        if ($request->user->getId() != $match['userId']) {
            return new Pluf_Exception_PermissionDenied();
        }
        $user = Pluf_Shortcuts_GetObjectOr404('Pluf_User', $match['userId']);
        return User_Shortcuts_DeleteAvatar($user);
    }
}
