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
     * فهرست تمام کاربران را نمایش می‌دهد
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function find ($request, $match)
    {
        $pag = new Pluf_Paginator(new Pluf_User());
        $pag->list_filters = array(
                'administrator',
                'staff',
                'active'
        );
        $list_display = array(
                'login' => __('User name'),
                'first_name' => __('First name'),
                'last_name' => __('Last name')
        );
        $search_fields = array(
                'login',
                'first_name',
                'last_name',
                'email'
        );
        $sort_fields = array(
                'id',
                'login',
                'first_name',
                'last_name',
                'date_joined',
                'last_login'
        );
        $pag->model_view = 'secure';
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->items_per_page = $this->getListCount($request);
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function get ($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }

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
     * ثبت کاربران
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function create ($request, $match)
    {
        // Create account
        $extra = array();
        $form = new User_Form_User(
                array_merge($request->REQUEST, $request->FILES), $extra);
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
}
