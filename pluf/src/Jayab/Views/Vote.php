<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_RenderToResponse');

/**
 * لایه نمایش از رای را ایجاد می‌کند
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 * @since 0.1.0
 *       
 */
class Jayab_Views_Vote
{

    /**
     * فهرستی از تمام رای‌ها را ایجاد می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_NotImplemented
     */
    function votes ($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }

    /**
     * یک رای جدید را ایجاد می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_NotImplemented
     */
    function create ($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }

    /**
     * پیش فرض‌هایی دسترسی به داده‌های یک رای
     * 
     * @var unknown
     */
    public $get_precond = array(
            'Pluf_Precondition::loginRequired'
    );

    /**
     * اطلاعات یک رای را تعیین می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_DoesNotExist
     * @return Pluf_HTTP_Response_Json
     */
    function get ($request, $match)
    {
        $location = Pluf_Shortcuts_GetObjectOr404('Jayab_Vote', $match[1]);
        if ($vote != null) {
            return new Pluf_HTTP_Response_Json($vote);
        }
        throw new Pluf_Exception_DoesNotExist();
    }
}