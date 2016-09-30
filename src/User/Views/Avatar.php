<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

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
        // get avatar
        $avatar = Pluf::factory('User_Avatar')->getOne('user=' . $request->user->id);
        if ($avatar) {
            return new Pluf_HTTP_Response_File($avatar->getAbsloutPath(), $avatar->mimeType);
        }
        // default avatar
        $file = Pluf::f('user_avatar_default');
        return new Pluf_HTTP_Response_File($file, SaaS_FileUtil::getMimeType($file));
    }

    /**
     * Updates avatar image of user.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function update($request, $match)
    {
        $avatar = Pluf::factory('User_Avatar')->getOne('user=' . $request->user->id);
        if ($avatar) {
            $form = new User_Form_Avatar(array_merge($request->REQUEST, $request->FILES), array(
                'model' => $avatar,
                'user' => $request->user
            ));
        } else {
            $form = new User_Form_Avatar(array_merge($request->REQUEST, $request->FILES), array(
                'model' => new User_Avatar(),
                'user' => $request->user
            ));
        }
        $model = $form->save();
        return new Pluf_HTTP_Response_Json($model);
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
        $avatar = Pluf::factory('User_Avatar')->getOne('user=' . $request->user->id);
        if ($avatar) {
            $avatar->delete();
        }
        return new Pluf_HTTP_Response_Json($avatar);
    }
}
