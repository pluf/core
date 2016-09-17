<?php

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. http://dpq.co.ir
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * مدل داده‌ای کاربر
 *
 * این مدل داده‌ای، یک مدل داده‌ای کلی است و همواره به صورت پیش فرض استفاده
 * می‌شود.
 * در صورت تمایل می‌توان از ساختارهای داده‌ای دیگر به عنوان مدل داده‌ای برای
 * کاربران
 * استفاده کرد.
 */
class Pluf_User extends Pluf_Model
{

    public $_model = 'Pluf_User';

    /**
     * کلد جلسه کاربر را تعیین می‌کند.
     */
    public $session_key = '_PX_Pluf_User_auth';

    /**
     * Cache of the permissions.
     */
    public $_cache_perms = null;

    function init ()
    {
        $langs = Pluf::f('languages', array(
                'en'
        ));
        $this->_a['verbose'] = __('user');
        $this->_a['table'] = 'users';
        $this->_a['model'] = 'Pluf_User';
        $this->_a['cols'] = array(
                // It is mandatory to have an "id" column.
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        // It is automatically added.
                        'blank' => true
                ),
                'version' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => true
                ),
                'login' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'unique' => true,
                        'size' => 50,
                        'verbose' => __('login')
                ),
                'first_name' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 100,
                        'verbose' => __('first name')
                ),
                'last_name' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 100,
                        'verbose' => __('last name')
                ),
                'email' => array(
                        'type' => 'Pluf_DB_Field_Email',
                        'blank' => false,
                        'verbose' => __('email')
                ),
                'password' => array(
                        'type' => 'Pluf_DB_Field_Password',
                        'blank' => false,
                        'verbose' => __('password'),
                        'size' => 150,
                        'help_text' => __('Format: [algo]:[salt]:[hash]'),
                        'secure' => true
                ),
                'groups' => array(
                        'type' => 'Pluf_DB_Field_Manytomany',
                        'blank' => true,
                        'model' => Pluf::f('pluf_custom_group', 'Pluf_Group'),
                        'relate_name' => 'users'
                ),
                'permissions' => array(
                        'type' => 'Pluf_DB_Field_Manytomany',
                        'blank' => true,
                        'model' => 'Pluf_Permission'
                ),
                'administrator' => array(
                        'type' => 'Pluf_DB_Field_Boolean',
                        'default' => false,
                        'blank' => true,
                        'verbose' => __('administrator')
                ),
                'staff' => array(
                        'type' => 'Pluf_DB_Field_Boolean',
                        'default' => false,
                        'blank' => true,
                        'verbose' => __('staff')
                ),
                'active' => array(
                        'type' => 'Pluf_DB_Field_Boolean',
                        'default' => true,
                        'blank' => true,
                        'verbose' => __('active')
                ),
                'language' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'default' => $langs[0],
                        'size' => 5,
                        'verbose' => __('language'),
                        'help_text' => __(
                                'Prefered language of the user for the interface. Use the 2 or 5 letter code like "fr", "en", "fr_QC" or "en_US".')
                ),
                'timezone' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'default' => date_default_timezone_get(),
                        'size' => 45,
                        'verbose' => __('time zone'),
                        'help_text' => __(
                                'Time zone of the user to display the time in local time.')
                ),
                'date_joined' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('date joined'),
                        'editable' => false
                ),
                'last_login' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('last login'),
                        'editable' => false
                )
        );
        $this->_a['idx'] = array(
                'login_idx' => array(
                        'col' => 'login',
                        'type' => 'unique'
                )
        );
        $this->_a['views'] = array(
                'all' => array(
                        'select' => $this->getSelect()
                ),
                'secure' => array(
                        'select' => $this->getSecureSelect()
                ),
                'user_permission' => array(
                        'select' => $this->getSecureSelect(),
                        'join' => 'LEFT JOIN rowpermissions ON users.id=rowpermissions.owner_id'
                )
        );
        if (Pluf::f('pluf_custom_user', false))
            $this->extended_init();
    }

    /**
     * تمام داده‌های امنیتی را از فهرست انتخاب حذف می‌کند.
     */
    function getSecureSelect ()
    {
        if (isset($this->_cache['getSecureSelect']))
            return $this->_cache['getSecureSelect'];
        $select = array();
        $table = $this->getSqlTable();
        foreach ($this->_a['cols'] as $col => $val) {
            if ($val['type'] != 'Pluf_DB_Field_Manytomany' && ! (array_key_exists(
                    'secure', $val) && $val['secure'] == true)) {
                $select[] = $table . '.' . $this->_con->qn($col) . ' AS ' .
                         $this->_con->qn($col);
            }
        }
        $this->_cache['getSecureSelect'] = implode(', ', $select);
        return $this->_cache['getSecureSelect'];
    }

    /**
     * Hook for extended class
     */
    function extended_init ()
    {
        return;
    }

    /**
     * نمایش رشته‌ای از یک کاربر
     *
     * این کلاس یک نمایش رشته‌ای از کاربر ایجاد می‌کند.
     */
    function __toString ()
    {
        $repr = $this->last_name;
        if (strlen($this->first_name) > 0) {
            $repr = $this->first_name . ' ' . $repr;
        }
        return $repr;
    }

    /**
     * فراخوانی‌های پیش از حذف کاربر
     *
     * پیش از این که کاربر حذف شود یک سیگنال به کل سیستم ارسال شده و حذف کاربر
     * گزارش می‌شود.
     */
    function preDelete ()
    {
        /**
         * [signal]
         *
         * Pluf_User::preDelete
         *
         * [sender]
         *
         * Pluf_User
         *
         * [description]
         *
         * This signal allows an application to perform special
         * operations at the deletion of a user.
         *
         * [parameters]
         *
         * array('user' => $user)
         */
        $params = array(
                'user' => $this
        );
        Pluf_Signal::send('Pluf_User::preDelete', 'Pluf_User', $params);
        
        if (Pluf::f('pluf_use_rowpermission', false)) {
            $_rpt = Pluf::factory('Pluf_RowPermission')->getSqlTable();
            $sql = new Pluf_SQL('owner_class=%s AND owner_id=%s', 
                    array(
                            $this->_a['model'],
                            $this->_data['id']
                    ));
            $this->_con->execute(
                    'DELETE FROM ' . $_rpt . ' WHERE ' . $sql->gen());
        }
    }

    /**
     * Set the password of a user.
     *
     * You need to manually save the user to store the password in the
     * database. The supported algorithms are md5, crc32 and sha1,
     * sha1 being the default.
     *
     * @param
     *            string New password
     * @return bool Success
     */
    function setPassword ($password)
    {
        $salt = Pluf_Utils::getRandomString(5);
        $this->password = 'sha1:' . $salt . ':' . sha1($salt . $password);
        return true;
    }

    /**
     * تعیین صحت گذرواژه کاربر
     *
     * در صورتی که گذرواژه کاربر تعیین شود، این متد بررسی می‌کن که آیا مقدار
     * درستی برای
     * آن تعیین شده است یا نه.
     *
     * @param
     *            string گذرواژه
     * @return bool مقدار درستی در صورت موفقیت
     */
    function checkPassword ($password)
    {
        if ($this->password == '') {
            return false;
        }
        list ($algo, $salt, $hash) = explode(':', $this->password);
        if ($hash == $algo($salt . $password)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if the login creditentials are valid.
     *
     * @param
     *            string Login
     * @param
     *            string Password
     * @return mixed False or matching user
     */
    function checkCreditentials ($login, $password)
    {
        $where = 'login = ' . $this->_toDb($login, 'login');
        $users = $this->getList(
                array(
                        'filter' => $where
                ));
        if ($users === false or count($users) !== 1) {
            return false;
        }
        if ($users[0]->active and $users[0]->checkPassword($password)) {
            return $users[0];
        }
        return false;
    }

    /**
     * خصوصیت‌های کاربر را استخراج کرده و در اختیار قرار می دهد.
     *
     * @param unknown $login            
     * @return boolean|ArrayObject
     */
    function getUser ($login)
    {
        $where = 'login = ' . $this->_toDb($login, 'login');
        $users = $this->getList(
                array(
                        'filter' => $where
                ));
        if ($users === false or count($users) !== 1) {
            return false;
        }
        return $users[0];
    }

    /**
     * Set the last_login and date_joined before creating.
     */
    function preSave ($create = false)
    {
        if (! ($this->id > 0)) {
            $this->last_login = gmdate('Y-m-d H:i:s');
            $this->date_joined = gmdate('Y-m-d H:i:s');
        }
    }

    /**
     * تمام دسترسی‌هایی که یک کاربر دارد را تعیین می‌کند.
     *
     * این که گواهی مربوط به یک سطر است یا نه به صورت کلی تعیین شده است مهم نیست
     * و تنها وجود گواهی برای کاربر در نظر گرفته می‌شود.
     *
     * گواهی می‌تواند به کاربر و یا به گروه انتصاب داده شده باشد.
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
        $perms = (array) $this->get_permissions_list();
        $groups = $this->get_groups_list();
        $ids = array();
        foreach ($groups as $group) {
            $ids[] = $group->id;
        }
        if (count($ids) > 0) {
            $gperm = new Pluf_Permission();
            $f_name = strtolower(Pluf::f('pluf_custom_group', 'Pluf_Group')) .
                     '_id';
            $perms = array_merge($perms, 
                    (array) $gperm->getList(
                            array(
                                    'filter' => $f_name . ' IN (' .
                                             join(', ', $ids) . ')',
                                            'view' => 'join_group'
                            )));
        }
        foreach ($perms as $perm) {
            if (! in_array($perm->application . '.' . $perm->code_name, 
                    $this->_cache_perms)) {
                $this->_cache_perms[] = $perm->application . '.' .
                         $perm->code_name;
            }
        }
        if (Pluf::f('pluf_use_rowpermission', false) and $this->id) {
            $growp = new Pluf_RowPermission();
            $sql = new Pluf_SQL('owner_id=%s AND owner_class=%s', 
                    array(
                            $this->id,
                            'Pluf_User'
                    ));
            if (count($ids) > 0) {
                $sql2 = new Pluf_SQL(
                        'owner_id IN (' . join(', ', $ids) .
                                 ') AND owner_class=%s', 
                                array(
                                        Pluf::f('pluf_custom_group', 
                                                'Pluf_Group')
                                ));
                $sql->SOr($sql2);
            }
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
     * تمام گواهی‌های کاربر را تعیین می‌کند
     *
     * این فراخوانی تمام گواهی‌های کاربر را تعیین می‌کند که برای یک موجودیت
     * دارد. برای نمونه تمام گواهی‌هایی که کاربر برای یک نرم افزار دارد را
     * می‌توان با این فراخوانی تعیین کرد.
     *
     * @param unknown $object            
     */
    public function getPermissions ($object)
    {
        // Group permissions
        // $groups = $this->get_groups_list();
        // $ids = array();
        // foreach ($groups as $group) {
        // $ids[] = $group->id;
        // }
        // if (count($ids) > 0) {
        // $gperm = new Pluf_Permission();
        // $f_name = strtolower(Pluf::f('pluf_custom_group', 'Pluf_Group')) .
        // '_id';
        // $perms = array_merge($perms,
        // (array) $gperm->getList(
        // array(
        // 'filter' => $f_name . ' IN (' .
        // join(', ', $ids) . ')',
        // 'view' => 'join_group'
        // )));
        // }
    }

    /**
     * تعیین گواهی برای شئی تعیین شده
     *
     * یگ گواهی برای یک مدل خاص است، در اینجا می‌توان تعیین کرد که آیا کاربر
     * به شئی مورد نظر این گواهی را دارد.
     *
     * <ul>
     * <li>کاربر باید در سیستم فعال باشد</li>
     * <li>کاربر مدیر تمام دسترسی‌ها را دارد</li>
     * <li></li>
     * </ul>
     *
     * @param
     *            string Permission
     * @param
     *            Object Object for row level permission (null)
     * @return bool درستی اگر کاربر گواهی مورد نظر برای شئی را دارد.
     */
    function hasPerm ($perm, $obj = null)
    {
        if (! $this->active)
            return false;
        if ($this->administrator)
            return true;
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
     * تعیین می‌کند که آیا کاربر یکی از مجوزهای نرم افزار را دارد یا نه.
     *
     * @return bool درستی اگر یکی از مجوزها وجود داشته باشد.
     */
    function hasAppPerms ($app)
    {
        if ($this->administrator)
            return true;
        foreach ($this->getAllPermissions() as $perm) {
            if (0 === strpos($perm, $app . '.')) {
                return true;
            }
        }
        return false;
    }

    /**
     * تعیین پیام برای کاربر
     *
     * یک پیام جدید را ایجاد کرده و به کاربر اضافه می‌کند. در صورتی که کاربر در
     * سیستم ایجاد نشده باشد یک خطا صادر خواهد شد.
     *
     * @param
     *            string Message
     * @return bool Success
     */
    function setMessage ($message)
    {
        if ($this->isAnonymous()) {
            throw new Pluf_Exception_DoesNotExist(
                    __("User not exist, while you are trying to add message?!"));
        }
        $m = new Pluf_Message();
        $m->user = $this;
        $m->message = $message;
        if (! $m->create()) {
            throw new Pluf_Exception(__("not possible to create a message"));
        }
        return $m;
    }

    /**
     * دریافت و حذف پیام‌ها
     *
     * تمام پیام‌هایی که به کار بر اضافه شده است را به عنوان نتیجه برمی‌گرداند.
     * در
     * صورتی که کاربر ایجاد نشده باشد پیام خطا صادر خواهد شد.
     *
     * @return ArrayObject
     */
    function getAndDeleteMessages ()
    {
        if ($this->isAnonymous()) {
            throw new Pluf_Exception_DoesNotExist(
                    __("User not exist, while you are trying to get messages?!"));
        }
        $messages = array();
        $ms = $this->get_pluf_message_list();
        foreach ($ms as $m) {
            $ms = new Pluf_Message($m->id);
            array_push($messages, $ms);
            $m->delete();
        }
        return $messages;
    }

    /**
     * پروفایل کاربر را تعیین می‌کند.
     *
     * Retrieve the profile of the current user. If not profile in the
     * database a Pluf_Exception_DoesNotExist exception is thrown,
     * just catch it and create a profile.
     *
     * @return Pluf_Model User profile
     */
    function getProfile ()
    {
        $pclass = Pluf::f('user_profile_class', false);
        if (false == $pclass) {
            throw new Pluf_Exception_SettingError(
                    __('"user_profile_class" setting not defined.'));
        }
        $db = $this->getDbConnection();
        $sql = new Pluf_SQL(sprintf('%s=%%s', $db->qn('user')), 
                array(
                        $this->id
                ));
        $users = Pluf::factory($pclass)->getList(
                array(
                        'filter' => $sql->gen()
                ));
        if ($users->count() != 1) {
            throw new Pluf_Exception_DoesNotExist(
                    sprintf(__('No profiles available for user: %s'), 
                            (string) $this));
        }
        return $users[0];
    }
}
