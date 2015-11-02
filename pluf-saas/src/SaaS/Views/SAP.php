<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Views_SAP
{

    public function sap ($request, $match)
    {
        $app = $request->application;
        if ($app->isAnonymous()) {
            throw new Pluf_Exception("Non app??");
        }
        $sap = $app->get_sap();
        $repo = Pluf::f('saas_sap_repository');
        
        // بار گذاری بسته
        $package = null;
        {
            $filename = $repo . $sap->path .
                     Pluf::f('saas_sap_package', "/sap.json");
            if (is_readable($filename)) {
                $myfile = fopen($filename, "r") or die("Unable to open file!");
                $json = fread($myfile, filesize($filename));
                fclose($myfile);
                $package = json_decode($json, true);
            }
        }
        // کتابخانه‌ها
        $cssLib = array();
        $jsLib = array();
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
                throw new Pluf_Exception('library '.$n.' does not exit.');
            }
            if($items[0]->type == SaaS_LibType::JavaScript)
                $jsLib[] = $items[0];
            else 
                $cssLib[] = $items[0];
        }
        
        // نمایش اصلی
        $params = array(
                'sap' => $sap,
                'title' => __('ghazal'),
                'mainView' => $repo . $sap->path . $package['view'],
                'sources' => $package['src'],
                'links' => $package['link'],
                'metas' => $package['meta'],
                'jsLibs' => $jsLib,
                'cssLibs' => $cssLib,
                'appcache' => $package['appcache']
        );
        return Pluf_Shortcuts_RenderToResponse('sap.html', $params, $request);
    }

    public function appcache ($request, $match)
    {
        $params = array();
        return Pluf_Shortcuts_RenderToResponse('saas.appcache', $params, 
                $request);
    }
}