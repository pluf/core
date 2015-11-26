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

    public function spa ($request, $match)
    {
        $app = $request->application;
        if ($app->isAnonymous()) {
            throw new Pluf_Exception("Non app??");
        }
        $sap = $app->get_sap();
        $repo = Pluf::f('saas_spa_repository');
        
        $package = $sap->loadPackage();
        list ($jsLib, $cssLib, $libs) = $this->loadLibrary($package);
        
        // نمایش اصلی
        $params = array(
                'sap' => $sap,
                'app' => $app,
                'title' => __('ghazal'),
                'mainView' => $repo . $sap->path . $package['view'],
                'jsLibs' => $jsLib,
                'cssLibs' => $cssLib,
                'package' => $package
        );
        return Pluf_Shortcuts_RenderToResponse('spa.html', $params, $request);
    }

    public function appcache ($request, $match)
    {
        $app = $request->application;
        if ($app->isAnonymous()) {
            throw new Pluf_Exception("Non app??");
        }
        $sap = $app->get_sap();
        $repo = Pluf::f('saas_spa_repository');
        $package = $sap->loadPackage();
        list ($jsLib, $cssLib, $libs) = $this->loadLibrary($package);
        
        // نمایش اصلی
        $params = array(
                'sap' => $sap,
                'app' => $app,
                'title' => __('ghazal'),
                'mainView' => $repo . $sap->path . $package['view'],
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