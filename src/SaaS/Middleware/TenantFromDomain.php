<?php

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *        
 */
class SaaS_Middleware_TenantFromDomain
{

    function process_request(&$request)
    {
        if (! $request->tenant->isAnonymous()) {
            return false;
        }
        try {
            $domain = $request->http_host;
            $domain = preg_replace('/^www\./', '', $domain);
            $app = SaaS_Application::byDomain($domain);
            if ($app) {
                $request->tenant = $app;
                $request->application = $app;
            }
        } catch (Exception $e) {
//             echo $e->getMessage();
        }
        return false;
    }
}
