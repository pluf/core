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
class Inbox_Views_System
{

    /**
     * پیش نیازهای حساب کاربری
     *
     * @var unknown
     */
    public $messages_precond = array(
            'Pluf_Precondition::loginRequired'
    );

    /**
     * به روز رسانی و مدیریت اطلاعات خود کاربر
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function messages ($request, $match)
    {
        return new Pluf_HTTP_Response_Json(
                $request->user->getAndDeleteMessages());
    }
}
