<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('SaaS_Shortcuts_GetSPAOr404');
Pluf::loadFunction('SaaS_Shortcuts_GetApplicationOr404');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Views_SPA
{

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

    public function tenant ($request, $match)
    {
        // TODO: maso, 1394: Redirect if there is domain
        return $this->main($request, $match);
    }

    public function tenantById ($request, $match)
    {
        // TODO: maso, 1394: Redirect if there is domain
        return $this->main($request, $match);
    }

    public function tenantSpa ($request, $match)
    {
        // TODO: maso, 1394: Redirect if there is domain
        return $this->spa($request, $match);
    }

    public function tenantSpaById ($request, $match)
    {
        // TODO: maso, 1394: Redirect if there is domain
        $app = $request->tenant;
        $spa = new SaaS_SPA($match[1]);
        
        // Check access
        SaaS_Precondition::userCanAccessApplication($request, $app);
        SaaS_Precondition::userCanAccessSpa($request, $spa);
        
        // نمایش اصلی
        return $this->loadSpa($request, $app, $spa);
    }

    public function main ($request, $match)
    {
        $app = $request->tenant;
        if ($app->spa != 0)
            $spa = $app->get_spa();
        else {
            $spa = SaaS_SPA::getByName(Pluf::f('saas_spa_default', 'main'));
            return $this->loadSpa($request, $app, $spa);
        }
        
        // Check access
        SaaS_Precondition::userCanAccessApplication($request, $app);
        SaaS_Precondition::userCanAccessSpa($request, $spa);
        
        return $this->loadSpa($request, $app, $spa);
    }

    public function spa ($request, $match)
    {
        $app = $request->tenant;
        $spa = SaaS_SPA::getByName($match[1]);
        
        // Check access
        SaaS_Precondition::userCanAccessApplication($request, $app);
        SaaS_Precondition::userCanAccessSpa($request, $spa);
        
        // نمایش اصلی
        return $this->loadSpa($request, $app, $spa);
    }

    public function source ($request, $match)
    {
        $spa = new SaaS_SPA();
        $spa = $spa->getOne(
                array(
                        'filter' => "name='" . $match[1] . "'"
                ));
        $repo = Pluf::f('saas_spa_repository');
        
        // TODO: Check access (No Tentant)
        // SaaS_Precondition::userCanAccessSpa($request, $spa);
        
        // Do
        return $this->loadSource($request, $spa, $match[2]);
    }

    public function assets ($request, $match)
    {
        // Load data
        // Check access
        // DO
        return $this->loadAssets($request, $match[1]);
    }

    public function appcache ($request, $match)
    {
        $spa = new SaaS_SPA($match[1]);
        $package = $spa->loadPackage();
        list ($jsLib, $cssLib, $libs) = $this->loadLibrary($package);
        
        // نمایش اصلی
        $params = array(
                'spa' => $spa,
                'title' => __('Pluf PaaS'),
                'jsLibs' => $jsLib,
                'cssLibs' => $cssLib,
                'package' => $package
        );
        return Pluf_Shortcuts_RenderToResponse('saas.appcache', $params, 
                $request);
    }

    private function loadLibrary ($package)
    {
        // کتابخانه‌ها
        $cssLib = array();
        $jsLib = array();
        $libs = array();
        $mlib = new SaaS_Lib();
        foreach ($package['dependencies'] as $n => $v) {
            $sql = new Pluf_SQL('name=%s', 
                    array(
                            $n
                    ));
            $items = $mlib->getList(
                    array(
                            'filter' => $sql->gen()
                    ));
            if ($items->count() == 0) {
                throw new Pluf_Exception('library ' . $n . ' does not exit.');
            }
            $libs[] = $items[0];
            if ($items[0]->type == SaaS_LibType::JavaScript)
                $jsLib[] = $items[0];
            else
                $cssLib[] = $items[0];
        }
        return array(
                $jsLib,
                $cssLib,
                $libs
        );
    }

    private function loadSpa ($request, $app, $spa)
    {
        $package = $spa->loadPackage();
        list ($jsLib, $cssLib, $libs) = $this->loadLibrary($package);
        
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

    private function loadSource ($request, $spa, $name)
    {
        $p = $spa->getSourcePath($name);
        return new Pluf_HTTP_Response_File($p, SaaS_FileUtil::getMimeType($p));
    }

    private function loadAssets ($request, $name)
    {
        $p = SaaS_SPA::getAssetsPath($name);
        return new Pluf_HTTP_Response_File($p, SaaS_FileUtil::getMimeType($p));
    }
}