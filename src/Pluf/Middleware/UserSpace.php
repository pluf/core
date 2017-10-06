<?php

/**
 * میان افزار اطلاعات فضای کاربر
 *
 * 
 * Allow to manage some user specific data and settings.
 * 
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 */
class Pluf_Middleware_UserSpace
{

    /**
     * Process the request.
     *
     * When processing the request, if user is found related user-space
     * will be fetch and loaded into $request->user_space.
     *
     * @param
     *            Pluf_HTTP_Request The request
     * @return bool false
     */
    function process_request(&$request)
    {
        if (! isset($request->user) || $request->user->isAnonymous()) {
            // user is not set so we set an empty fake UserSpace
            $request->user_space = new User_Space();
        } else {
            $userId = $request->user->getId();
            $userSpace = Pluf::factory('User_Space')->getOne('user=' . $userId);
            if ($userSpace == null) {
                $userSpace = Pluf::factory('User_Space');
                $userSpace->__set('user', $request->user);
            }
            $request->user_space = $userSpace;
        }
        return false;
    }

    /**
     * Process the response of a view.
     *
     * If the user_space has been modified save it into the database.
     *
     * @param
     *            Pluf_HTTP_Request The request
     * @param
     *            Pluf_HTTP_Response The response
     * @return Pluf_HTTP_Response The response
     */
    function process_response($request, $response)
    {
        if (! isset($request->user) || $request->user->isAnonymous()) {
            return $response;
        }
        if ($request->user_space->touched) {
            if ($request->user_space->id > 0) {
                $request->user_space->update();
            } else {
                $request->user_space->create();
            }
        }
        return $response;
    }
}
