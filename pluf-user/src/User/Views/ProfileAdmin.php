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
class User_Views_ProfileAdmin extends User_Views_Profile
{

    /**
     * پیش نیازهای دسترسی به پرفایل کاربران
     *
     * @var unknown
     */
    public $getProfile_precond = array(
            'Pluf_Precondition::loginRequired'
    );

    /**
     * پروفایل کاربری را در اختیار قرار می‌دهد
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function getProfile ($request, $match)
    {
        $user_id = $match[1];
        if ($user_id == $request->user->id) {
            return $this->get($request, $match);
        }
        throw new Pluf_Exception_NotImplemented();
    }

    /**
     * پیش نیازهای دسترسی به پرفایل کاربران
     *
     * @var unknown
     */
    public $updateProfile_precond = array(
            'Pluf_Precondition::loginRequired'
    );

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception
     * @return Pluf_HTTP_Response_Json
     */
    public function updateProfile ($request, $match)
    {
        $user_id = $match[1];
        if ($user_id == $request->user->id) {
            return $this->update($request, $match);
        }
        throw new Pluf_Exception_NotImplemented();
    }
}
