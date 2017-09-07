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

Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');
Pluf::loadFunction('User_Shortcuts_UpdateProfile');

/**
 * Manage profile information of users.
 *
 * @author maso
 * @author hadi
 *        
 */
class User_Views_Profile
{

    /**
     * Returns profile information of specified user.
     * Data model of profile can be different in each system. Also loading information of user is lazy,
     * so profile is not loaded until a request occure.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function get($request, $match)
    {
        $profile_model = Pluf::f('user_profile_class', false);
        if ($profile_model === false) {
            throw new Pluf_Exception(__('Profile model is not configured.'));
        }
        $userId = $match['userId'];
        $user = Pluf_Shortcuts_GetObjectOr404('Pluf_User', $userId);
        try {
            $profile = $user->getProfile();
        } catch (Pluf_Exception_DoesNotExist $ex) {
            $profile = new $profile_model();
            $profile->user = $user;
            $profile->create();
        }
        return new Pluf_HTTP_Response_Json($profile);
    }

    /**
     * Update profile of specified user.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception
     * @return Pluf_HTTP_Response_Json
     */
    public static function update($request, $match)
    {
        // $profile_model = Pluf::f('user_profile_class', false);
        // if ($profile_model === false) {
        // throw new Pluf_Exception(__('Profile model is not configured.'));
        // }
        // try {
        // $profile = $request->user->getProfile();
        // } catch (Pluf_Exception_DoesNotExist $ex) {
        // $profile = new $profile_model();
        // $profile->user = $request->user;
        // $profile->create();
        // }
        
        // $profile_form = Pluf::f('user_profile_form', false);
        // if ($profile_form === false) {
        // throw new Pluf_Exception(__('Profile form is not configured.'));
        // }
        // $form = new $profile_form(array_merge($request->POST, $request->FILES), array(
        // 'user_profile' => $profile
        // ));
        // $profile = $form->update();
        // return new Pluf_HTTP_Response_Json($profile);
        // TODO: Hadi, 1395-07-23: should consider security permissions
        $currentUser = $request->user;
        $user = Pluf_Shortcuts_GetObjectOr404('Pluf_User', $match['userId']);
        if($currentUser->getId() === $user->getId() || Pluf_Precondition::ownerRequired($request))
            return User_Shortcuts_UpdateProfile($user, $request->REQUEST);
        throw new Pluf_Exception_PermissionDenied("Permission is denied");
    }
}
