<?php
namespace Pluf\Middleware;

use Pluf\Bootstrap;
use Pluf\Middleware;
use Pluf\Tenant;

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class TenantFromConfig implements Middleware
{

    function process_request(&$request)
    {
        if (! $request->tenant->isAnonymous()) {
            return false;
        }

        $appName = Bootstrap::f('tenant_default', 'www');
        $app = Tenant::bySubDomain($appName);
        if ($app) {
            $request->tenant = $app;
        }
        return false;
    }

    public function process_response($request, $response)
    {}
}
