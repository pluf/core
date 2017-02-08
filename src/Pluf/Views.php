<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_RenderToResponse');

// XXX: maso, 1394: بر اساس ساختارهای REST باید این لایه نمایش نیز به روز شود.

/**
 * دسته‌ای از فراخوانی‌های پایه در لایه نمایش
 *
 * در بسیاری از کاربردها همواره کارهای ثابتی در لایه نمایش مورد نیاز است. تمام
 * این کارها در اینجا پیاده سازی شده است تا هزینه پیاده سازی لایه نمایش کاهش
 * یابد.
 *
 * برای نمونه در برخی شرایط نیاز است که مسیر کاربر را به یک مسیر جدید تغییر داد
 * در اینجا یک پیاده سازی ساده برای این کار انجام شده است.
 */
class Pluf_Views
{

    /**
     * Simple redirection view.
     *
     * @param
     *            Request Request object
     * @param
     *            array Match
     * @param
     *            string Redirection URL (not a view)
     */
    function redirectTo ($request, $match, $url)
    {
        return new Pluf_HTTP_Response_Redirect($url);
    }

    /**
     * Simple content view.
     *
     * @param
     *            Request Request object
     * @param
     *            array Match
     * @param
     *            string Content of the page
     */
    function simpleContent ($request, $match, $content)
    {
        return new Pluf_HTTP_Response($content);
    }

    /**
     * Log the user in.
     *
     * The login form is provided by the login_form.html template.
     * The '_redirect_after' hidden value is used to redirect the user
     * after successfull login. If the view is called with
     * _redirect_after set in the query as a GET variable it will be
     * available as $_redirect_after in the template.
     *
     * @param
     *            Request Request object
     * @param
     *            array Match
     * @param
     *            string Default redirect URL after login ('/')
     * @param
     *            array Extra context values (array()).
     * @param
     *            string Login form template ('login_form.html')
     * @return Response object
     */
    function login ($request, $match, $success_url = '/', $extra_context = array(), 
            $template = 'login_form.html')
    {
        if (! empty($request->REQUEST['_redirect_after'])) {
            $success_url = $request->REQUEST['_redirect_after'];
        }
        $error = '';
        if ($request->method == 'POST') {
            foreach (Pluf::f('auth_backends', 
                    array(
                            'Pluf_Auth_ModelBackend'
                    )) as $backend) {
                $user = call_user_func(
                        array(
                                $backend,
                                'authenticate'
                        ), $request->POST);
                if ($user !== false) {
                    break;
                }
            }
            if (false === $user) {
                $error = __(
                        'The login or the password is not valid. The login and the password are case sensitive.');
            } else {
                if (! $request->session->getTestCookie()) {
                    $error = __(
                            'You need to enable the cookies in your browser to access this website.');
                } else {
                    $request->user = $user;
                    $request->session->clear();
                    $request->session->setData('login_time', 
                            gmdate('Y-m-d H:i:s'));
                    $user->last_login = gmdate('Y-m-d H:i:s');
                    $user->update();
                    $request->session->deleteTestCookie();
                    return new Pluf_HTTP_Response_Redirect($success_url);
                }
            }
        }
        // Show the login form
        $request->session->createTestCookie();
        $context = new Pluf_Template_Context_Request($request, 
                array_merge(
                        array(
                                'page_title' => __('Sign In'),
                                '_redirect_after' => $success_url,
                                'error' => $error
                        ), $extra_context));
        $tmpl = new Pluf_Template($template);
        return new Pluf_HTTP_Response($tmpl->render($context));
    }

    /**
     * Logout the user.
     *
     * The success url is either an absolute url starting with
     * http(s):// or considered as an action.
     *
     * @param
     *            Request Request object
     * @param
     *            array Match
     * @param
     *            string Default redirect URL after login '/'
     * @return Response object
     */
    function logout ($request, $match, $success_url = '/')
    {
        $user_model = Pluf::f('pluf_custom_user', 'Pluf_User');
        $request->user = new $user_model();
        $request->session->clear();
        $request->session->setData('logout_time', gmdate('Y-m-d H:i:s'));
        if (0 !== strpos($success_url, 'http')) {
            $murl = new Pluf_HTTP_URL();
            $success_url = Pluf::f('app_base') . $murl->generate($success_url);
        }
        return new Pluf_HTTP_Response_Redirect($success_url);
    }

