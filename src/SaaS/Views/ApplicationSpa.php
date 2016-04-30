<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('SaaS_Shortcuts_GetSPAOr404');

/**
 * کار با برنامه‌های کاربردی یک ملک
 * 
 * توی هر ملک دسته‌ای از نرم‌افزارهای کاربری وجود داده که مدیریت می‌تونه آنها را
 * مدیریت کنه. این کلاس نمایش‌هایی برای این کار فراهم کرده است.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Views_ApplicationSpa
{

    /**
     * برنامه کاربردی پیش فرض
     * 
     * برنامه کاربردی پیش فرض برای یک ملک را تعیین می‌کند. در صورتی که برنامه کاربردی
     * پیش فرض برای ملک تعیین نشده باشد، برنامه کاربردی پیش فرض سیستم را به عنوان نتیجه
     * این فراخوانی در نظر میگیرد. برنامه پیش فرض سیستم به صورتی زیر تعیین می‌شود.:
     * 
     * $cfg['saas-spa-default'] = 'spa name';
     * 
     * این برنامه در تنظیم‌های کلی سیستم تعیین می‌شود.
     * 
     * @param unknown $request
     * @param unknown $match
     */
    public static function getDefaultSpa ($request, $match)
    {
        // TODO: در صورتی که برنامه پیش فرض تعیین نشده است، برنامه پیش فرض سیستم نمایش داده شود.
        return new Pluf_HTTP_Response_Json($request->tenant->get_spa());
    }

    public function setDefaultSpa ($request, $match)
    {
        $spa = SaaS_Shortcuts_GetSPAOr404($match[1]);
        $request->tenant->spa = $spa;
        $request->tenant->update();
        return new Pluf_HTTP_Response_Json($request->tenant);
    }

    public function getByName ($request, $match)
    {
        $spa = SaaS_SPA::getByName($match[1]);
        return new Pluf_HTTP_Response_Json($spa);
    }

    public function getById ($request, $match)
    {
        $spa = new SaaS_SPA($match[1]);
        return new Pluf_HTTP_Response_Json($spa);
    }

    public function detail ($request, $match)
    {
        $spa = SaaS_Shortcuts_GetSPAOr404($match[1]);
        $package = $spa->loadPackage();
        return new Pluf_HTTP_Response_Json($package);
    }

    public function update ($request, $match)
    {
        $spa = SaaS_Shortcuts_GetSPAOr404($match[1]);
        $form = new SaaS_Form_ApplicationSpa(
                array_merge($request->REQUEST, $request->FILES), 
                array(
                        'tenant' => $request->tenant,
                        'spa' => $spa
                ));
        return new Pluf_HTTP_Response_Json($form->save());
    }

    /**
     * تمام دسترسی‌ها را حذف می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function removePermissions ($request, $match)
    {
        $spa = SaaS_Shortcuts_GetSPAOr404($match[1]);
        Pluf_RowPermission::remove($request->tenant, $spa, 
                'SaaS.spa-owner-access');
        Pluf_RowPermission::remove($request->tenant, $spa, 
                'SaaS.spa-member-access');
        Pluf_RowPermission::remove($request->tenant, $spa, 
                'SaaS.spa-authorized-access');
        Pluf_RowPermission::remove($request->tenant, $spa, 
                'SaaS.spa-anonymous-access');
        return new Pluf_HTTP_Response_Json($spa);
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function find ($request, $match)
    {
        $pag = new Pluf_Paginator(new SaaS_SPA());
        $pag->model_view = 'spa_application';
        // $pag->model_view = 'spa_application_permission';
        $sql = new Pluf_SQL('model_class=%s AND owner_class=%s AND owner_id=%s', 
                array(
                        'SaaS_SPA',
                        'SaaS_Application',
                        $request->application->id
                ));
        // Permissions
        $perms = array();
        $perms[] = Pluf_Permission::getFromString('SaaS.spa-anonymous-access');
        if (! $request->user->isAnonymous()) {
            $perms[] = Pluf_Permission::getFromString(
                    'SaaS.spa-authorized-access');
            $perms[] = Pluf_Permission::getFromString('SaaS.spa-member-access');
            $perms[] = Pluf_Permission::getFromString('SaaS.spa-owner-access');
        }
        
        $permSql = new Pluf_SQL();
        foreach ($perms as $permission) {
            $permSql->SOr(
                    new Pluf_SQL('permission=%s', 
                            array(
                                    $permission->id
                            )));
        }
        $sql->SAnd($permSql);
        
        $pag->forced_where = $sql;
        
        $list_display = array(
                'id' => __('id'),
                'title' => __('title'),
                'creation_dtime' => __('create')
        );
        $search_fields = array();
        $sort_fields = array(
                'creation_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->action = array();
        $pag->items_per_page = $this->getListCount($request);
        $pag->no_results_text = __('No apartment is added yet.');
        $pag->sort_order = array(
                'creation_dtime',
                'DESC'
        );
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }
    
    // public function tenant ($request, $match)
    // {
    // // TODO: maso, 1394: Redirect if there is domain
    // return $this->main($request, $match);
    // }
    
    // public function tenantById ($request, $match)
    // {
    // // TODO: maso, 1394: Redirect if there is domain
    // return $this->main($request, $match);
    // }
    
    // public function tenantSpa ($request, $match)
    // {
    // // TODO: maso, 1394: Redirect if there is domain
    // return $this->spa($request, $match);
    // }
    
    /**
     * تعداد گزینه‌های یک لیست را تعیین می‌کند.
     *
     * TODO: maso, 1394: این تعداد می‌تواند برای کاربران متفاوت باشد.
     *
     * @param unknown $request            
     * @return number
     */
    protected function getListCount ($request)
    {
        $count = 5;
        if (array_key_exists('_px_count', $request->GET)) {
            $count = $request->GET['_px_count'];
            if ($count > 20 || $count < 1) {
                $count = 20;
            }
        }
        return $count;
    }

    /**
     * اطلاعات یک نرم افزار را لود می‌کند.
     *
     * @param unknown $request            
     * @param unknown $app            
     * @param unknown $spa            
     */
    protected function loadSpa ($request, $app, $spa)
    {
        $package = $spa->loadPackage();
        list ($jsLib, $cssLib, $libs) = SaaS_Views_SPA::loadLibrary($package);
        
        // نمایش اصلی
        $params = array(
                'spa' => $spa,
                'app' => $app,
                'title' => __('ghazal'),
                'mainView' => $spa->getMainViewPath(),
                'jsLibs' => $jsLib,
                'cssLibs' => $cssLib,
                'package' => $package,
                'base' => $request->query
        );
        return Pluf_Shortcuts_RenderToResponse('spa.template', $params, 
                $request);
    }
}