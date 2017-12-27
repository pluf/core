<?php

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Pluf_Tenant extends Pluf_Model
{

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
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => true,
                'editable' => false
            ),
            'level' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'blank' => true,
                'editable' => false
            ),
            'title' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 100
            ),
            'description' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 250,
                'editable' => true
            ),
            'domain' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'is_null' => true,
                'unique' => true,
                'size' => 63,
                'editable' => true
            ),
            'subdomain' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'is_null' => false,
                'unique' => true,
                'size' => 63,
                'editable' => true
            ),
            'validate' => array(
                'type' => 'Pluf_DB_Field_Boolean',
                'default' => false,
                'blank' => true,
                'editable' => false
            ),
            'creation_dtime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => true,
                'editable' => false
            ),
            'modif_dtime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => true,
                'editable' => false
            )
        );
        
        // @Note: hadi - 1396-10: when define an attribute as unique => true, pluf automatically
        // create an unique index for it (for example domain and subdomain fields). So following codes are extra.
//         $this->_a['idx'] = array(
//             'tenant_domain_idx' => array(
//                 'col' => 'domain',
//                 'type' => 'unique', // normal, unique, fulltext, spatial
//                 'index_type' => '', // hash, btree
//                 'index_option' => '',
//                 'algorithm_option' => '',
//                 'lock_option' => ''
//             ),
//             'tenant_subdomain_idx' => array(
//                 'col' => 'subdomain',
//                 'type' => 'unique', // normal, unique, fulltext, spatial
//                 'index_type' => '', // hash, btree
//                 'index_option' => '',
//                 'algorithm_option' => '',
//                 'lock_option' => ''
//             )
//         );
        
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
     * @return Pluf_Tenant
     */
    public static function bySubDomain($subdomain)
    {
        $sql = new Pluf_SQL('subdomain=%s', $subdomain);
        return Pluf::factory('Pluf_Tenant')->getOne($sql->gen());
    }

    /**
     * ملک با دامنه تعیین شده را برمی‌گرداند.
     *
     * @param string $domain
     */
    public static function byDomain($domain)
    {
        $sql = new Pluf_SQL('domain=%s', $domain);
        $result = Pluf::factory('Pluf_Tenant')->getOne($sql->gen());
        return $result;
    }

    /**
     * Gets current tenant
     *
     * @return Pluf_Tenant
     */
    public static function current()
    {
        if (! Pluf::f('multitenant', false)) {
            $tenant = new Pluf_Tenant();
            $tenant->setFromFormData(Pluf::f('multitenant_default', array(
                'level' => 10,
                'title' => 'Tenant title',
                'description' => 'Default tenant in single mode',
                'domain' => 'pluf.ir',
                'subdomain' => 'www',
                'validate' => 1
            )));
            $tenant->id = 0;
            return $tenant;
        }
        // load tenant from request
        return $GLOBALS['_PX_request']->tenant;
    }

    /**
     * Gets tenant storage path
     *
     * @return string
     */
    public static function storagePath()
    {
        $tenant = self::current();
        if ($tenant->isAnonymous()) {
            return Pluf::f('upload_path');
        }
        return Pluf::f('upload_path') . '/' . $tenant->id;
    }
}