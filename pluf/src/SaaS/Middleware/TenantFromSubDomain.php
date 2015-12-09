<?php

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Middleware_TenantFromSubDomain
{

    function process_request (&$request)
    {
        if (! $request->tenant->isAnonymous()) {
            return false;
        }
        
        return false;
    }
}
