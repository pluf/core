<?php

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class Seo_Middleware_Spa
{

    
    /**
     *
     * @param Pluf_HTTP_Request $request
     */
    function process_request (&$request)
    {
        // در صورتی که درخواست مربوط به seo باشد
        if (array_key_exists('_escaped_fragment_', $request->GET)) {
            return $this->Shortcuts_SeoResponse($request);
        }
        return false;
    }
    
    /**
     * بر اساس تقاضا یک نتیجه مناسب برای جستجوی گوگل ایجاد می‌کند.
     *
     * @param Pluf_HTTP_Request $request
     */
    function Shortcuts_SeoResponse ($request)
    {
        $context = new Pluf_Template_Context(array(
                'tenant' => $request->tenant
        ));
        $tmpl = new Pluf_Template('seo.template');
        $html = $tmpl->render($context);
        return new Pluf_HTTP_Response($tmpl->render($context));
    }
    
}
