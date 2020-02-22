<?php
namespace Pluf\Middleware;

use Pluf\Exception;
use Pluf\Middleware;
use Pluf\Tenant;
use Pluf\HTTP\Request;

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *        
 */
class TenantFromHeader implements Middleware
{

    /**
     *
     * @param Request $request
     * @return boolean
     */
    function process_request(&$request)
    {
        if (! $request->tenant->isAnonymous()) {
            return false;
        }
        try {
            if (array_key_exists('_PX_tenant', $request->HEADERS)) {
                $app = new Tenant($request->HEADERS['_PX_tenant']);
                if ($app) {
                    $request->tenant = $app;
                }
            }
        } catch (Exception $e) {
            // echo $e->getMessage();
        }
        return false;
    }

    public function process_response($request, $response)
    {}
}
