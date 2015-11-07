<?php

/**
 * Maintenance middleware.
 *
 * If a file MAINTENANCE exists in the temp folder then a maintenance
 * page is shown. If available, a template maintenance.html is used,
 * else a simple plain text 'Server in maintenance, please retry
 * later...' is shown.
 *
 * This middleware should be
 *
 * Only the actions starting with Pluf::f('maintenance_root') are not
 * interrupted, that way you can access a special url to perform
 * upgrade.
 */
class Pluf_Middleware_Maintenance
{

    /**
     * Process the request.
     *
     * @param
     *            Pluf_HTTP_Request The request
     * @return bool false
     */
    function process_request (&$request)
    {
        if (0 !== strpos($request->query, Pluf::f('maintenance_root')) &&
                 file_exists(Pluf::f('tmp_folder') . '/MAINTENANCE')) {
            $res = new Pluf_HTTP_Response(
                    'Server in maintenance' . "\n\n" .
                     'We are upgrading the system to make it better for you, please try again later...', 
                    'text/plain');
            $res->status_code = 503;
            return $res;
        }
        return false;
    }
}
