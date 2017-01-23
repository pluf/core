<?php

/**
 * نمایش و اجرای spa
 * 
 * @author maso
 *
 */
class Seo_Views_Sitemap
{

    /**
     * سایت مپ رو تولید می‌کنه
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     * @return Pluf_HTTP_Response
     */
    public static function get ($request, $match)
    {
        $sp = new SPA();
        $spaList = $sp->getList();
        
        // TODO: روشی برای اضافه کردن لینک های خارجی هم باید ایجاد بشه
        // Add link to SPAs of tenant
        $tmpl = new Pluf_Template('/sitemap.template');
        $context = new Pluf_Template_Context(
                array(
                        'tenant' => $request->tenant,
                        'spaList' => $spaList
                ));
        $mimetype = Pluf::f('mimetype', 'text/xml') . '; charset=utf-8';
        return new Pluf_HTTP_Response($tmpl->render($context), $mimetype);
    }
}