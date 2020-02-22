<?php
namespace Pluf\Middleware;

use Pluf\Middleware;
use Pluf\Tenant;

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class TenantEmpty implements Middleware
{

    function process_request(&$request)
    {
        if (isset($request->tenant) && (! $request->tenant->isAnonymous())) {
            return false;
        }

        $request->tenant = new Tenant();
        $request->application = $request->tenant;
        return false;
    }

    public function process_response($request, $response)
    {}
}
