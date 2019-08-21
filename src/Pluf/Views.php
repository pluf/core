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
 * Basic Pluf fices
 *
 * To start a module as fast as possible, you need a basic views such as model
 * CRUD and list. Here is a list of a basic views which are very common in your
 * desing.
 *
 * If you follow SEEN API guid line, the view is verry usefull in your
 * implementation.
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
    function redirectTo($request, $match, $url)
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
    function simpleContent($request, $match, $content)
    {
        return new Pluf_HTTP_Response($content);
    }

    /**
     * Creates a template and returns as result
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
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return Pluf_HTTP_Response
     */
    function loadTemplate($request, $match)
    {
        $template = $match[1];
        $extra_context = array();
        // create and show a template
        $context = new Pluf_Template_Context_Request($request, $extra_context);
        $tmpl = new Pluf_Template($template);
        return new Pluf_HTTP_Response($tmpl->render($context));
    }

    // TODO: maso, 2017: document
    private static function CRUD_getModelInstance($p)
    {
        $model = self::CRUD_getModel($p);
        return new $model();
    }

    // TODO: maso, 2017: document
    private static function CRUD_getModel($p)
    {
        if (! isset($p['model'])) {
            throw new Exception('The model class was not provided in the parameters.');
        }
        return $p['model'];
    }

    // TODO: maso, 2017: document
    private static function CRUD_getParentModel($p)
    {
        if (! isset($p['parent'])) {
            throw new Exception('The parent class was not provided in the parameters.');
        }
        return $p['parent'];
    }

    // TODO: maso, 2017: document
    private static function CRUD_response($request, $p, $object, $child = null)
    {
        /*
         * Return a template if is set
         */
        if (isset($p['template'])) {
            $context = (isset($p['extra_context'])) ? $p['extra_context'] : array();
            $template = $p['template'];
            return Pluf_Shortcuts_RenderToResponse($template, array_merge($context, array(
                'object' => $object
            )), $request);
        }
        return $object;
    }

    /**
     * Call determined preconditions in the $p and check if preconditions are statisfied.
     * Preconditions could be determined in the $p array as 'precond' feild.
     *
     * A precondition is a function which is called in some situations before some actions.
     * Here is an example to define preconditions in $p:
     *
     * $p = array(
     * 'precond' => array(
     * 'My_Precondition_Class::precondFunc',
     * 'My_Precondition_Class::precondFunc2'
     * )
     * );
     *
     * Value of 'precond' could be array or a single item.
     *
     * Each precondition function will be called with three argument respectively as following:
     * - $request: Pluf_HTTp_Request
     * - $object: Pluf_Model
     * - $parent: Pluf_Model, which is a parent of $object model
     *
     * @param Pluf_HTTP_Request $request
     * @param array $p
     * @param Pluf_Model $object
     * @param Pluf_Model $parent
     * @throws Pluf_Exception
     */
    private static function CRUD_checkPreconditions($request, $p, $object, $parent = null)
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
            $res = call_user_func_array(explode('::', $precond), array(
                $request,
                $object,
                $parent
            ));
            if ($res !== true) {
                throw new Pluf_Exception('CRUD precondition is not satisfied.');
            }
        }
    }

    /**
     * Checks if one to many relation exist between two entity
     *
     * @param Pluf_HTTP_Request $request
     * @param Pluf_Model $parent
     * @param Pluf_Model $object
     * @param array $p
     *            parameters
     * @throws Pluf_HTTP_Error404 if relation does not exist
     */
    private static function CRUD_assertManyToOneRelation($parent, $object, $p)
    {
        if (! isset($p['parentKey'])) {
            throw new Exception('The parentKey was not provided in the parameters.');
        }
        $key = $p['parentKey'];
        if ($object->__get($key) !== $parent->id) {
            throw new Pluf_HTTP_Error404('Invalid relation');
        }
    }

    /**
     * List objects (Part of the CRUD series).
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return Pluf_Paginator
     */
    public function findObject($request, $match, $p = array())
    {
        // Create page
        $builder = new Pluf_Paginator_Builder(self::CRUD_getModelInstance($p));
        if (array_key_exists('sql', $p)) {
            if ($p['sql'] instanceof Pluf_SQL) {
                $builder->setWhereClause($p['sql']);
            } else {
                $builder->setWhereClause(new Pluf_SQL($p['sql']));
            }
        }
        if (array_key_exists('listFilters', $p)) {
            $builder->setDisplayList($p['listFilters']);
        }
        if (array_key_exists('searchFields', $p)) {
            $builder->setSearchFields($p['searchFields']);
        }
        if (array_key_exists('sortFields', $p)) {
            $builder->setSortFields($p['sortFields']);
        }
        if (array_key_exists('view', $p)) {
            $builder->setView($p['view']);
        }
        $builder->setRequest($request);
        return $builder->build();
    }

    /**
     * Get list of Children
     *
     * It there is a relation ( Many to one), you can list all child with this
     * view. The relation must be implemented with forign key in child class.
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return Pluf_Paginator
     */
    public function findManyToOne($request, $match, $p)
    {
        if (array_key_exists('parentId', $request->REQUEST)) {
            $parentId = $request->REQUEST['parentId'];
        } else {
            $parentId = $match['parentId'];
        }
        $sql = new Pluf_SQL($p['parentKey'] . '=%s', $parentId);
        if (isset($p['sql'])) {
            $sqlMain = new Pluf_SQL($p['sql']);
            $sql = $sqlMain->SAnd($sql);
        }
        $p['sql'] = $sql;
        return $this->findObject($request, $match, $p);
    }

    /**
     * Clear collection list with the given query
     *
     * @param unknown $request
     * @param unknown $match
     * @param unknown $p
     */
    public function clearManyToOne($request, $match, $p){
        // XXX: clean list
        return null;
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
    public function getObject($request, $match, $p)
    {
        // Set the default
        $object = Pluf_Shortcuts_GetObjectOr404(self::CRUD_getModel($p), $match['modelId']);
        self::CRUD_checkPreconditions($request, $p, $object);
        return self::CRUD_response($request, $p, $object);
    }

    /**
     * Get children
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @param array $p
     * @return Pluf_Model
     */
    public function getManyToOne($request, $match, $p)
    {
        // Set the default
        if (array_key_exists('modelId', $request->REQUEST)) {
            $modelId = $request->REQUEST['modelId'];
        } else {
            $modelId = $match['modelId'];
        }
        $object = Pluf_Shortcuts_GetObjectOr404(self::CRUD_getModel($p), $modelId);
        if (array_key_exists('parentId', $request->REQUEST)) {
            $parentId = $request->REQUEST['parentId'];
        } else {
            $parentId = $match['parentId'];
        }
        $parent = Pluf_Shortcuts_GetObjectOr404(self::CRUD_getParentModel($p), $parentId);
        // TODO: maso, 2017: assert relation
        self::CRUD_assertManyToOneRelation($parent, $object, $p);
        self::CRUD_checkPreconditions($request, $p, $object, $parent);
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
    public function createObject($request, $match, $p)
    {
        $default = array(
            'extra_context' => array(),
            'extra_form' => array()
        );
        $p = array_merge($default, $p);
        // Set the default
        $model = self::CRUD_getModel($p);
        $object = $model instanceof Pluf_Model ? $model : new $model();
        // Read body of request
        // $entityBody = file_get_contents('php://input', 'r');
        // Check if body is a json array
        // Convert each item to an object model by using Form
        // Save models
        $form = Pluf_Shortcuts_GetFormForModel($object, $request->REQUEST, $p['extra_form']);
        $object = $form->save();

        return self::CRUD_response($request, $p, $object);
    }

    /**
     * Createy a many to one object
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @param array $p
     * @return Pluf_HTTP_Response
     */
    public function createManyToOne($request, $match, $p)
    {
        if (array_key_exists('parentId', $request->REQUEST)) {
            $parentId = $request->REQUEST['parentId'];
        } else {
            $parentId = $match['parentId'];
        }
        $parent = Pluf_Shortcuts_GetObjectOr404(self::CRUD_getParentModel($p), $parentId);

        $default = array(
            'extra_context' => array(),
            'extra_form' => array()
        );
        $p = array_merge($default, $p);
        // Set the default
        $model = self::CRUD_getModel($p);
        $object = $model instanceof Pluf_Model ? $model : new $model();
        $form = Pluf_Shortcuts_GetFormForModel($object, $request->REQUEST, $p['extra_form']);
        $object = $form->save(false);
        $object->{$p['parentKey']} = $parent;
        $object->create();

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
    public function updateObject($request, $match, $p)
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
        $form = Pluf_Shortcuts_GetFormForUpdateModel($object, $request->REQUEST, $p['extra_form']);
        $object = $form->save();
        return self::CRUD_response($request, $p, $object);
    }

    /**
     * Update many to one
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @param array $p
     * @return Pluf_HTTP_Response
     */
    public function updateManyToOne($request, $match, $p)
    {
        if (array_key_exists('parentId', $request->REQUEST)) {
            $parentId = $request->REQUEST['parentId'];
        } else {
            $parentId = $match['parentId'];
        }
        $parent = Pluf_Shortcuts_GetObjectOr404(self::CRUD_getParentModel($p), $parentId);

        $default = array(
            'extra_context' => array(),
            'extra_form' => array()
        );
        $p = array_merge($default, $p);
        // Set the default
        $model = self::CRUD_getModel($p);
        $object = Pluf_Shortcuts_GetObjectOr404($model, $match['modelId']);
        // TODO: maso, 2017: check relateion
        self::CRUD_assertManyToOneRelation($parent, $object, $p);
        self::CRUD_checkPreconditions($request, $p, $object, $parent);
        $form = Pluf_Shortcuts_GetFormForUpdateModel($object, $request->REQUEST, $p['extra_form']);
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
     * Other extra parameters may be as follow:
     *
     * 'permanently' - if is exist and its value is false the object will not be deleted permanently and
     * only the `deleted` field of that will be set to true. Note that this option assumes that the removing
     * object has a feild named `deleted`
     *
     * @param Pluf_HTTP_Request $request
     *            object
     * @param array $match
     * @param array $p
     *            parameters
     * @return Pluf_HTTP_Response Response object (can be a redirect)
     */
    public function deleteObject($request, $match, $p)
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
        if (array_key_exists('permanently', $p) && $p['permanently'] === false) {
            $object->deleted = true;
            $object->update();
            return $object;
        }
        $objectCopy = Pluf_Shortcuts_GetObjectOr404($model, $match['modelId']);
        $objectCopy->id = 0;
        $object->delete();
        return self::CRUD_response($request, $p, $objectCopy);
    }

    /**
     * Delete many to one
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @param array $p
     * @return Pluf_HTTP_Response
     */
    public function deleteManyToOne($request, $match, $p)
    {
        if (array_key_exists('parentId', $request->REQUEST)) {
            $parentId = $request->REQUEST['parentId'];
        } else {
            $parentId = $match['parentId'];
        }
        $parent = Pluf_Shortcuts_GetObjectOr404(self::CRUD_getParentModel($p), $parentId);

        $default = array(
            'extra_context' => array(),
            'extra_form' => array()
        );
        $p = array_merge($default, $p);
        // Set the default
        $model = self::CRUD_getModel($p);
        $object = Pluf_Shortcuts_GetObjectOr404($model, $match['modelId']);
        // TODO: maso, 2017: check relateion
        self::CRUD_assertManyToOneRelation($parent, $object, $p);
        $objectCopy = Pluf_Shortcuts_GetObjectOr404($model, $match['modelId']);
        $objectCopy->id = 0;
        self::CRUD_checkPreconditions($request, $p, $object, $parent);
        $object->delete();
        return self::CRUD_response($request, $p, $objectCopy);
    }

    public function getSchema($request, $match, $p)
    {
        $model = self::CRUD_getModel($p);
        $obj = new $model();
        return $obj->getSchema();
    }

}


