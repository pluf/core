<?php
namespace Pluf\Middleware;

use Pluf\Bootstrap;
use Pluf\Tenant;
use Pluf\HTTP\Response;

/**
 * Redirects to the main (default) tenant if tenant of request could not be find.
 *
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *        
 */
class Pluf_Middleware_TenantRedirect
{

    function process_request(&$request)
    {
        if (! $request->tenant->isAnonymous()) {
            return false;
        }

        $tenantSubdomain = Bootstrap::f('tenant_default', 'www');
        $tenant = Tenant::bySubDomain($tenantSubdomain);
        if ($tenant) {
            $url = 'http://' . $tenant->domain . '/';
            $response = new Response\Redirect($url, 302);
            return $response;
        }
        return false;
    }
}
