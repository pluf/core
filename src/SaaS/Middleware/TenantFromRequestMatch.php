<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Middleware_TenantFromRequestMatch
{

    function process_request (&$request)
    {
        if (!$request->tenant->isAnonymous()) {
            return false;
        }
        
        $regexs = Pluf::f('saas_tenant_match', array());
        foreach ($regexs as $regex) {
            try {
                $match = array();
                if (preg_match($regex['regex'], $request->query, $match)) {
                    $app = false;
                    if ($regex['value'] == 'subdomain') {
                        $app = SaaS_Application::bySubDomain(
                                $match[$regex['value']]);
                    } elseif ($regex['value'] == 'id') {
                        $app = new SaaS_Application($match[$regex['match']]);
                    }
                    if (! $app)
                        continue;
                    $request->tenant = $app;
                    $request->application = $app;
                    return false;
                }
            } catch (Exception $e) {
//                 echo $e->getMessage();
            }
        }
        
        return false;
    }
}
