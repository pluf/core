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
class User_Views_User extends Pluf_Views
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
        $form = new User_Form_User(array_merge($request->POST, $request->FILES), 
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
     * پیش نیازهای دسترسی به فهرست کاربران
     *
     * @var unknown
     */
    public $users_precond = array(
            'Pluf_Precondition::staffRequired'
    );

    /**
     * فهرست تمام کاربران را نمایش می‌دهد
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function users ($request, $match)
    {
        $pag = new Pluf_Paginator(new Pluf_User());
        $pag->list_filters = array(
                'id',
                'login'
        );
        $list_display = array(
                'login' => __('User name')
        );
        $search_fields = array(
                'id',
                'login',
        );
        $sort_fields = array(
                'id',
                'login',
        );
        $pag->model_view = 'list';
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->items_per_page = 10;
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * تمام داده‌های امنیتی را از فهرست انتخاب حذف می‌کند.
     */
    function getUserListSelect ()
    {
        if (isset($this->_cache['getSelect']))
            return $this->_cache['getSelect'];
        $select = array();
        $table = $this->getSqlTable();
        foreach ($this->_a['cols'] as $col => $val) {
            if ($val['type'] != 'Pluf_DB_Field_Manytomany') {
                $select[] = $table . '.' . $this->_con->qn($col) . ' AS ' .
                        $this->_con->qn($col);
            }
        }
        $this->_cache['getSelect'] = implode(', ', $select);
        return $this->_cache['getSelect'];
    }
    
    /**
     * پیش نیازهای فهرست کردن کاربران
     *
     * @var unknown
     */
    public $user_precond = array(
            'Pluf_Precondition::staffRequired'
    );

    /**
     * مدیریت یک کاربر را در سیستم ایجاد می‌کند
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function user ($request, $match)
    {
        $user_id = $match[1];
        if ($user_id === $request->user->id) {
            return $this->account($request, $match);
        }
        throw new Pluf_Exception_NotImplemented();
    }
}
