<?php

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class Pluf_Middleware_TenantEmpty
{

    function process_request (&$request)
    {
        if (isset($request->tenant) && (! $request->tenant->isAnonymous())) {
            return false;
        }
        
        $request->tenant = new Pluf_Tenant();
        $request->application = $request->tenant;
        return false;
    }
}
