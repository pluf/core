<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

/**
 * پیش شرط‌های سیستم را ایجاد می‌کند.
 *
 * @author maso
 *        
 */
class SaaS_Precondition
{

    /**
     * دسترسی به نمایش را بر اساس مدل تجاری فریمیوم بررسی می‌کند.
     *
     * @param unknown $request            
     */
    static public function freemium ($request)
    {
        if (! isset($request->application) || $request->application == null ||
                 $request->application->isAnonymous()) {
            throw new Pluf_Exception("Application is not defined.");
        }
        $config = $request->application->fetchConfiguration("system");
        $level = $config->getData('level', 0);
        if (isset($request->view['ctrl']['freemium']['level']) &&
                 $level < $request->view['ctrl']['freemium']['level']) {
            throw new Pluf_Exception_PermissionDenied("Application level is low");
        }
        return true;
    }

    /**
     * بررسی دسترسی پایه به نرم‌افزار
     *
     * در برخی موارد نیاز است که سایت به صورت موقت بسته شود. این فراخوانی برای
     * تعیین بسته بودن سایت است.
     *
     * @param
     *            Pluf_HTTP_Request
     * @return mixed
     */
    static public function baseAccess ($request, $app = null)
    {
        if ($request->application == null) {
            $request->application = $app;
        }
        if (($request->application == null ||
                 $request->application->isAnonymous()) &&
                 isset($request->view['ctrl']['saas']['match-application'])) {
            $request->application = Pluf_Shortcuts_GetObjectOr404(
                    'SaaS_Application', 
                    $request->view['match'][$request->view['ctrl']['saas']['match-application']]);
        }
        if ($request->application == null) {
            throw new Pluf_Exception("Application is not defined.");
        }
        if (Pluf::f('saas_freemium_enable', false)) {
            SaaS_Precondition::freemium($request);
        }
        return true;
    }

    /**
     * بررسی مالک نرم‌افزار
     *
     * @param
     *            Pluf_HTTP_Request
     * @return mixed
     */
    static public function applicationOwner ($request, $app = null)
    {
        $res = Pluf_Precondition::loginRequired($request, $app);
        if (true !== $res) {
            return $res;
        }
        SaaS_Precondition::baseAccess($request, $app);
        if ($request->user->administrator) {
            return true;
        }
        if ($request->user->hasPerm('SaaS.software-owner', 
                $request->application)) {
            return true;
        }
        throw new Pluf_Exception_PermissionDenied();
    }

    /**
     * بررسی این که عضو و یا مالک یک نرم‌افزار
     *
     * @param
     *            Pluf_HTTP_Request
     * @return mixed
     */
    static public function applicationMemberOrOwner ($request, $app = null)
    {
        $res = Pluf_Precondition::loginRequired($request);
        if (true !== $res) {
            return $res;
        }
        SaaS_Precondition::baseAccess($request, $app);
        if ($request->user->administrator) {
            return true;
        }
        if ($request->user->hasPerm('SaaS.software-owner', 
                $request->application) || $request->user->hasPerm(
                'SaaS.software-member', $request->application)) {
            return true;
        }
        throw new Pluf_Exception_PermissionDenied();
    }

    public static function userCanCreateLib ($request)
    {
        return true;
    }

    public static function userCanAccessLibs ($request)
    {
        return true;
    }

    public static function userCanAccessLib ($request, $lib)
    {
        return true;
    }

    public static function userCanUpdateLib ($request, $lib)
    {
        return true;
    }

    public static function userCanDeleteLib ($request, $lib)
    {
        return true;
    }
}