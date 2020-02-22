<?php
namespace Pluf\Middleware;

use Pluf\Exception;
use Pluf\Middleware;
use Pluf\Tenant;

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *        
 */
class TenantFromDomain implements Middleware
{

    /**
     *
     * {@inheritdoc}
     * @see Middleware::process_request()
     */
    function process_request(&$request)
    {
        if ($request->tenant != null && ! $request->tenant->isAnonymous()) {
            return false;
        }
        try {
            $domain = $request->http_host;
            // Remove 'www.' if exist
            $domain = preg_replace('/^www\./', '', $domain);
            // Find tenant by domain
            $app = Tenant::byDomain($domain);
            if ($app) {
                $request->tenant = $app;
                $request->application = $app;
            }
        } catch (Exception $e) {
            // echo $e->getMessage();
        }
        return false;
    }

    /**
     *
     * {@inheritdoc}
     * @see Middleware::process_response()
     */
    public function process_response($request, $response)
    {
        return $response;
    }
}
