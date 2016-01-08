<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');
Pluf::loadFunction('User_Shortcuts_UserJsonResponse');

/**
 * لایه نمایش مدیریت کاربران را به صورت پیش فرض ایجاد می‌کند
 *
 * @author maso
 *        
 */
class User_Views_User
{

    /**
     * پیش نیازهای حساب کاربری
     *
     * @var unknown
     */
    public $account_precond = array();

    /**
     * به روز رسانی و مدیریت اطلاعات خود کاربر
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function account ($request, $match)
    {
        return User_Shortcuts_UserJsonResponse($request->user);
    }

    /**
     * پیش نیازهای حساب کاربری
     *
     * @var unknown
     */
    public $update_precond = array(
            'Pluf_Precondition::loginRequired'
    );

    /**
     * اطلاعات حساب کاربری را به روز می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return unknown
     */
    public function update ($request, $match)
    {
        // initial page data
        $extra = array(
                'user' => $request->user
        );
        $form = new User_Form_Account(
                array_merge($request->POST, $request->FILES), $extra);
        $cuser = $form->update();
        $request->user->setMessage(
                sprintf(__('Account data has been updated.'), (string) $cuser));
        
        // Return response
        return User_Shortcuts_UserJsonResponse($cuser);
    }

    /**
     * پیش نیازهای ثبت کاربران
     *
     * @var unknown
     */
    public $signup_precond = array();

    /**
     * ثبت کاربران
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function signup ($request, $match)
    {
        // Create account
        $extra = array();
        $form = new User_Form_User(array_merge($request->REQUEST, $request->FILES), 
                $extra);
        $cuser = $form->save();
        
        // Create profile
        $profile_model = Pluf::f('user_profile_class', false);
        $profile_form = Pluf::f('user_profile_form', false);
        if ($profile_form === false || $profile_model === false) {
            return User_Shortcuts_UserJsonResponse($cuser);
        }
        try {
            $profile = $cuser->getProfile();
        } catch (Pluf_Exception_DoesNotExist $ex) {
            $profile = new $profile_model();
            $profile->user = $cuser;
            $profile->create();
        }
        $form = new $profile_form(array_merge($request->POST, $request->FILES), 
                array(
                        'user_profile' => $profile
                ));
        $profile = $form->update();
        
        // Return response
        return User_Shortcuts_UserJsonResponse($cuser);
    }

    /**
     * پیش نیازهای فراخوانی تغییر ایمیل
     *
     * @var unknown
     */
    public $changeEmail_precond = array(
            'Pluf_Precondition::loginRequired'
    );

    /**
     * با استفاده از این فراخوانی ایمیل کاربر تغییر می‌کند.
     *
     * زمانی که کاربر ایمیل خود را تغییر دهد یک پیام برای ایمیل ارسال می‌شود در
     * صورتی
     * که ایمیل درست باشد، ایمیل کاربر تغییر می‌کند.
     */
    public function changeEmail ($request, $match)
    {
        $key = $match[1];
        list ($email, $id, $time) = User_Form_UserChangeEmail::validateKey($key);
        if ($id != $request->user->id) {
            throw new Pluf_Exception('user not match');
        }
        // Now we have a change link coming from the right user.
        if ($request->user->email == $email) {
            return User_Shortcuts_UserJsonResponse($request->user);
        }
        
        $request->user->email = $email;
        $request->user->update();
        $request->user->setMessage(
                sprintf(
                        __(
                                'Your new email address "%s" has been validated. Thank you!'), 
                        Pluf_esc($email)));
        User_Shortcuts_UpdateLeveFor($request->user, 'user_email_registerd');
        // Return response
        return User_Shortcuts_UserJsonResponse($request->user);
    }
}
