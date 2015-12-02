<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('SaaS_Shortcuts_GetSAPOr404');
Pluf::loadFunction('SaaS_Shortcuts_GetApplicationOr404');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Views_SPA
{

    public function main ($request, $match)
    {
        $app = $request->application;
        if ($app->isAnonymous()) {
            throw new Pluf_Exception("Non app??");
        }
        if ($app->spa != 0)
            $spa = $app->get_sap();
        else
            $spa = new SaaS_SPA(Pluf::f('saas_spa_default', 1));
        $repo = Pluf::f('saas_spa_repository');
        
        $package = $spa->loadPackage();
        list ($jsLib, $cssLib, $libs) = $this->loadLibrary($package);
        
        // نمایش اصلی
        $params = array(
                'spa' => $spa,
                'app' => $app,
                'title' => __('ghazal'),
                'mainView' => $repo . $spa->path . $package['view'],
                'jsLibs' => $jsLib,
                'cssLibs' => $cssLib,
                'package' => $package
        );
        return Pluf_Shortcuts_RenderToResponse('spa.html', $params, $request);
    }

    public function spa ($request, $match)
    {
        $app = $request->application;
        $spa = new SaaS_SPA($match[2]);
        $repo = Pluf::f('saas_spa_repository');
        
        // Check access
        SaaS_Precondition::userCanAccessApplication($request, $app);
        SaaS_Precondition::userCanAccessSpa($request, $spa);
        
        // نمایش اصلی
        $package = $spa->loadPackage();
        list ($jsLib, $cssLib, $libs) = $this->loadLibrary($package);
        $params = array(
                'spa' => $spa,
                'app' => $app,
                'title' => __('ghazal'),
                'mainView' => $repo . $spa->path . $package['view'],
                'jsLibs' => $jsLib,
                'cssLibs' => $cssLib,
                'package' => $package
        );
        return Pluf_Shortcuts_RenderToResponse('spa.html', $params, $request);
    }

    public function appcache ($request, $match)
    {
        $app = $request->application;
        $spa = new SaaS_SPA($match[2]);
        if ($app->isAnonymous()) {
            throw new Pluf_Exception("Non app??");
        }
        $repo = Pluf::f('saas_spa_repository');
        $package = $spa->loadPackage();
        list ($jsLib, $cssLib, $libs) = $this->loadLibrary($package);
        
        // نمایش اصلی
        $params = array(
                'spa' => $spa,
                'app' => $app,
                'title' => __('ghazal'),
                'mainView' => $repo . $spa->path . $package['view'],
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
}