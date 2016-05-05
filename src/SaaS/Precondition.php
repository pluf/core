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
    static public function freemium($request)
    {
        if (! isset($request->application) || $request->application == null || $request->application->isAnonymous()) {
            throw new Pluf_Exception("Application is not defined.");
        }
        $config = $request->application->fetchConfiguration("system");
        $level = $config->getData('level', 0);
        if (isset($request->view['ctrl']['freemium']['level']) && $level < $request->view['ctrl']['freemium']['level']) {
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
    static public function baseAccess($request)
    {
        if ($request->tenant == null || $request->tenant->isAnonymous()) {
            throw new Pluf_Exception("Tenant is not defined.");
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
    static public function applicationOwner($request, $app = null)
    {
        $res = Pluf_Precondition::loginRequired($request);
        if (true !== $res) {
            return $res;
        }
        SaaS_Precondition::baseAccess($request, $app);
        if ($request->user->administrator) {
            return true;
        }
        if ($request->user->hasPerm('SaaS.software-owner', $request->tenant)) {
            return true;
        }
        throw new Pluf_Exception_PermissionDenied();
    }

    static public function tenantOwner($request)
    {
        $res = Pluf_Precondition::loginRequired($request);
        if (true !== $res) {
            return $res;
        }
        SaaS_Precondition::baseAccess($request);
        if ($request->user->administrator) {
            return true;
        }
        if ($request->user->hasPerm('SaaS.software-owner', $request->application)) {
            return true;
        }
        throw new Pluf_Exception_PermissionDenied();
    }

    static public function tenantMember($reqeust)
    {
        $res = Pluf_Precondition::loginRequired($request);
        if (true !== $res) {
            return $res;
        }
        SaaS_Precondition::baseAccess($request, $app);
        if ($request->user->administrator) {
            return true;
        }
        if ($request->user->hasPerm('SaaS.software-owner', $request->application) 
                || $request->user->hasPerm('SaaS.software-member', $request->application)) {
            return true;
        }
        throw new Pluf_Exception_PermissionDenied();
    }

    static public function tenantAuthorized($request)
    {
        $res = Pluf_Precondition::loginRequired($request);
        if (true !== $res) {
            return $res;
        }
        SaaS_Precondition::baseAccess($request, $app);
        if ($request->user->administrator) {
            return true;
        }
        if ($request->user->hasPerm('SaaS.software-owner', $request->application) 
                || $request->user->hasPerm('SaaS.software-member', $request->application)
                || $request->user->hasPerm('SaaS.software-authorized-user', $request->application)) {
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
    static public function applicationMemberOrOwner($request, $app = null)
    {
        $res = Pluf_Precondition::loginRequired($request);
        if (true !== $res) {
            return $res;
        }
        SaaS_Precondition::baseAccess($request, $app);
        if ($request->user->administrator) {
            return true;
        }
        if ($request->user->hasPerm('SaaS.software-owner', $request->application) || $request->user->hasPerm('SaaS.software-member', $request->application)) {
            return true;
        }
        throw new Pluf_Exception_PermissionDenied();
    }

    public static function userCanCreateApplication($request)
    {
        return true;
    }

    public static function userCanAccessApplication($request, $app)
    {
        return true;
    }

    public static function userCanUpdateApplication($request, $app)
    {
        return true;
    }

    public static function userCanDeleteApplication($request, $app)
    {
        return true;
    }

    public static function userCanCreateSap($request)
    {
        return true;
    }

    /**
     * تعیین می‌کند که کاربر حق دسترسی را دارد یا نه.
     *
     * @param unknown $request            
     * @param unknown $spa
     *            در صورتی که تهی باشد پیش فرض در نظر گرفته می‌شود.
     */
    public static function userCanAccessSpa($request, $spa = null)
    {
        if ($request->application->isAnonymous()) {
            throw new Pluf_Exception("No SaaS is set");
        }
        if ($spa == null) {
            $spa = $request->application->get_spa();
        }
        if ($request->application->hasPerm("SaaS.spa-anonymous-access", $spa)) {
            return $spa;
        } elseif ($request->application->hasPerm("SaaS.spa-authorized-access", $spa) && ! $request->user - isAnonymous()) {
            return $spa;
        } elseif ($request->application->hasPerm("SaaS.spa-member-access", $spa) && $request->user->hasPerm("SaaS.software-member", $request->application)) {
            return $spa;
        } elseif ($request->application->hasPerm("SaaS.spa-owner-access", $spa) && $request->user->hasPerm("SaaS.software-owner", $request->application)) {
            return $spa;
        }
        throw new Pluf_Exception("You are not authorized to use this application.");
    }

    public static function userCanUpdateSpa($request, $sap)
    {
        return true;
    }

    public static function userCanDeleteSpa($request, $sap)
    {
        return true;
    }

    public static function userCanCreateLib($request)
    {
        return true;
    }

    public static function userCanAccessLibs($request)
    {
        return true;
    }

    public static function userCanAccessLib($request, $lib)
    {
        return true;
    }

    public static function userCanUpdateLib($request, $lib)
    {
        return true;
    }

    public static function userCanDeleteLib($request, $lib)
    {
        return true;
    }

    public static function userCanCreateResource($request)
    {
        return true;
    }

    public static function userCanAccessResource($request, $app)
    {
        return true;
    }

    public static function userCanUpdateResource($request, $app)
    {
        return true;
    }

    public static function userCanDeleteResource($request, $app)
    {
        return true;
    }
}