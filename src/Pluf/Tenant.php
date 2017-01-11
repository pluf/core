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
    function init ()
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
                        'unique' => true,
                        'size' => 63,
                        'editable' => true
                ),
                'subdomain' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'unique' => true,
                        'size' => 63,
                        'editable' => false
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
        $this->_a['views'] = array(
                'user_model_permission' => array(
                        'join' => 'LEFT JOIN ' . $this->_con->pfx .
                                 'rowpermissions ON Pluf_Tenant.id=' .
                                 $this->_con->pfx . 'rowpermissions.model_id',
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
     * @param $create حالت
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave ($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }

    /**
     * داده‌های مربوط به اعضای یک نرم‌افزار را تعیین می‌کند
     *
     * نتیجه این فراخوانی یک آرایه با سه کلید است: 'members'، 'owners' و
     * 'authorized'.
     *
     * این ارائه بر اساس داده‌های که در جدول اجازها به وجود آمده است تعیینن
     * می‌شوند.
     * به این نکته توجه داشته باشید در صورتی که کاربر مدیر کلی سیستم باشد
     * دسترسی‌های کلی به تمام نرم‌افزارها را دارد اما در این فهرست آورده
     * نمی‌شود.
     *
     * @param
     *            string Format ('objects'), 'string'.
     * @return mixed Array of Pluf_User or newline separated list of logins.
     */
    public function getMembershipData ($fmt = 'objects')
    {
        $mperm = Pluf_Permission::getFromString('SaaS.software-member');
        $operm = Pluf_Permission::getFromString('SaaS.software-owner');
        $aperm = Pluf_Permission::getFromString('SaaS.software-authorized-user');
        $grow = new Pluf_RowPermission();
        $db = & Pluf::db();
        $false = Pluf_DB_BooleanToDb(false, $db);
        $sql = new Pluf_SQL(
                'model_class=%s AND model_id=%s AND owner_class=%s AND permission=%s AND negative=' .
                         $false, 
                        array(
                                'Pluf_Tenant',
                                $this->id,
                                'Pluf_User',
                                $operm->id
                        ));
        $owners = new Pluf_Template_ContextVars(array());
        foreach ($grow->getList(
                array(
                        'filter' => $sql->gen()
                )) as $row) {
            if ($fmt == 'objects') {
                $owners[] = Pluf::factory('Pluf_User', $row->owner_id);
            } else {
                $owners[] = Pluf::factory('Pluf_User', $row->owner_id)->login;
            }
        }
        $sql = new Pluf_SQL(
                'model_class=%s AND model_id=%s AND owner_class=%s AND permission=%s AND negative=' .
                         $false, 
                        array(
                                'Pluf_Tenant',
                                $this->id,
                                'Pluf_User',
                                $mperm->id
                        ));
        $members = new Pluf_Template_ContextVars(array());
        foreach ($grow->getList(
                array(
                        'filter' => $sql->gen()
                )) as $row) {
            if ($fmt == 'objects') {
                $members[] = Pluf::factory('Pluf_User', $row->owner_id);
            } else {
                $members[] = Pluf::factory('Pluf_User', $row->owner_id)->login;
            }
        }
        $authorized = new Pluf_Template_ContextVars(array());
        if ($aperm != false) {
            $sql = new Pluf_SQL(
                    'model_class=%s AND model_id=%s AND owner_class=%s AND permission=%s AND negative=' .
                             $false, 
                            array(
                                    'Pluf_Tenant',
                                    $this->id,
                                    'Pluf_User',
                                    $aperm->id
                            ));
            foreach ($grow->getList(
                    array(
                            'filter' => $sql->gen()
                    )) as $row) {
                if ($fmt == 'objects') {
                    $authorized[] = Pluf::factory('Pluf_User', $row->owner_id);
                } else {
                    $authorized[] = Pluf::factory('Pluf_User', $row->owner_id)->login;
                }
            }
        }
        if ($fmt == 'objects') {
            return new Pluf_Template_ContextVars(
                    array(
                            'members' => $members,
                            'owners' => $owners,
                            'authorized' => $authorized
                    ));
        } else {
            return array(
                    'members' => $members,
                    'owners' => $owners,
                    'authorized' => $authorized
            );
        }
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
    function getAllPermissions ($force = false)
    {
        if ($force == false and ! is_null($this->_cache_perms)) {
            return $this->_cache_perms;
        }
        $this->_cache_perms = array();
        if (Pluf::f('pluf_use_rowpermission', false) and $this->id) {
            $growp = new Pluf_RowPermission();
            $sql = new Pluf_SQL('owner_id=%s AND owner_class=%s', 
                    array(
                            $this->id,
                            $this->_model
                    ));
            $perms = $growp->getList(
                    array(
                            'filter' => $sql->gen(),
                            'view' => 'join_permission'
                    ));
            foreach ($perms as $perm) {
                $perm_string = $perm->application . '.' . $perm->code_name . '#' .
                         $perm->model_class . '(' . $perm->model_id . ')';
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
    function hasPerm ($perm, $obj = null)
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
     */
    public static function bySubDomain ($subdomain)
    {
        $sql = new Pluf_SQL('subdomain=%s', $subdomain);
        return Pluf::factory('Pluf_Tenant')->getOne($sql->gen());
    }

    /**
     * ملک با دامنه تعیین شده را برمی‌گرداند.
     * 
     * @param unknown $domain            
     */
    public static function byDomain ($domain)
    {
        $sql = new Pluf_SQL('domain=%s', $domain);
        $result = Pluf::factory('Pluf_Tenant')->getOne($sql->gen());
        return $result;
    }
    
    /**
     * Gets current tenant
     * 
     * @return unknown
     */
    public static function current()
    {
        if(!Pluf::f('multitenant', false)){
            return Pluf::factory('Pluf_Tenant');
        }
        // load tenant from request
        return $GLOBALS ['_PX_request']->tenant;
    }
}