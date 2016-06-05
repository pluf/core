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
    	// XXX: دامنه 
        if (! $request->tenant->isAnonymous()) {
            return false;
        }
        $app = new SaaS_Application();
        $request->tenant = $app;
        $request->application = $app;
        return false;
    }
}
