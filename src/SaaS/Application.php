<?php

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaS_Application extends Pluf_Model
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
        $this->_a['table'] = 'saas_application';
        $this->_a['model'] = 'SaaS_Application';
        $this->_model = 'SaaS_Application';
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
                'access_count' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'editable' => false
                ),
                'validate' => array(
                        'type' => 'Pluf_DB_Field_Boolean',
                        'default' => false,
                        'blank' => true,
                        'editable' => false
                ),
                'title' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 100
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
                'description' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 250,
                        'editable' => true
                ),
                'setting' => array(
                        'type' => 'Pluf_DB_Field_Text',
                        'blank' => true,
                        'verbose' => 'Setting',
                        'help_text' => 'Settings are editable by owner',
                        'editable' => false,
                        'readable' => false
                ),
                'config' => array(
                        'type' => 'Pluf_DB_Field_Text',
                        'blank' => true,
                        'verbose' => 'Configuration',
                        'help_text' => 'Configuration used by system and administrator',
                        'editable' => false,
                        'readable' => false
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
                ),
                'spa' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'SaaS_SPA',
                        'blank' => true,
                        'editable' => true
                )
        );
        $this->_a['views'] = array(
                'user_model_permission' => array(
                        'join' => 'LEFT JOIN ' . $this->_con->pfx .
                                 'rowpermissions ON saas_application.id=' .
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
        // encode settingData into setting
        if ($this->settingChanged)
            $this->setting = serialize($this->settingData);
            
            // encode configData into config
        if ($this->configChanged)
            $this->config = serialize($this->configData);
        
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }

    /**
     * Restore function to decode the setting and config into $this->settingData
     * and $this->configData.
     */
    function restore ()
    {
        $this->settingData = unserialize($this->setting);
        $this->configData = unserialize($this->config);
    }

    function preDelete ()
    {
        // @unlink(Pluf::f('upload_issue_path').'/'.$this->attachment);
    }

    /**
     * حالت کار ایجاد شده را به روز می‌کند
     *
     * @see Pluf_Model::postSave()
     */
    function postSave ($create = false)
    {
        //
    }

    /**
     * تعیین یک داده در تنظیمات ملک
     *
     * با استفاده از این فراخوانی می‌توان در تنظیمات ملک، برای مشخصه تعیین شده
     * با کلید key
     * یک مقدار تعیین کرد. این کلید برای دستیابی‌های بعد مورد استفاده قرار خواهد
     * گرفت.
     *
     * @param $key کلید
     *            داده
     * @param $value داده
     *            مورد نظر. در صورتی که مقدار آن تهی باشد به معنی
     *            حذف است.
     */
    function setSetting ($key, $value = null)
    {
        if (is_null($value)) {
            unset($this->settingData[$key]);
        } else {
            $this->settingData[$key] = $value;
        }
        $this->settingChanged = true;
    }

    /**
     * داده معادل با کلید تعیین شده را از تنظیمات ملک برمی‌گرداند
     *
     * در صورتی که داده تعیین نشده بود مقدار پیش فرض تعیین شده به عنوان نتیجه
     * این فراخوانی برگردانده خواهد شد.
     */
    function getSetting ($key = null, $default = '')
    {
        // if (is_null($key)) {
        // return parent::getData();
        // }
        if (isset($this->settingData[$key])) {
            return $this->settingData[$key];
        } else {
            return $default;
        }
    }

    /**
     * تعیین یک داده در پیکره‌بندی ملک
     *
     * با استفاده از این فراخوانی می‌توان در پیکره‌بندی ملک، برای مشخصه تعیین
     * شده با کلید key
     * یک مقدار تعیین کرد. این کلید برای دستیابی‌های بعد مورد استفاده قرار خواهد
     * گرفت.
     *
     * @param $key کلید
     *            داده
     * @param $value داده
     *            مورد نظر. در صورتی که مقدار آن تهی باشد به معنی
     *            حذف است.
     */
    function setConfig ($key, $value = null)
    {
        if (is_null($value)) {
            unset($this->configData[$key]);
        } else {
            $this->configData[$key] = $value;
        }
        $this->configChanged = true;
    }

    /**
     * داده معادل با کلید تعیین شده را از پیکره‌بندی ملک برمی‌گرداند
     *
     * در صورتی که داده تعیین نشده بود مقدار پیش فرض تعیین شده به عنوان نتیجه
     * این فراخوانی برگردانده خواهد شد.
     */
    function getConfig ($key = null, $default = '')
    {
        // if (is_null($key)) {
        // return parent::getData();
        // }
        if (isset($this->configData[$key])) {
            return $this->configData[$key];
        } else {
            return $default;
        }
    }

    /**
     * تمام داده‌های موجود در تنظیمات ملک را پاک می‌کند.
     */
    function clearSetting ()
    {
        $this->settingData = array();
        $this->settingChanged = true;
    }

    /**
     * تمام داده‌های موجود در پیکره‌بندی ملک را پاک می‌کند.
     */
    function clearConfig ()
    {
        $this->configData = array();
        $this->configChanged = true;
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
                                'SaaS_Application',
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
                                'SaaS_Application',
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
                                    'SaaS_Application',
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
        return Pluf::factory('SaaS_Application')->getOne($sql->gen());
    }

    /**
     * ملک با دامنه تعیین شده را برمی‌گرداند.
     * 
     * @param unknown $domain            
     */
    public static function byDomain ($domain)
    {
        $sql = new Pluf_SQL('domain=%s', $domain);
        $result = Pluf::factory('SaaS_Application')->getOne($sql->gen());
        return $result;
    }
}