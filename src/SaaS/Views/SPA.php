<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('SaaS_Shortcuts_GetSPAOr404');
Pluf::loadFunction('SaaS_Shortcuts_GetApplicationOr404');
Pluf::loadFunction('SaaS_Migrations_Update_spa');

/**
 * کار با برنامه‌های کاربردی برای نمایش
 *
 * یکی از مهم‌ترین ساختارهای داده‌ای، ساختارهایی است که برای توصیف نرم‌افزارهای
 * کاربردی به کار برده می‌شود. این نمایش تمام راهکارهای مورد نیاز برای کار با این
 * ساختار داده‌ای را در سیستم فراهم کرده است.
 *
 * در این کلاس ابزارهایی برای لود کردن برنامه‌های کاربردی در نظر گرفته شده است. به
 * عنوان نمونه اگر کاربر بخواهد برنامه کاربردی پیش فرض برای یک ملک را اجرا کند
 * در این کلاس برای آن فراخوانی در نظر گرفته شده است.
 *
 * اکثر فراخوانی‌هایی که در این لایه نمایش ایجاد شده در پرونده urls-app2.php به کار
 * گرفته شده است.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Views_SPA
{

    /**
     * جستجوی نرم‌افزارهای کاربردی
     *
     * این فراخوانی برای جستجو تمام نرم افزارهای کاربردی موجود در سیستم به کار گرفته
     * می‌شود. این فراخوانی برای استفاده تمام کاربران آزاد است و کاربران می‌توانند از
     * بین تمام نرم افزارهای موجود نرم افزارهای مورد نظر خود را پیدا کنند.
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public static function find($request, $match)
    {
        $pag = new Pluf_Paginator(new SaaS_SPA());
        $list_display = array(
            'id' => 'spa id',
            'title' => 'title',
            'creation_dtime' => 'creation time'
        );
        $search_fields = array();
        $sort_fields = array(
            'creation_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->action = array();
        $pag->items_per_page = 10;
        $pag->sort_order = array(
            'creation_dtime',
            'DESC'
        );
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * یک نرم افزار جدید را در سیستم ایجاد میکند.
     *
     * این فراخوانی موظف است که یک نرم افزار جدید در سیستم ایجاد کند.
     *
     * @note در حال حاضر این امکان فراهم نشده و نرم فزارها باید به صورت دستی ایجاد شوند.
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public static function create($request, $match)
    {}

    /**
     * اطلاعات یک spa
     *
     * این فراخوانی اطلاعات یک spa را در اختیار کاربران قرار می‌دهد. در حالت عادی دسترسی به
     * اطلاعات نرم‌افزارها به دسترسی‌های خاصی نیاز ندارد و هر کاربری قادر است که به آنها دسترسی
     * داشته باشد.
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public static function get($request, $match)
    {
        $spa = SaaS_Shortcuts_GetSPAOr404($match[1]);
        return new Pluf_HTTP_Response_Json($spa);
    }

    /**
     * اطلاعات یک نرم افزار را به روز می‌کند.
     *
     * اطلاعاتی که از نرم افزار در پایگاه داده نگهداری می‌شود بر اساس اطلاعاتی که در خود نرم افزار
     * وجود دارد به روز خواهد شد.
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public static function refresh($request, $match)
    {
        $spa = SaaS_Shortcuts_GetSPAOr404($match[1]);
        // TODO: maso, 1395: به روز کردن بسته
        return new Pluf_HTTP_Response_Json($spa);
    }

    /**
     * به روز کردن تمام نرم‌افزارها
     *
     * تمام نرم افزارهایی که در سیستم نصب شده است را به روز رسانی می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public static function refreshAll($request, $match)
    {
        SaaS_Migrations_Update_spa();
        SaaS_Migrations_Update_lib();
        // XXX: maso, 1395: مدل کلی انجام پردازش‌های سرور نیز تعیین شود.
        return new Pluf_HTTP_Response_Json(array(
            'id' => '0',
            'progress' => array(
                'title' => 'updating spas',
                'totalWork' => 1,
                'done' => true
            )
        ));
    }

    /**
     * مدیریت منابع برای HTML5
     *
     * توی HTML5 ابزارهایی برای اجرای سایت‌ها به صورت افلاین وجود دارد. این ابزارها
     * فهرست منابع را از سرور تهیه می‌کنند. این فراخوانی بر اساس تنظیم‌های پروژه فهرست
     * منابع را برای کاربران ایجاد می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response
     */
    public static function appcache($request, $match)
    {
        $spa = SaaS_Shortcuts_GetSPAOr404($match[1]);
        $package = $spa->loadPackage();
        list ($jsLib, $cssLib, $libs) = SaaS_Views_SPA::loadLibrary($package);
        
        // نمایش اصلی
        $params = array(
            'spa' => $spa,
            'title' => 'Pluf PaaS',
            'jsLibs' => $jsLib,
            'cssLibs' => $cssLib,
            'package' => $package
        );
        return Pluf_Shortcuts_RenderToResponse('saas.appcache', $params, $request);
    }

    /**
     * اطلاعات پیاده سازی بسته را تعیین می‌کند
     *
     * هر نرم افزار بر اساس یک پرونده spa.json ایجاد می‌شود. این فراخوانی امکان دسترسی به
     * محتوی spa.json را برای کاربران فراهم می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public static function package($request, $match)
    {
        $spa = SaaS_Shortcuts_GetSPAOr404($match[1]);
        $package = $spa->loadPackage();
        return new Pluf_HTTP_Response_Json($package);
    }

    /**
     * ********************************Deprecated********************************************
     */
    public function tenantSpaById($request, $match)
    {
        // TODO: maso, 1394: Redirect if there is domain
        $app = $request->tenant;
        $spa = new SaaS_SPA($match[1]);
        
        // Check access
        SaaS_Precondition::userCanAccessApplication($request, $app);
        SaaS_Precondition::userCanAccessSpa($request, $spa);
        
        // نمایش اصلی
        return $this->loadSpa($request, $app, $spa);
    }

    public function main($request, $match)
    {
        $app = $request->tenant;
        if ($app->spa != 0)
            $spa = $app->get_spa();
        else {
            $spa = SaaS_SPA::getByName(Pluf::f('saas_spa_default', 'main'));
            return $this->loadSpa($request, $app, $spa);
        }
        
        // Check access
        SaaS_Precondition::userCanAccessApplication($request, $app);
        SaaS_Precondition::userCanAccessSpa($request, $spa);
        
        return $this->loadSpa($request, $app, $spa);
    }

    public function spa($request, $match)
    {
        $app = $request->tenant;
        $spa = SaaS_SPA::getByName($match[1]);
        
        // Check access
        SaaS_Precondition::userCanAccessApplication($request, $app);
        SaaS_Precondition::userCanAccessSpa($request, $spa);
        
        // نمایش اصلی
        return $this->loadSpa($request, $app, $spa);
    }

    public function source($request, $match)
    {
        $spa = new SaaS_SPA();
        $spa = $spa->getOne(array(
            'filter' => "name='" . $match[1] . "'"
        ));
        $repo = Pluf::f('saas_spa_repository');
        
        // TODO: Check access (No Tentant)
        // SaaS_Precondition::userCanAccessSpa($request, $spa);
        
        // Do
        return $this->loadSource($request, $spa, $match[2]);
    }

    public function assets($request, $match)
    {
        // Load data
        // Check access
        // DO
        return $this->loadAssets($request, $match[1]);
    }

    static function loadLibrary($package)
    {
        // کتابخانه‌ها
        $cssLib = array();
        $jsLib = array();
        $libs = array();
        $mlib = new SaaS_Lib();
        foreach ($package['dependencies'] as $n => $v) {
            $sql = new Pluf_SQL('name=%s', array(
                $n
            ));
            $items = $mlib->getList(array(
                'filter' => $sql->gen()
            ));
            if ($items->count() == 0) {
                throw new Pluf_Exception('library ' . $n . ' does not exit.');
            }
            $libs[] = $items[0];
            if ($items[0]->type == SaaS_LibType::JavaScript)
                $jsLib[] = $items[0];
            else
                $cssLib[] = $items[0];
        }
        return array(
            $jsLib,
            $cssLib,
            $libs
        );
    }

    function loadSource($request, $spa, $name)
    {
        $p = $spa->getSourcePath($name);
        return new Pluf_HTTP_Response_File($p, SaaS_FileUtil::getMimeType($p));
    }

    function loadAssets($request, $name)
    {
        $p = SaaS_SPA::getAssetsPath($name);
        return new Pluf_HTTP_Response_File($p, SaaS_FileUtil::getMimeType($p));
    }

    protected function loadSpa($request, $app, $spa)
    {
        $package = $spa->loadPackage();
        list ($jsLib, $cssLib, $libs) = SaaS_Views_SPA::loadLibrary($package);
        
        // نمایش اصلی
        $params = array(
            'spa' => $spa,
            'app' => $app,
            'title' => __('ghazal'),
            'mainView' => $spa->getMainViewPath(),
            'index' => $spa->getIndexPath(),
            'jsLibs' => $jsLib,
            'cssLibs' => $cssLib,
            'package' => $package,
            'base' => $request->query
        );
        return Pluf_Shortcuts_RenderToResponse('spa.template', $params, $request);
    }
}