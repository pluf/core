<?php

/**
 * مدل داده‌ای کاربر را ایجاد می‌کند.
 * 
 * @param unknown $object
 * @return Pluf_User
 */
function User_Shortcuts_UserDateFactory($object)
{
    $user_model = Pluf::f('pluf_custom_user', 'Pluf_User');
    // $group_model = Pluf::f ( 'pluf_custom_group', 'Pluf_Group' );
    if ($object == null || ! isset($object))
        return new $user_model();
    return $object;
}

/**
 * بررسی حالت پسورد جدید
 * 
 * @param String $pass
 * @throws Pluf_Exception
 * @return String
 */
function User_Shortcuts_CheckPassword($pass)
{
    if ($pass == null || ! isset($pass))
        throw new Pluf_Exception("Pasword must not be null");
    return $pass;
}

/**
 *
 * @param unknown $object            
 * @return unknown
 */
function User_Shortcuts_UserProfileDateFactory($object)
{
    $user_model = Pluf::f('user_profile_class', 'User_Profile');
    if ($object == null || ! isset($object))
        return new $user_model();
    return $object;
}


/**
 * اطلاعات امنیتی کاربران را حذف می‌کند.
 *
 * @deprecated این تابع حذف خواهد شد.
 * @param unknown $user            
 */
function User_Shortcuts_RemoveSecureData(&$user)
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
function User_Shortcuts_UpdateLeveFor($user, $action, $decrease = true)
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

/**
 * Updates or creates profile for given user by using given data.
 *
 * @param unknown $user            
 * @param array $data            
 * @throws Pluf_Exception
 * @return unknown
 */
function User_Shortcuts_UpdateProfile($user, $data = array())
{
    $profileModel = Pluf::f('user_profile_class', false);
    if ($profileModel === false) {
        throw new Pluf_Exception(__('Profile model is not configured.'));
    }
    $profile = Pluf::factory($profileModel)->getOne('user=' . $user->getId());
    if ($profile == null) {
        $profile = Pluf::factory($profileModel);
        $profile->__set('user', $user);
        $profile->create();
    }
    $form = Pluf_Shortcuts_GetFormForModel($profile, $data, array());
    $sf = $form->save();
    return new Pluf_HTTP_Response_Json($sf);
}

/**
 * Returns information of profile of given user
 *
 * @param unknown $user            
 * @throws Pluf_Exception
 * @return Pluf_HTTP_Response_Json
 */
function User_Shortcuts_GetProfile($user)
{
    // TODO: hadi, 1395: use appropriate setting name
    $profileModel = Pluf::f('user_profile_class', false);
    if ($profileModel === false) {
        throw new Pluf_Exception(__('Profile model is not configured.'));
    }
    $profile = Pluf::factory($profileModel)->getOne('user=' . $user->getId());
    if ($profile == null) {
        // throw new Pluf_Exception('User has no profile yet!');
        return new Pluf_HTTP_Response_Json(Pluf::factory($profileModel));
    }
    // TODO: hadi, 1395: we should hide secure information of profile.
    return new Pluf_HTTP_Response_Json($profile);
}

/**
 * Deletes avatar of given user.
 *
 * @param unknown $user            
 * @return Pluf_HTTP_Response_Json
 */
function User_Shortcuts_DeleteAvatar($user)
{
    $avatar = Pluf::factory('User_Avatar')->getOne('user=' . $user->id);
    if ($avatar) {
        $avatar->delete();
    }
    return new Pluf_HTTP_Response_Json($avatar);
}

/**
 * Returns avatar of given user if is existed.
 *
 * @param unknown $user            
 */
function User_Shortcuts_GetAvatar($user)
{
    // get avatar
    $avatar = Pluf::factory('User_Avatar')->getOne('user=' . $user->id);
    if ($avatar) {
        return new Pluf_HTTP_Response_File($avatar->getAbsloutPath(), $avatar->mimeType);
    }
    // default avatar
    $file = Pluf::f('user_avatar_default');
    return new Pluf_HTTP_Response_File($file, Pluf_FileUtil::getMimeType($file));
}

/**
 * Sets (updates or creates) avatar for given user
 * @param unknown $user
 * @param array $data
 * @return Pluf_HTTP_Response_Json
 */
function User_Shortcuts_UpdateAvatar($user, $data = array())
{
    $avatar = Pluf::factory('User_Avatar')->getOne('user=' . $user->id);
    if ($avatar) {
        $form = new User_Form_Avatar($data, array(
            'model' => $avatar,
            'user' => $user
        ));
    } else {
        $form = new User_Form_Avatar($data, array(
            'model' => new User_Avatar(),
            'user' => $user
        ));
    }
    $model = $form->save();
    return new Pluf_HTTP_Response_Json($model);
}