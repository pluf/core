<?php
namespace Pluf\Middleware;

use Pluf\Bootstrap;
use Pluf\Exception;
use Pluf\Tenant;
use Pluf\Middleware;

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class TenantFromRequestMatch implements Middleware
{

    function process_request(&$request)
    {
        if (! $request->tenant->isAnonymous()) {
            return false;
        }

        $regexs = Bootstrap::f('tenant_match', array());
        foreach ($regexs as $regex) {
            try {
                $match = array();
                if (preg_match($regex['regex'], $request->query, $match)) {
                    $app = false;
                    if ($regex['value'] == 'subdomain') {
                        $app = Tenant::bySubDomain($match[$regex['value']]);
                    } elseif ($regex['value'] == 'id') {
                        $app = new Tenant($match[$regex['match']]);
                    }
                    if (! $app)
                        continue;
                    $request->tenant = $app;
                    $request->application = $app;
                    return false;
                }
            } catch (Exception $e) {
                // echo $e->getMessage();
            }
        }

        return false;
    }

    public function process_response($request, $response)
    {}
}
