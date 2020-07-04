<?php
namespace Pluf\Pluf;

use Pluf\Data\Query;
use Pluf\Data\Schema;
use Pluf\HTTP\Request;
use Pluf;
use Pluf_Model;

/**
 * Data model of a tenant
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class Tenant extends Pluf_Model
{

    /**
     * Current tenant
     *
     * @var Tenant
     */
    static ?Tenant $currentTenant = NULL;

    /**
     * یک نگاشت به صورت کلید-مقدار که تنظیمات ملک را نگه می دارد.
     * تنظیمات ملک خصوصیاتی است که توسط مالک ملک قابل تعیین است
     *
     * @var array $setting
     */
    protected $settingData = array();

    protected $settingChanged = false;

    /**
     * یک نگاشت به صورت کلید-مقدار که پیکره‌بندی ملک را نگه می دارد.
     * پیکره بندی ملک خصوصیاتی است که توسط ادمین کل پلتفورم قابل تعیین است
     *
     * @var array $configData
     */
    protected $configData = array();

    protected $configChanged = false;

    /**
     *
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'tenants';
        $this->_a['multitenant'] = false;
        $this->_a['cols'] = array(
            'id' => array(
                'type' => Schema::SEQUENCE,
                'blank' => true,
                'editable' => false
            ),
            'level' => array(
                'type' => 'Integer',
                'blank' => true,
                'default' => 0,
                'editable' => false
            ),
            'title' => array(
                'type' => 'Varchar',
                'blank' => true,
                'size' => 100
            ),
            'description' => array(
                'type' => 'Varchar',
                'blank' => true,
                'is_null' => true,
                'size' => 1024,
                'editable' => true
            ),
            'domain' => array(
                'type' => 'Varchar',
                'blank' => true,
                'is_null' => true,
                'unique' => true,
                'size' => 63,
                'editable' => true
            ),
            'subdomain' => array(
                'type' => 'Varchar',
                'is_null' => false,
                'unique' => true,
                'size' => 63,
                'editable' => true
            ),
            'validate' => array(
                'type' => 'Boolean',
                'default' => false,
                'is_null' => true,
                'editable' => false
            ),
            'email' => array(
                'type' => 'Email',
                'is_null' => true,
                'verbose' => 'Owner email',
                'editable' => true,
                'readable' => true
            ),
            'phone' => array(
                'type' => 'Varchar',
                'blank' => true,
                'verbose' => 'Owner phone',
                'editable' => true,
                'readable' => true
            ),
            'creation_dtime' => array(
                'type' => 'Datetime',
                'blank' => true,
                'editable' => false
            ),
            'modif_dtime' => array(
                'type' => 'Datetime',
                'blank' => true,
                'editable' => false
            ),
            /*
             * Relations
             */
            'parent_id' => array(
                'type' => 'Foreignkey',
                'model' => Tenant::class,
                'blank' => true,
                // 'name' => 'parent', // Used to override columen name
                'graphql_name' => 'parent',
                'relate_name' => 'children',
                'editable' => true,
                'readable' => true
            )
        );
    }

    /**
     * \brief پیش ذخیره را انجام می‌دهد
     *
     * @param boolean $create
     *            حالت
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }

    /**
     * ملک تعیین شده با زیردامنه تعیین شده را برمی‌گرداند
     *
     * @param string $subdomain
     * @return Tenant
     */
    public static function bySubDomain($subdomain)
    {
        $list = Pluf::getDataRepository(Tenant::class)->get(new Query([
            'filter' => [
                [
                    'subdomain',
                    '=',
                    $subdomain
                ]
            ]
        ]));
        if (sizeof($list) > 0) {
            return $list[0];
        }
        return NULL;
    }

    /**
     * ملک با دامنه تعیین شده را برمی‌گرداند.
     *
     * @param string $domain
     */
    public static function byDomain($domain)
    {
        $repo = Pluf::getDataRepository(Tenant::class);
        $list = $repo->get(new Query([
            'filter' => [
                [
                    'domain',
                    '=',
                    $domain
                ]
            ]
        ]));
        if (sizeof($list) > 0) {
            return $list[0];
        }
        return NULL;
    }

    public static function setCurrent(?Tenant $tenant = null): void
    {
        self::$currentTenant = $tenant;
        // Change current request tenant
        $request = Request::getCurrent();
        if (isset($request)) {
            $request->setTenant($tenant);
        }
    }

    /**
     *
     * @deprecated See Tenant::getCurrent()
     * @return Tenant current tentant
     */
    public static function current(): ?Tenant
    {
        return self::getCurrent();
    }

    /**
     * Gets current tenant
     *
     * @return Tenant
     */
    public static function getCurrent(): ?Tenant
    {
        // check current value
        if (isset(self::$currentTenant)) {
            return self::$currentTenant;
        }

        // create for single tinant
        if (! Pluf::f('multitenant', false)) {
            $tenant = new Tenant();
            $tenant->setFromFormData(Pluf::f('multitenant_default', array(
                'level' => 10,
                'title' => 'Tenant title',
                'description' => 'Default tenant in single mode',
                'domain' => 'pluf.ir',
                'subdomain' => 'www',
                'validate' => 1
            )));
            $tenant->id = 0;
            return self::$currentTenant = $tenant;
        }

        // fetch from request
        $request = Request::getCurrent();
        if (isset($request)) {
            return self::$currentTenant = $request->tenant;
        }
        return null;
    }

    /**
     * Gets tenant storage path
     *
     * @return string
     */
    public static function storagePath()
    {
        return self::getCurrent()->getStoragePath();
    }

    /**
     * Gets tenant storage path
     *
     * @return string path of the storage
     */
    public function getStoragePath()
    {
        if ($this->isAnonymous()) {
            return Pluf::f('upload_path');
        }
        return Pluf::f('upload_path') . '/' . $this->id;
    }
}
