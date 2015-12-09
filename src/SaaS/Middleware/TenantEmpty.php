<?php

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Middleware_TenantEmpty
{

    function process_request (&$request)
    {
        if (isset($request->tenant) && (! $request->tenant->isAnonymous())) {
            return false;
        }
        
        $request->tenant = new SaaS_Application();
        $request->application = $request->tenant;
        return false;
    }
}
