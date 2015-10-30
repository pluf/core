<?php

/**
 * مدل داده‌ای کاربر را ایجاد می‌کند.
 * 
 * @param unknown $object
 * @return Pluf_User
 */
function User_Shortcuts_UserDateFactory ($object)
{
    $user_model = Pluf::f('pluf_custom_user', 'Pluf_User');
    // $group_model = Pluf::f ( 'pluf_custom_group', 'Pluf_Group' );
    if ($object == null || ! isset($object))
        return new $user_model();
    return $object;
}

/**
 *
 * @param unknown $object            
 * @return unknown
 */
function User_Shortcuts_UserProfileDateFactory ($object)
{
    $user_model = Pluf::f('user_profile_class', 'User_Profile');
    if ($object == null || ! isset($object))
        return new $user_model();
    return $object;
}

/**
 * داده‌های کاربر را با در نظر گرفتن امنیت ارسال می‌کند.
 *
 * @param unknown $object            
 * @return unknown
 */
function User_Shortcuts_UserJsonResponse ($user)
{
    $user->password = '****';
    return new Pluf_HTTP_Response_Json($user);
}

/**
 * اطلاعات امنیتی کاربران را حذف می‌کند.
 *
 * @param unknown $user            
 */
function User_Shortcuts_RemoveSecureData (&$user)
{
    $user->email = null;
    $user->password = null;
    $user->administrator = null;
    $user->staff = null;
    $user->active = null;
    $user->language = null;
    $user->timezone = null;
    $user->date_joined = null;
    $user->last_login = null;
}

/**
 * سطح کاربر را ارتقا می‌دهد.
 *
 * نوع عمل عمل انجام شده می‌توان سطح کاربر را افزایش و یا کاهش داد. این
 * فراخوانی
 * امکان افزایش سطح کاربر را تعیین می‌کند.
 *
 * برای این کار باید یک عمل به عنوان عمل انجام شده تعیین شود. هر عمل به صورت
 * یک
 * متغیر در سیستم در نظر گرفته می‌شود، و در صورتی که یک درجه برای عمل تعیین
 * شده
 * باشد به اندازه همان درجه به کاربر اضافه یا شاید کم می‌شود.
 *
 * پارامتر بعد تعیین کاهش و یا افزایش است که با یک مقدار درستی و یا نا درستی
 * تعیین
 * می‌شود.
 *
 * @param unknown $user            
 * @param unknown $action            
 * @param string $decres            
 */
function User_Shortcuts_UpdateLeveFor ($user, $action, $decrease = true)
{
    try {
        $values = Pluf::f('user_profile_level_values', array());
        if (! array_key_exists($action, $values)) {
            return;
        }
        $value = $values[$action];
        if ($value == 0) {
            return;
        }
        $profile = $user->getProfile();
        if ($decrease) {
            $profile->level += $value;
        } else {
            $profile->level -= $value;
        }
        $profile->update();
    } catch (Exception $ex) {
        // $profile = new $profile_model();
        // $profile->user = $request->user;
        // $profile->create();
    }
}