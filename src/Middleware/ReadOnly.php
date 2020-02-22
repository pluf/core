<?php

/**
 * Readonly middleware.
 *
 * It is intercepting all the POST requests with a message telling
 * that the website is in read only mode.
 *
 * Optionally, a message at the top of the page is added to inform
 * that the website is in read only mode.
 *
 * Add the middleware at the top of your middleware list and
 * optionally add a message to be displayed in your configuration
 * file.
 *
 * Example:
 *
 * <pre>
 * $cfg['middleware_classes'] = array(
 *         'Pluf_Middleware_ReadOnly',
 *         'Pluf_Middleware_Csrf',
 *         'Pluf_Middleware_Session',
 *         'Pluf_Middleware_Translation',
 *         );
 * $cfg['read_only_mode_message'] = 'The server is in read only mode the '
 *                                 .'time to be migrated on another host.'
 *                                 .'Thank you for your patience.';
 * </pre>
 *
 * You can put HTML in your message.
 *
 */
class Pluf_Middleware_ReadOnly
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
        if ($request->method == 'POST') {
            $res = new Pluf_HTTP_Response(
                    'Server in read only mode' . "\n\n" .
                             'We are upgrading the system to make it better for you, please try again later...', 
                            'text/plain');
            $res->status_code = 503;
            return $res;
        }
        return false;
    }

    /**
     * Process the response of a view.
     *
     * If configured, add the message to inform that the website is in
     * read only mode.
     *
     * @param
     *            Pluf_HTTP_Request The request
     * @param
     *            Pluf_HTTP_Response The response
     * @return Pluf_HTTP_Response The response
     */
    function process_response ($request, $response)
    {
        if (! Pluf::f('read_only_mode_message', false)) {
            return $response;
        }
        if (! in_array($response->status_code, 
                array(
                        200,
                        201,
                        202,
                        203,
                        204,
                        205,
                        206,
                        404,
                        501
                ))) {
            return $response;
        }
        $ok = false;
        $cts = array(
                'text/html',
                'application/xhtml+xml'
        );
        foreach ($cts as $ct) {
            if (false !== strripos($response->headers['Content-Type'], $ct)) {
                $ok = true;
                break;
            }
        }
        if ($ok == false) {
            return $response;
        }
        $message = Pluf::f('read_only_mode_message');
        $response->content = str_replace('<body>', 
                '<body><div style="width: 50%; color: #c00; border: 2px solid #c00; padding: 5px; margin: 1em auto 2em; background-color: #fffde3">' .
                         $message . '</div>', $response->content);
        return $response;
    }
}
