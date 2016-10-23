<?php
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
