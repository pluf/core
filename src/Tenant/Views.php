<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * لایه نمایش مدیریت گروه‌ها را به صورت پیش فرض ایجاد می‌کند
 *
 * @author maso
 *        
 */
class Tenant_Views extends Pluf_Views
{

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function current ($request, $match)
    {
        return new Pluf_HTTP_Response_Json($request->tenant);
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function find ($request, $match)
    {
        // maso, 1394: گرفتن فهرست مناسبی از نرم افزارها
        $pag = new Pluf_Paginator(new Pluf_Tenant());
        if (! $request->user->administrator) {
            $pag->model_view = 'user_model_permission';
            $pag->forced_where = new Pluf_SQL(
                    'model_class=%s AND owner_class=%s AND owner_id=%s', 
                    array(
                            'Pluf_Tenant',
                            'Pluf_User',
                            $request->user->id
                    ));
        }
        $list_display = array(
                'id' => __('tenant id'),
                'title' => __('title'),
                'validate' => __('validate'),
                'domain' => __('domain'),
                'subdomain' => __('subdomain'),
                'creation_dtime' => __('creation date')
        );
        $search_fields = array(
                'title',
                'description',
                'domain',
                'subdomain'
        );
        $sort_fields = array(
                'id',
                'title',
                'domain',
                'subdomain',
                'creation_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->action = array();
        $pag->no_results_text = __('no tenant is found');
        $pag->sort_order = array(
                'creation_dtime',
                'DESC'
        );
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function create ($request, $match)
    {
        $model = Pluf::factory('Pluf_Tenant');
        $form = Pluf_Shortcuts_GetFormForModel($model, $request->REQUEST, 
                array());
        $model = $form->save();
        Pluf_RowPermission::add($request->user, $model, 'SaaS.owner', 
                $model->id);
        return new Pluf_HTTP_Response_Json($model);
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function update ($request, $match)
    {
        $model = Pluf_Shortcuts_GetObjectOr404('Pluf_Tenant', $match['id']);
        $form = Pluf_Shortcuts_GetFormForModel($model, $request->REQUEST, 
                array());
        return new Pluf_HTTP_Response_Json($form->save());
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function get ($request, $match)
    {
        $model = Pluf_Shortcuts_GetObjectOr404('Pluf_Tenant', $match['id']);
        return new Pluf_HTTP_Response_Json($model);
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function delete ($request, $match)
    {
        $model = Pluf_Shortcuts_GetObjectOr404('Pluf_Tenant', $match['id']);
        $model2 = Pluf_Shortcuts_GetObjectOr404('Pluf_Tenant', 
                $match['id']);
        $model2->delete();
        // XXX: maso, 1395: delete permisions
        // XXX: maso, 1395: delete files
        // XXX: maso, 1395: delete Settings, configs
        // XXX: maso, 1395: emite signal
        return new Pluf_HTTP_Response_Json($model);
    }
}
