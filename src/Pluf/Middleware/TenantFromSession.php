<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class Pluf_Middleware_TenantFromSession
{

    function process_request (&$request)
    {
        if (! $request->tenant->isAnonymous()) {
            return false;
        }
        
        // $application_id = $request->session->getData('application', '');
        // if ($application_id === '') {
        // $application_id = null;
        // }
        
        return false;
    }
}
