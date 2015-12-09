<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Middleware_TenantFromConfig
{

    function process_request (&$request)
    {
        if (! $request->tenant->isAnonymous()) {
            return false;
        }
        
        try{
            $appName = Pluf::f('saas_tenant_default');
            $app = SaaS_Application::bySubDomain($appName);
            $request->tenant = $app;
            $request->application = $app;
        } catch(Exception $ex){
            // Add log
        }
        return false;
    }
}
