<?php

class Seo_Views_Xsl extends Pluf_Views
{

    /**
     * مقدار یک خصوصیت را تعیین می‌کند.
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public function get ($request, $match)
    { // Set the default
        $path = __DIR__ .'/../resource/xml-'.$match['style'].'.xsl';
        $response = new Pluf_HTTP_Response_File($path, 'text/xsl');
        $response->headers['Content-Disposition'] = sprintf(
                'attachment; filename="%s"', 'sitemap.xsl');
        return $response;
    }
}