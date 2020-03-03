<?php
namespace Pluf;

/**
 * Data model of a tenant
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class Tenant extends Model
{

    /**
     * Current tenant
     *
     * @var Tenant
     */
    static $currentTenant = NULL;

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
     * @see Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'tenants';
        $this->_a['multitenant'] = false;
        $this->_a['cols'] = array(
            'id' => array(
                'type' => '\Pluf\DB\Field\Sequence',
                'blank' => true,
                'editable' => false
            ),
            'level' => array(
                'type' => '\Pluf\DB\Field\Integer',
                'blank' => true,
                'editable' => false
            ),
            'title' => array(
                'type' => '\Pluf\DB\Field\Varchar',
                'blank' => true,
                'size' => 100
            ),
            'description' => array(
                'type' => '\Pluf\DB\Field\Varchar',
                'blank' => true,
                'is_null' => true,
                'size' => 1024,
                'editable' => true
            ),
            'domain' => array(
                'type' => '\Pluf\DB\Field\Varchar',
                'blank' => true,
                'is_null' => true,
                'unique' => true,
                'size' => 63,
                'editable' => true
            ),
            'subdomain' => array(
                'type' => '\Pluf\DB\Field\Varchar',
                'blank' => false,
                'is_null' => false,
                'unique' => true,
                'size' => 63,
                'editable' => true
            ),
            'validate' => array(
                'type' => '\Pluf\DB\Field\Boolean',
                'default' => false,
                'blank' => true,
                'editable' => false
            ),
            'email' => array(
                'type' => '\\Pluf\\DB\\Field\\Email',
                'blank' => true,
                'verbose' => 'Owner email',
                'editable' => true,
                'readable' => true
            ),
            'phone' => array(
                'type' => '\Pluf\DB\Field\Varchar',
                'blank' => true,
                'verbose' => 'Owner phone',
                'editable' => true,
                'readable' => true
            ),
            'creation_dtime' => array(
                'type' => '\Pluf\DB\Field\Datetime',
                'blank' => true,
                'editable' => false
            ),
            'modif_dtime' => array(
                'type' => '\Pluf\DB\Field\Datetime',
                'blank' => true,
                'editable' => false
            ),
            /*
             * Relations
             */
            'parent_id' => array(
                'type' => '\Pluf\DB\Field\Foreignkey',
                'model' => 'Pluf_Tenant',
                'blank' => true,
                'name' => 'parent',
                'graphql_name' => 'parent',
                'relate_name' => 'children',
                'editable' => true,
                'readable' => true
            )
        );
        $this->_a['views'] = array();
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
        $sql = new SQL('subdomain=%s', $subdomain);
        return Bootstrap::factory('Pluf_Tenant')->getOne($sql->gen());
    }

    /**
     * ملک با دامنه تعیین شده را برمی‌گرداند.
     *
     * @param string $domain
     */
    public static function byDomain($domain)
    {
        $sql = new SQL('domain=%s', $domain);
        $result = Bootstrap::factory('Pluf_Tenant')->getOne($sql->gen());
        return $result;
    }

    public static function setCurrent($tenant)
    {
        self::$currentTenant = $tenant;
    }

    /**
     * Gets current tenant
     *
     * @return Tenant
     */
    public static function current()
    {
        //----------------------------------------------------
        // Single tenant
        //----------------------------------------------------
        if (! Bootstrap::f('tenant_multi_enable', false)) {
            $tenant = new Tenant();
            $tenant->setFromFormData(Bootstrap::pf('tenant_root_', array(
                'level' => 10,
                'title' => 'Tenant title',
                'description' => 'Default tenant in single mode',
                'domain' => 'pluf.ir',
                'subdomain' => 'www',
                'validate' => 1
            ), true));
            $tenant->id = 0;
            return $tenant;
        }
        //----------------------------------------------------
        // Multi tenant
        //----------------------------------------------------
        if (array_key_exists('_PX_request', $GLOBALS)) {
            // load tenant from request
            return $GLOBALS['_PX_request']->tenant;
        }
        if (isset(self::$currentTenant)) {
            return self::currentTenant;
        }
        throw new Exception('No tenant loaded');
    }

    /**
     * Gets tenant storage path
     *
     * @return string
     */
    public static function storagePath()
    {
        return self::current()->getStoragePath();
    }

    /**
     * Gets tenant storage path
     *
     * @return string of the storage
     */
    public function getStoragePath()
    {
        if ($this->isAnonymous()) {
            return Bootstrap::f('upload_path');
        }
        return Bootstrap::f('upload_path') . '/' . $this->id;
    }
}
