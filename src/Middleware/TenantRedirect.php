<?php

/**
 * Redirects to the main (default) tenant if tenant of request could not be find.
 * 
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *        
 */
class Pluf_Middleware_TenantRedirect
{

    function process_request (&$request)
    {
        if (! $request->tenant->isAnonymous()) {
            return false;
        }
        
        $tenantSubdomain = Pluf::f('tenant_default', 'www');
        $tenant = Pluf_Tenant::bySubDomain($tenantSubdomain);
        if ($tenant) {
            $url = 'http://' . $tenant->domain . '/';
            $response = new Pluf_HTTP_Response_Redirect($url, 302);
            return $response;
        }
        return false;
    }
}
