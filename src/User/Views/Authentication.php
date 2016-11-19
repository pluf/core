<?php
Pluf::loadFunction('User_Shortcuts_UserJsonResponse');

/**
 * Provide authentication functionality for users.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 * @author hadi<mohammad.hadi.mansouri@dpq.co.ir>
 *        
 */
class User_Views_Authentication
{

    /**
     * Logs in user.
     * As a result, returns information of loged in user as JSON information (if login was successful).
     */
    public static function login($request, $match)
    {
        if (! $request->user->isAnonymous()) {
            return User_Shortcuts_UserJsonResponse($request->user);
        }
        
        $backends = Pluf::f('auth_backends', array(
            'Pluf_Auth_ModelBackend'
        ));
        foreach ($backends as $backend) {
            $user = call_user_func(array(
                $backend,
                'authenticate'
            ), $request->POST);
            if ($user !== false) {
                break;
            }
        }
        
        if (false === $user) {
            throw new Pluf_Exception(__('user authentication incorrect'));
        }
        
        $request->user = $user;
        $request->session->clear();
        $request->session->setData('login_time', gmdate('Y-m-d H:i:s'));
        $user->last_login = gmdate('Y-m-d H:i:s');
        $user->update();

        return new Pluf_HTTP_Response_Json($user);
    }

    /**
     * Logs out user.
     */
    public static function logout($request, $match)
    {
        $user_model = Pluf::f('pluf_custom_user', 'Pluf_User');
        $request->user = new $user_model();
        $request->session->clear();
        $request->session->setData('logout_time', gmdate('Y-m-d H:i:s'));
        return new Pluf_HTTP_Response_Json(new Pluf_User());
    }
}
