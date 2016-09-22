<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * لایه نمایش مدیریت کاربران را به صورت پیش فرض ایجاد می‌کند
 *
 * @author maso
 *        
 */
class User_Views_Profile
{

    /**
     * پروفایل کاربری را در اختیار قرار می‌دهد
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function get ($request, $match)
    {
        $profile_model = Pluf::f('user_profile_class', false);
        if ($profile_model === false) {
            throw new Pluf_Exception(__('Profile model is not configured.'));
        }
        try {
            $profile = $request->user->getProfile();
        } catch (Pluf_Exception_DoesNotExist $ex) {
            $profile = new $profile_model();
            $profile->user = $request->user;
            $profile->create();
        }
        return new Pluf_HTTP_Response_Json($profile);
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception
     * @return Pluf_HTTP_Response_Json
     */
    public function update ($request, $match)
    {
        $profile_model = Pluf::f('user_profile_class', false);
        if ($profile_model === false) {
            throw new Pluf_Exception(__('Profile model is not configured.'));
        }
        try {
            $profile = $request->user->getProfile();
        } catch (Pluf_Exception_DoesNotExist $ex) {
            $profile = new $profile_model();
            $profile->user = $request->user;
            $profile->create();
        }
        
        $profile_form = Pluf::f('user_profile_form', false);
        if ($profile_form === false) {
            throw new Pluf_Exception(__('Profile form is not configured.'));
        }
        $form = new $profile_form(array_merge($request->POST, $request->FILES), 
                array(
                        'user_profile' => $profile
                ));
        $profile = $form->update();
        return new Pluf_HTTP_Response_Json($profile);
    }
}
