<?php
namespace Pluf\Middleware;
use Pluf\HTTP\Response;
use Pluf\Bootstrap;

/**
 * Tidy middleware.
 *
 * Check if a response contains HTML errors. It writes a general log
 * in the 'tmp_folder' with a view of the details of the errors in
 * separate files. It relies on the availability of tidy on the system
 * path.
 *
 * It checks only the pages with following status code: 200, 201, 202,
 * 203, 204, 205, 206, 404, 501. And the following content type:
 * text/html, text/html, application/xhtml+xml.
 *
 * @see http://tidy.sourceforge.net/
 */
class Pluf_Middleware_Tidy
{

    /**
     * Process the response of a view.
     *
     * If the status code and content type are allowed, perform the check.
     *
     * @param
     *            Pluf_HTTP_Request The request
     * @param
     *            Pluf_HTTP_Response The response
     * @return Response The response
     */
    function process_response($request, $response)
    {
        if (! in_array($response->status_code, array(
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
        $content = escapeshellarg($response->content);
        $res = array();
        $rval = 0;
        exec('(echo ' . $content . '| tidy -e -utf8 -q 3>&2 2>&1 1>&3) ', $res, $rval);
        if (empty($res)) {
            return $response;
        }
        $only_char_encoding_issue = Bootstrap::f('tidy_skip_encoding_errors', true);
        foreach ($res as $line) {
            if (false === strpos($line, 'invalid character code')) {
                $only_char_encoding_issue = false;
                break;
            }
        }
        if ($only_char_encoding_issue == true) {
            return $response;
        }
        $response->content = str_replace('</body>', '<pre style="text-align: left;">' . htmlspecialchars(join("\n", $res)) . '</pre></body>', $response->content);
        return $response;
    }
}
