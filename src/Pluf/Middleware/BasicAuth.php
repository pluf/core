<?php

/**
 * Basic Auth middleware
 *
 * Allow basic_auth for REST API.
 * 
 */
class Pluf_Middleware_BasicAuth
{

    /**
     * Process the request.
     *
     *
     * @param
     *            Pluf_HTTP_Request The request
     * @return bool false
     */
    function process_request (&$request)
    {
        if(isset($request->user) && !$request->user->isAnonymous()){
            return false;
        }
        if (! isset($request->SERVER['PHP_AUTH_USER'])) {
            return false;
        }
        
        // $user_model = Pluf::f('pluf_custom_user', 'Pluf_User');
        $auth = array(
                'login' => $request->SERVER['PHP_AUTH_USER'],
                'password' => $request->SERVER['PHP_AUTH_PW']
        );
        foreach (Pluf::f('auth_backends', 
                array(
                        'Pluf_Auth_ModelBackend'
                )) as $backend) {
            $user = call_user_func(
                    array(
                            $backend,
                            'authenticate'
                    ), $auth);
            if ($user !== false) {
                break;
            }
        }
        if($user){
            $request->user = $user;
        }
        return false;
    }

}