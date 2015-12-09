<?php

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaS_Application extends Pluf_Model
{

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
                        'blank' => true
                ),
                'level' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'unique' => false
                ),
                'access_count' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'unique' => false
                ),
                'validate' => array(
                        'type' => 'Pluf_DB_Field_Boolean',
                        'default' => false,
                        'blank' => true,
                        'verbose' => __('validate')
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
                        'size' => 50
                ),
                'subdomain' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'unique' => true,
                        'size' => 50
                ),
                'description' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 250
                ),
                'logo' => array(
                        'type' => 'Pluf_DB_Field_File',
                        'blank' => true,
                        'verbose' => __('logo')
                ),
                'background' => array(
                        'type' => 'Pluf_DB_Field_File',
                        'blank' => true,
                        'verbose' => __('background image')
                ),
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true
                ),
                'modif_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true
                ),
                'spa' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'SaaS_SPA',
                        'blank' => true,
                        'verbose' => __('Default SAP for this application')
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
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
            
            // $file = Pluf::f('upload_issue_path').'/'.$this->attachment;
            // $this->filesize = filesize($file);
            // // remove .dummy
            // $this->filename = substr(basename($file), 0, -6);
            // $img_extensions = array('jpeg', 'jpg', 'png', 'gif');
            // $info = pathinfo($this->filename);
            // if (!isset($info['extension'])) $info['extension'] = '';
            // if (in_array(strtolower($info['extension']), $img_extensions)) {
            // $this->type = 'img';
            // } else {
            // $this->type = 'other';
            // }
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
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
     * دسترسی به تنظیم های عمومی
     *
     * @param unknown $name            
     * @param string $default            
     * @return string
     */
    public function getProperty ($name, $default = null)
    {
        // XXX: maso, 1394: دسترسی به تنظیم‌های عمومی
        return $default;
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
     *
     * @param unknown $shortname            
     * @throws Pluf_HTTP_Error404
     * @return unknown
     */
    public function getConfigurationList (
            $types = array(SaaS_ConfigurationType::GENERAL), $access = array())
    {
        $sql = new Pluf_SQL('application=%s ', 
                array(
                        $this->id
                ));
        if (sizeof($types) >= 1) {
            $typSql = new Pluf_SQL();
            foreach ($types as $key => $value) {
                $typSql = $typSql->SOr(
                        new Pluf_SQL('type=%s ', 
                                array(
                                        $value
                                )));
            }
            $sql = $sql->SAnd($typSql);
        }
        if (sizeof($access) >= 1) {
            $typSql = new Pluf_SQL();
            foreach ($access as $key => $value) {
                $typSql = $typSql->SAnd(
                        new Pluf_SQL($key . '=%s ', 
                                array(
                                        $value
                                )));
            }
            $sql = $sql->SAnd($typSql);
        }
        $configs = Pluf::factory('SaaS_Configuration')->getList(
                array(
                        'filter' => $sql->gen(),
                        'view' => 'list'
                ));
        return $configs;
    }

    /**
     * تنظیم‌تعیین شده با کلید را تعیین می‌کند.
     *
     * در صورتی که تنظیم مورد نظر در سیستم تعریف نشده باشد یک خطا
     * تولید می‌شود.
     *
     * @param unknown $key            
     * @param string $default            
     * @throws Pluf_Exception در صورتی که تنظیم‌تعیین شده وجود نداشته باشد.
     * @return تنظیم‌های مورد نظر
     */
    public function getConfiguration ($key, $default = null)
    {
        $sql = new Pluf_SQL(
                'saas_configuration.application=%s AND saas_configuration.key=%s', 
                array(
                        $this->id,
                        $key
                ));
        $config = Pluf::factory('SaaS_Configuration');
        $configs = $config->getList(
                array(
                        'filter' => $sql->gen()
                ));
        if ($configs->count() < 1) {
            throw new Pluf_Exception();
        }
        return $configs[0];
    }

    /**
     * تنظیم با کلید تعیین شده را بر می‌گرداند
     *
     * در صورتی که تنظیم‌ها با کلید تعیین شده وجود نداشته باشد تنظیم پیش فرض
     * ایجاد شده به عنوان نتیجه برگردانده می‌شود.
     *
     * @param unknown $key            
     * @return Ambigous <string, SaaS_Configuration>
     */
    public function fetchConfiguration ($key)
    {
        try {
            return $this->getConfiguration($key);
        } catch (Pluf_Exception $ex) {
            $config = SaaS_Util::configurationFactory();
            $config->key = $key;
            $config->application = $this;
            $config->create();
            return $config;
        }
    }

    public function bySubDomain ($subdomain)
    {
        $sql = new Pluf_SQL('subdomain=%s', $subdomain);
        return Pluf::factory('SaaS_Application')->getOne($sql->gen());
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
}