<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class Pluf_Middleware_TenantFromConfig
{

    function process_request (&$request)
    {
        if (! $request->tenant->isAnonymous()) {
            return false;
        }
        
        $appName = Pluf::f('tenant_default');
        if(!isset($appName) || $appName === ''){
            $appName = 'www';
        }
        $app = Pluf_Tenant::bySubDomain($appName);
        if ($app) {
            $request->tenant = $app;
            $request->application = $app;
        }
        return false;
    }
}