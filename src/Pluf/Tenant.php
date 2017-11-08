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
        $this->_a['table'] = 'pluf_tenant';
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
        
        $this->_a['idx'] = array(
            'tenant_domain_idx' => array(
                'col' => 'domain',
                'type' => 'unique', // normal, unique, fulltext, spatial
                'index_type' => '', // hash, btree
                'index_option' => '',
                'algorithm_option' => '',
                'lock_option' => ''
            ),
            'tenant_subdomain_idx' => array(
                'col' => 'subdomain',
                'type' => 'unique', // normal, unique, fulltext, spatial
                'index_type' => '', // hash, btree
                'index_option' => '',
                'algorithm_option' => '',
                'lock_option' => ''
            )
        );
        
        $this->_a['views'] = array(
            'user_model_permission' => array(
                'join' => 'LEFT JOIN ' . $this->_con->pfx . 'rowpermissions ON Pluf_Tenant.id=' . $this->_con->pfx . 'rowpermissions.model_id',
                'select' => $this->getSelect() . ', permission',
                'props' => array(
                    'permission' => 'permission'
                )
            )
        );
    }

    /**
     * \brief پیش ذخیره را انجام می‌دهد
     *
     * @param boolean $create حالت
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
     * Cache of the permissions.
     */
    public $_cache_perms = null;

    /**
     * تمام دسترسی‌هایی که یک مدل داده‌ای دارد را تعیین می‌کند.
     *
     * @param
     *            bool Force the reload of the list of permissions (false)
     * @return array List of permissions
     */
    function getAllPermissions($force = false)
    {
        if ($force == false and ! is_null($this->_cache_perms)) {
            return $this->_cache_perms;
        }
        $this->_cache_perms = array();
        if (Pluf::f('pluf_use_rowpermission', false) and $this->id) {
            $growp = new Pluf_RowPermission();
            $sql = new Pluf_SQL('owner_id=%s AND owner_class=%s', array(
                $this->id,
                $this->_model
            ));
            $perms = $growp->getList(array(
                'filter' => $sql->gen(),
                'view' => 'join_permission'
            ));
            foreach ($perms as $perm) {
                $perm_string = $perm->application . '.' . $perm->code_name . '#' . $perm->model_class . '(' . $perm->model_id . ')';
                if ($perm->negative) {
                    $perm_string = '!' . $perm_string;
                }
                if (! in_array($perm_string, $this->_cache_perms)) {
                    $this->_cache_perms[] = $perm_string;
                }
            }
        }
        return $this->_cache_perms;
    }

    /**
     * تعیین گواهی برای شئی تعیین شده
     *
     * @param
     *            string Permission
     * @param
     *            Object Object for row level permission (null)
     * @return bool درستی اگر کاربر گواهی مورد نظر برای شئی را دارد.
     */
    function hasPerm($perm, $obj = null)
    {
        $perms = $this->getAllPermissions();
        if (! is_null($obj)) {
            $perm_row = $perm . '#' . $obj->_a['model'] . '(' . $obj->id . ')';
            if (in_array('!' . $perm_row, $perms))
                return false;
            if (in_array($perm_row, $perms))
                return true;
        }
        if (in_array($perm, $perms))
            return true;
        return false;
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