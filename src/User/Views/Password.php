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
Pluf::loadFunction('User_Shortcuts_CheckPassword');

/**
 * Manage avatar image of user
 *
 * @author maso
 * @author hadi
 *        
 */
class User_Views_Password extends Pluf_Views
{

    /**
     * Updates passwrod
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public static function update ($request, $match)
    {
        $user = Pluf_Shortcuts_GetObjectOr404('Pluf_User', $match['userId']);
        if ($request->user->administrator || $user->id === $request->user->id) {
            $pass = User_Shortcuts_CheckPassword($request->REQUEST['password']);
            $user->setPassword($pass);
            $user->update();
        } else {
            throw new Pluf_Exception_PermissionDenied(
                    "You are not allowed to change password.");
        }
        return $user;
    }
    
    /**
     * Manages user password
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function password($request, $match)
    {
        $msg = array(
            'message' => 'succcess'
        );
        // TODO: maso, 2017: recover by mail
        if (array_key_exists('email', $request->REQUEST)) {
            $usr = $request->user->getOne('email=' . $request->REQUEST['email']);
            if ($user) {
                $this->sendPasswordToken($request, $user);
            }
            return $msg;
        }
        // TODO: maso, 2017: recover by login
        if (array_key_exists('login', $request->REQUEST)) {
            $usr = $request->user->getOne('login=' . $request->REQUEST['login']);
            if ($user) {
                $this->sendPasswordToken($request, $user);
            }
            return $msg;
        }
        // TODO: maso, 2017: reset by token
        if (array_key_exists('token', $request->REQUEST)) {
            $token = new User_PasswordToken();
            $token = $token->getOne('token='.$request->REQUEST['token']);
            if(!$token || $token->isExpired()){
                throw new Pluf_Exception_DoesNotExist('Token not exist');
            }
            $this->changePassword($request, $token);
            return $msg;
        }
        // TODO: maso, 2017: reset by old password
        if (array_key_exists('old', $request->REQUEST)) {}
        
        throw new Pluf_Exception_MismatchParameter('Invalid request params');
    }
    
    
    private function sendPasswordToken($request, $user){
        
    }
    
    private function changePassword($requst, $user){
        
    }
}
