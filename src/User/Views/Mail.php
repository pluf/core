<?php
Pluf::loadFunction('User_Shortcuts_UserJsonResponse');

/**
 * لایه نمایش احراز اصالت را ایجاد می‌کند
 *
 * @date 1394 یک پیاده سازی اولیه از این کلاس ارائه شده است که در آن دو واسط
 * RESR برای ورود و خروج در نظر گرفته شده است.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class User_Views_Authentication
{

    /**
     * با استفاده از این فراخوانی ایمیل کاربر تغییر می‌کند.
     *
     * زمانی که کاربر ایمیل خود را تغییر دهد یک پیام برای ایمیل ارسال می‌شود در
     * صورتی که ایمیل درست باشد، ایمیل کاربر تغییر می‌کند.
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