    /**
     * یکی از الگوها را ایجاد و آن را به عنوان نتیجه برمی‌گرداند
     *
     * در بسیاری از کاربردها نرم‌افزار کاربردی به صفحه‌های متفاوتی شکسته می‌شود
     * و بر اساس
     * حالت کاربر یکی از صفحه‌ها نمایش داده می‌شود. به این ترتیب حجم دانلود برای
     * هر صفحه
     * کم شده و توسعه هر صفحه نیز راحتر می‌شود.
     *
     * این فراخوانی این امکان را ایجاد می‌کند که در لایه نمایش به سادگی یکی از
     * الگوها را
     * فراخوانی کرده و آن را به عنوان نتیجه برای کاربران نمایش دهید.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response
     */
    function loadTemplate ($request, $match)
    {
        $template = $match[1];
        $extra_context = array();
        // create and show a template
        $context = new Pluf_Template_Context_Request($request, $extra_context);
        $tmpl = new Pluf_Template($template);
        return new Pluf_HTTP_Response($tmpl->render($context));
    }

    private static function CRUD_getModelInstance ($p)
    {
        $model = self::CRUD_getModel($p);
        return new $model();
    }

    private static function CRUD_getModel ($p)
    {
        if (! isset($p['model'])) {
            throw new Exception(
                    'The model class was not provided in the parameters.');
        }
        return $p['model'];
    }
    
    private static function CRUD_response ($request, $p, $object, 
            $child = null)
    {
        /*
         * Return a template if is set
         */
        if (isset($p['template'])) {
            $context = (isset($p['extra_context'])) ? $p['extra_context'] : array();
            $template = (isset($p['template'])) ? $p['template'] : strtolower(
                    $model) . '_confirm_delete.html';
                    $post_delete_keys = (isset($p['post_delete_redirect_keys'])) ? $p['post_delete_redirect_keys'] : array();
                    return Pluf_Shortcuts_RenderToResponse($template,
                            array_merge($context,
                                    array(
                                            'object' => $object
                                    )), $request);
        }
        return new Pluf_HTTP_Response_Json($object);
    }

    private static function CRUD_checkPreconditions ($request, $p, $object, 
            $child = null)
    {
        if (! isset($p['precond'])) {
            return;
        }
        $preconds = $p['precond'];
        if (! is_array($preconds)) {
            $preconds = array(
                    $preconds
            );
        }
        foreach ($preconds as $precond) {
            $res = call_user_func_array(explode('::', $precond[0]), 
                    array(
                            $request,
                            $object,
                            $child
                    ));
            if ($res !== true) {
                throw new Pluf_Exception('CRUD precondition is not satisfied.');
            }
        }
    }

    /**
     * List objects (Part of the CRUD series).
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     * @return Pluf_HTTP_Response_Json
     */
    public static function findObject ($request, $match, $p)
    {
        $default = array(
                'listFilters' => array(),
                'listDisplay' => array(),
                'searchFields' => array(),
                'sortFields' => array()
        );
        $p = array_merge($default, $p);
        // Create page
        $page = new Pluf_Paginator(self::CRUD_getModelInstance($p));
        if (isset($p['sql'])) {
            $page->forced_where = $p['sql'];
        }
        $page->list_filters = $p['listFilters'];
        $page->configure($p['listDisplay'], $p['searchFields'], 
                $p['sortFields']);
        $page->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($page->render_object());
    }

    /**
     * Access an object (Part of the CRUD series).
     *
     * کمترین پارامترهای اضافه که باید تعیین شود عبارتند از
     *
     * 'model' - Class name string, required.
     *
     * در پارامترهای مسیر هم باید پارامترهای زیر باشد
     *
     * 'modelIdd' - Id of of the current model to update
     *
     * @param
     *            Pluf_HTTP_Request Request object
     * @param
     *            array Match
     * @param
     *            array Extra parameters
     * @return Pluf_HTTP_Response Response object (can be a redirect)
     */
    public static function getObject ($request, $match, $p)
    {
        // Set the default
        $object = Pluf_Shortcuts_GetObjectOr404(self::CRUD_getModel($p), 
                $match['modelId']);
        self::CRUD_checkPreconditions($request, $p, $object);
        return self::CRUD_response($request, $p, $object);
    }

