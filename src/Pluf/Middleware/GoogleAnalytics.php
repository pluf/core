<?php

/**
 * میان افزار GoogleAnalytics را ایجادمی‌کند.
 * 
 *  این واسط کد دنبال کردن تحلیل‌های گوگل را به تمام سیستم اضافه می‌کند.
 *  
 *  
 *  @deprecated Move to the CRM package
 */
class Pluf_Middleware_GoogleAnalytics
{

    /**
     * Process the response of a view.
     *
     * If the status code and content type are allowed, add the
     * tracking code.
     *
     * @param
     *            Pluf_HTTP_Request The request
     * @param
     *            Pluf_HTTP_Response The response
     * @return Pluf_HTTP_Response The response
     */
    function process_response ($request, $response)
    {
        if (! Pluf::f('google_analytics_id', false)) {
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
        $js = '<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>
<script type="text/javascript"> try {
var pageTracker = _gat._getTracker("' .
                 Pluf::f('google_analytics_id') . '");
pageTracker._trackPageview(); } catch(err) {}
</script>';
        $response->content = str_replace('</body>', $js . '</body>', 
                $response->content);
        return $response;
    }
}
