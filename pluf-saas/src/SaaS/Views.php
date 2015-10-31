<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * @ingroup views
 * @brief این کلاس نمایش‌های اصلی سیستم را ایجاد می‌کند.
 *
 *
 *
 * @date 1394
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 */
class SaaS_Views extends Pluf_Views
{

    public function appcache ($request, $match)
    {
        $params = array();
        return Pluf_Shortcuts_RenderToResponse('saas.appcache', $params, 
                $request);
    }

    /**
     * @breif برگه اصلی هر نرم‌افزار
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function index ($request, $match)
    {
        $params = array();
        // TODO: maso, 1394: اضافه کردن تنظیم‌های
        return Pluf_Shortcuts_RenderToResponse('index.html', $params, $request);
    }

    /**
     * برگه‌هایی که همه به آن دسترسی دارند
     *
     * /page/{name}
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response
     */
    public function page ($request, $match)
    {
        $params = array();
        // TODO: maso, 1394: اضافه کردن تنظیم‌های
        return Pluf_Shortcuts_RenderToResponse('/page/' . $match[1] . '.html', 
                $params, $request);
    }

    /**
     * پیش شرط‌های دسترسی
     *
     * @var unknown
     */
    public $application_precond = array(
            'SaaS_Precondition::baseAccess'
    );

    /**
     * @breif برگه اصلی هر نرم‌افزار
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function application ($request, $match)
    {
        $params = array();
        $params['application'] = $request->application;
        // TODO: maso, 1394: اضافه کردن تنظیم‌های
        return Pluf_Shortcuts_RenderToResponse('application/index.html', 
                $params, $request);
    }

    /**
     * پیش شرط‌های دسترسی
     *
     * @var unknown
     */
    public $applicationPage_precond = array(
            'SaaS_Precondition::baseAccess'
    );

    /**
     * برگه‌هایی که همه به آن دسترسی دارند
     *
     * این برگه‌ها به صورت زیر آدرس دهی می‌شوند:
     *
     * /{application id}/{name}
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response
     */
    public function applicationPage ($request, $match)
    {
        $params = array();
        $params['application'] = $request->application;
        // TODO: maso, 1394: اضافه کردن تنظیم‌های
        return Pluf_Shortcuts_RenderToResponse('application/' . $match[2], 
                $params, $request);
    }

    /**
     * پیش شرط‌های اعضا
     *
     * @var unknown
     */
    public $member_precond = array(
            'SaaS_Precondition::applicationMemberOrOwner'
    );

    /**
     * برگه‌هایی که اعضا به آن دسترسی دارند
     *
     * این برگه‌ها به صورت زیر آدرس دهی می‌شوند:
     *
     * /{application id}/member/{name}
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response
     */
    public function member ($request, $match)
    {
        $params = array();
        $params['application'] = $request->application;
        // TODO: maso, 1394: اضافه کردن تنظیم‌های
        return Pluf_Shortcuts_RenderToResponse(
                'application/member/' . $match[2], $params, $request);
    }

    /**
     * پیش شرط‌های دسترسی
     *
     * @var unknown
     */
    public $owner_precond = array(
            'SaaS_Precondition::applicationOwner'
    );

    /**
     * برگه‌هایی که اعضا به آن دسترسی دارند
     *
     * این برگه‌ها به صورت زیر آدرس دهی می‌شوند:
     *
     * /{application id}/owner/{name}
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response
     */
    public function owner ($request, $match)
    {
        $params = array();
        $params['application'] = $request->application;
        // TODO: maso, 1394: اضافه کردن تنظیم‌های
        if (array_key_exists(2, $match)) {
            return Pluf_Shortcuts_RenderToResponse(
                    'application/owner/' . $match[2] . '.html', $params, 
                    $request);
        }
        return Pluf_Shortcuts_RenderToResponse('application/owner/index.html', 
                $params, $request);
    }

    /**
     * پیش شرط‌های دسترسی
     *
     * @var unknown
     */
    public $admin_precond = array(
            'Pluf_Precondition::adminRequired'
    );

    /**
     * نرم افزارهای مدیریت را تعیین می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response
     */
    public function admin ($request, $match)
    {
        $params = array();
        // TODO: maso, 1394: اضافه کردن تنظیم‌های
        if (array_key_exists(1, $match)) {
            return Pluf_Shortcuts_RenderToResponse('admin/' . $match[1], 
                    $params, $request);
        }
        return Pluf_Shortcuts_RenderToResponse('admin/index.html', $params, 
                $request);
    }

    /**
     * پیش شرط‌های دسترسی
     *
     * @var unknown
     */
    public $user_precond = array(
            'Pluf_Precondition::loginRequired'
    );

    /**
     * نرم افزارهای مدیریت را تعیین می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response
     */
    public function user ($request, $match)
    {
        $params = array();
        // TODO: maso, 1394: اضافه کردن تنظیم‌های
        if (array_key_exists(1, $match)) {
            return Pluf_Shortcuts_RenderToResponse('user/' . $match[1], $params, 
                    $request);
        }
        return Pluf_Shortcuts_RenderToResponse('user/index.html', $params, 
                $request);
    }
}
