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
    
    const SETTING_KEY_RESET_PASSWORD_EMAIL_TITLE = 'user.reset_pass_email_title';

    /**
     * Updates passwrod
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function update($request, $match)
    {
        $user = Pluf_Shortcuts_GetObjectOr404('Pluf_User', $match['userId']);
        if ($request->user->administrator || $user->id === $request->user->id) {
            $pass = User_Shortcuts_CheckPassword($request->REQUEST['password']);
            $user->setPassword($pass);
            $user->update();
        } else {
            throw new Pluf_Exception_PermissionDenied("You are not allowed to change password.");
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
            $sql = new Pluf_SQL('email=%s', array(
                $request->REQUEST['email']
            ));
            $user = $request->user->getOne($sql->gen());
            if ($user) {
                $this->sendPasswordToken($request, $user);
            }
            return $msg;
        }
        // TODO: maso, 2017: recover by login
        if (array_key_exists('login', $request->REQUEST)) {
            $sql = new Pluf_SQL('login=%s', array(
                $request->REQUEST['login']
            ));
            $user = $request->user->getOne($sql->gen());
            if ($user) {
                $this->sendPasswordToken($request, $user);
            }
            return $msg;
        }
        // TODO: maso, 2017: reset by token
        if (array_key_exists('token', $request->REQUEST)) {
            $token = new User_PasswordToken();
            $sql = new Pluf_SQL('token=%s', array(
                $request->REQUEST['token']
            ));
            $token = $token->getOne($sql->gen());
            if (! $token || $token->isExpired()) {
                throw new Pluf_Exception_DoesNotExist('Token not exist');
            }
            $user = $token->get_user();
            $user->setPassword($request->REQUEST['new']);
            $user->update();
            $token->delete();
            return $msg;
        }
        // TODO: maso, 2017: reset by old password
        if (array_key_exists('old', $request->REQUEST)) {
            if ($request->user->isAnonymous() || ! $request->user->checkPassword($request->REQUEST['old'])) {
                throw new Pluf_Exception_MismatchParameter('Old pass is not currect');
            }
            $request->user->setPassword($request->REQUEST['new']);
            $request->user->update();
            return $msg;
        }
        
        throw new Pluf_Exception_MismatchParameter('Invalid request params');
    }

    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param Pluf_User $user
     */
    private function sendPasswordToken($request, $user)
    {
        $token = new User_PasswordToken();
        // 1- remove old tokens
        $sql = new Pluf_Sql('user=%s', array(
            $user->id
        ));
        $old = $token->getOne($sql->gen());
        if (isset($old)) {
            $old->delete();
        }
        
        // 2- create new token
        $token->user = $user;
        $token->create();
        
        $callback = $this->generateCallback($request, $token);
        
        // 3- Notify user
        if (Pluf::f('test_unit', false)) {
            return;
        }
        $mailSubject = Setting_Service::get(SETTING_KEY_RESET_PASSWORD_EMAIL_TITLE, 'Reset password');
        $context = array(
            'subject' => mailSubject,
            'user' => $user,
            'token' => $token,
            'callback' => $callback
        );
        User_Notify::push($user, array(
            'Mail' => 'User/Mail/pass-token.html'
        ), $context);
    }

    /**
     * Generates token callback
     *
     * <ul>
     * <li> token: recover token</li>
     * <li> host: server host such as webpich.com</li>
     * <li> userId: Id of the user</li>
     * <li> userLogin: login of the user</li>
     * </ul>
     *
     * @param Pluf_HTTP_Request $request
     * @param User_PasswordToken $token
     * @return NULL|string
     */
    private function generateCallback($request, $token)
    {
        if (! array_key_exists('callback', $request->REQUEST)) {
            return null;
        }
        $calback = $request->REQUEST['callback'];
        
        $user = $token->get_user();
        $calback = str_replace("{{token}}", $token->token, $calback);
        $calback = str_replace("{{host}}", Pluf_Tenant::current()->domain, $calback);
        $calback = str_replace("{{userId}}", $user->id, $calback);
        $calback = str_replace("{{userLogin}}", $user->login, $calback);
        return $calback;
    }
}
