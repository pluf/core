<?php
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
        return new Pluf_HTTP_Response_Json($user);
    }
}
