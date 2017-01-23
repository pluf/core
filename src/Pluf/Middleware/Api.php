<?php

/**
 * بهبود نتیجه فراخوانی‌ها
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 */
class Pluf_Middleware_Api
{

    /**
     * نتایج فراخوانی را بهبود می‌دهد
     * 
     * تمام درخواست‌هایی که فراخوانی سیستم باشند را بهبود می‌دهد تا مشکلی
     * سمت کلاینت نباشد. مثلا کش را غیر فعال می‌کند.
     *
     * @param
     *            Pluf_HTTP_Request The request
     * @param
     *            Pluf_HTTP_Response The response
     * @return Pluf_HTTP_Response The response
     */
    function process_response ($request, $response)
    {
        $prefix = '/api/';
        if (strncmp($request->query, $prefix, strlen($prefix)) === 0){
            $response->headers['Cache-Control'] = 'no-cache, no-store, must-revalidate';
            $response->headers['Pragma'] = 'no-cache';
            $response->headers['Expires'] = '0';
        }
        return $response;
    }
}