    /**
     * Create an object (Part of the CRUD series).
     *
     * The minimal extra parameter is the model class name. The list
     * of extra parameters is:
     *
     * 'model' - Class name string, required.
     *
     * 'extra_context' - Array of key/values to be added to the
     * context (array())
     *
     * 'extra_form' - Array of key/values to be added to the
     * form generation (array())
     *
     * 'template' - Template to use ('"model class"_create_form.html')
     *
     * @param
     *            Pluf_HTTP_Request Request object
     * @param
     *            array Match
     * @param
     *            array Extra parameters
     * @return Pluf_HTTP_Response Response object (can be a redirect)
     */
    public function createObject ($request, $match, $p)
    {
        $default = array(
                'extra_context' => array(),
                'extra_form' => array()
        );
        $p = array_merge($default, $p);
        // Set the default
        $model = self::CRUD_getModel($p);
        $object = new $model();
        $form = Pluf_Shortcuts_GetFormForModel($object, $request->REQUEST, 
                $p['extra_form']);
        $object = $form->save();

        return self::CRUD_response($request, $p, $object);
    }

    /**
     * Update an object (Part of the CRUD series).
     *
     * The minimal extra parameter is the model class name. The list
     * of extra parameters is:
     *
     * 'model' - Class name string, required.
     *
     * 'model_id' - Id of of the current model to update
     *
     * 'extra_context' - Array of key/values to be added to the
     * context (array())
     *
     * 'extra_form' - Array of key/values to be added to the
     * form generation (array())
     *
     * 'template' - Template to use ('"model class"_update_form.html')
     *
     * @param Pluf_HTTP_Request $request
     *            object
     * @param array $match            
     * @param array $p
     *            parameters
     * @return Pluf_HTTP_Response Response object (can be a redirect)
     */
    public function updateObject ($request, $match, $p)
    {
        $default = array(
                'extra_context' => array(),
                'extra_form' => array()
        );
        $p = array_merge($default, $p);
        // Set the default
        $model = self::CRUD_getModel($p);
        $object = Pluf_Shortcuts_GetObjectOr404($model, $match['modelId']);
        self::CRUD_checkPreconditions($request, $p, $object);
        $form = Pluf_Shortcuts_GetFormForModel($object, $request->REQUEST, 
                $p['extra_form']);
        $object = $form->save();
        return self::CRUD_response($request, $p, $object);
    }

    /**
     * Delete an object (Part of the CRUD series).
     *
     * The minimal extra parameter is the model class name. The list
     * of extra parameters is:
     *
     * 'model' - Class name string, required.
     *
     * 'post_delete_redirect' - View to redirect after saving, required.
     *
     * 'id' - Index in the match to fin the id of the object to delete (1)
     *
     * 'login_required' - Do we require login (false)
     *
     * 'template' - Template to use ('"model class"_confirm_delete.html')
     *
     * 'post_delete_redirect_keys' - Which keys of the model to pass to
     * the view (array())
     *
     * 'extra_context' - Array of key/values to be added to the
     * context (array())
     *
     * @param Pluf_HTTP_Request $request
     *            object
     * @param array $match            
     * @param array $p
     *            parameters
     * @return Pluf_HTTP_Response Response object (can be a redirect)
     */
    public function deleteObject ($request, $match, $p)
    {
        $default = array(
                'extra_context' => array(),
                'extra_form' => array()
        );
        $p = array_merge($default, $p);
        // Set the default
        $model = self::CRUD_getModel($p);
        $object = Pluf_Shortcuts_GetObjectOr404($model, $match['modelId']);
        self::CRUD_checkPreconditions($request, $p, $object);
        $object->delete();
        return self::CRUD_response($request, $p, $object);
    }
}