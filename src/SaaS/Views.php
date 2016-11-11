<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * @ingroup views
 * @brief این کلاس نمایش‌های اصلی سیستم را ایجاد می‌کند.
 *
 *
 *
 * @date 1394
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 */
class SaaS_Views extends Pluf_Views
{

    /**
     * جستجوی مدل داده‌ای
     *
     * توی بسیاری از لایه‌های نمایش لازم هست که همه موجودیت‌ها به شکل ساده جسجو
     * بشن این فراخوانی برای این نوع جستجوها در نظر گرفته شده.
     *
     * @param unknown $request
     * @param unknown $match
     * @return Pluf_HTTP_Response_Json
     */
    public static function findObject ($request, $match, $p)
    {
        if (! isset($p['model'])) {
            throw new Pluf_Exception(
                    'The model class was not provided in the parameters.');
        }
        $default = array(
                'listFilters' => array(),
                'listDisplay' => array(),
                'searchFields' => array(),
                'sortFields' => array()
        );
        $p = array_merge($default, $p);
        $sql = new Pluf_SQL('tenant=%s',
                array(
                        $request->tenant->id
                ));
        if (isset($p['sql'])) {
            $sql = $sql->SAnd($p['sql']);
        }
        // Create page
        $page = new Pluf_Paginator(new $p['model']());
        $page->forced_where = $sql;
        $page->list_filters = $p['listFilters'];
        $page->configure($p['listDisplay'], $p['searchFields'],
                $p['sortFields']);
        // XXX: maso, 1395: add sort order
//         $page->sort_order = array(
//             'creation_dtime',
//             'DESC'
//         );
        $page->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($page->render_object());
    }

    /**
     * یک موجودیت جدید در ملک ایجاد می‌کند.
     *
     *
     * فرض شده که مدل در ساختار داده‌ای خود یک فیلد برای شناسه ملک در نظر گرفته
     * است.
     *
     * برای اینکه این فراخوانی پویا باشه و بتونیم برای کاربردهای متفاوت استفاده
     * کنیم، پارامترهای اضافه زیر رو در نظر گرفتیم
     *
     * 'model' - نام مدل داده‌ای که یک پارامتر مورد نیاز است
     *
     * 'extra_form' - Array of key/values to be added to the
     * form generation (array())
     *
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
                'extra_form' => array()
        );
        $p = array_merge($default, $p);
        if (! isset($p['model'])) {
            throw new Pluf_Exception(
                    'The model class was not provided in the parameters.');
        }
        // Set the default
        $model = $p['model'];
        $object = new $model();
        $form = Pluf_Shortcuts_GetFormForModel($object, $request->REQUEST, 
                $p['extra_form']);
        $object = $form->save(false);
        $object->tenant = $request->tenant;
        $object->create();
        if (! $request->user->isAnonymous()) {
            $request->user->setMessage(
                    sprintf(__('The %s was created successfully.'), 
                            $object->_a['verbose']));
        }
        return new Pluf_HTTP_Response_Json($object);
    }

    /**
     * یک موجودیت رو میگیره
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
    public function getObject ($request, $match, $p)
    {
        if (! isset($p['model'])) {
            throw new Exception(
                    'The model class was not provided in the parameters.');
        }
        // Set the default
        $object = Pluf_Shortcuts_GetObjectOr404($p['model'], 
                $match['modelId']);
        return new Pluf_HTTP_Response_Json($object);
    }

    /**
     * یک موجودیت رو به روز می‌کنه
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
    public function updateObject ($request, $match, $p)
    {
        if (! isset($p['model'])) {
            throw new Exception(
                    'The model class was not provided in the parameters.');
        }
        if(! isset($p['extra_form'])){
            $p['extra_form'] = array();
        }
        // Set the default
        $object = Pluf_Shortcuts_GetObjectOr404($p['model'], 
                $match['modelId']);
        $form = Pluf_Shortcuts_GetFormForUpdateModel($object, $request->REQUEST, 
                $p['extra_form']);
        $object = $form->save();
        if (! $request->user->isAnonymous()) {
            $request->user->setMessage(
                    sprintf(__('The %s was created successfully.'), 
                            $object->_a['verbose']));
        }
        return new Pluf_HTTP_Response_Json($object);
    }

    /**
     * یک موجودیت را از سیستم حذف می‌کند
     *
     * حداقل پارامتر مورد نیاز برای این کار
     *
     * 'model' - Class name string, required.
     *
     * پارامتر زیر هم باید درمسیر تعیین شود.
     *
     * 'modelId' - شناسه موجودید
     *
     * @param
     *            Pluf_HTTP_Request Request object
     * @param
     *            array Match
     * @param
     *            array Extra parameters
     * @return Pluf_HTTP_Response Response object (can be a redirect)
     */
    public function deleteObject ($request, $match, $p)
    {
        $default = array(
                'permanently' => false
        );
        $p = array_merge($default, $p);
        if (! isset($p['model'])) {
            throw new Exception(
                    'The model class was not provided in the parameters.');
        }
        // Set the default
        $object = Pluf_Shortcuts_GetObjectOr404($p['model'], 
                $match['modelId']);
        $objectCopy = Pluf_Shortcuts_GetObjectOr404($p['model'], 
                $match['modelId']);
        if ($p['permanently']){
            $object->delete();
        } else {
            $object->deleted = true;
            $object->update();
            $objectCopy->deleted = true;
        }
        $objectCopy->id = 0;
        if (! $request->user->isAnonymous()) {
            $request->user->setMessage(
                    sprintf(__('The %s was deleted successfully.'), 
                            $object->_a['verbose']));
        }
        return new Pluf_HTTP_Response_Json($objectCopy);
    }
}
