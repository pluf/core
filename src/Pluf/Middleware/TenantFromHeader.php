<?php

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *        
 */
class Pluf_Middleware_TenantFromHeader
{

    /**
     *
     * @param Pluf_HTTP_Request $request            
     * @return boolean
     */
    function process_request (&$request)
    {
        if (! $request->tenant->isAnonymous()) {
            return false;
        }
        try {
            if(array_key_exists('_PX_tenant', $request->HEADERS)){                
                $app = new Pluf_Tenant($request->HEADERS['_PX_tenant']);
                if ($app) {
                    $request->tenant = $app;
                }
            }
        } catch (Exception $e) {
            // echo $e->getMessage();
        }
        return false;
    }
}
