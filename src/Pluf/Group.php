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
 * مدل داده‌ای یک گروه را ایجاد می‌کند.
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *        
 */
class Pluf_Group extends Pluf_Model
{

    /**
     * Cache of the permissions.
     */
    public $_cache_perms = null;

    function init()
    {
        $this->_a['table'] = 'groups';
        $this->_a['verbose'] = 'group';
        $this->_a['cols'] = array(
            // It is mandatory to have an "id" column.
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => true,
                'readable' => true,
                'editable' => false
            ),
            'version' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'blank' => true,
                'readable' => true,
                'editable' => false
            ),
            'name' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 50,
                'verbose' => 'name',
                'readable' => true,
                'editable' => true
            ),
            'description' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 250,
                'verbose' => 'description',
                'readable' => true,
                'editable' => true
            ),
            // XXX: hadi 1396-07: should be deleted. we use rowpermission system.
            'permissions' => array(
                'type' => 'Pluf_DB_Field_Manytomany',
                'blank' => true,
                'model' => 'Pluf_Permission',
                'readable' => true
            )
        );
        // TODO: hadi 1396-07: group name should be unique so we should define index for it.
        
        $hay = array(
            strtolower('Pluf_User'),
            strtolower($this->_a['model'])
        );
        sort($hay);
        $t_asso = $this->_con->pfx . $hay[0] . '_' . $hay[1] . '_assoc';
        $t_group = $this->_con->pfx . $this->_a['table'];
        $this->_a['views'] = array(
            'group_permission' => array(
                'join' => 'LEFT JOIN rowpermissions ON ' . $t_group . '.id=rowpermissions.owner_id AND rowpermissions.owner_class="' . $this->_a['model'] . '"'
            ),
            'roled_group' => array(
                'join' => 'JOIN (SELECT DISTINCT owner_id, owner_class, tenant FROM rowpermissions) AS B ' . 'ON (' . $t_group . '.id=B.owner_id AND B.owner_class="Pluf_Group")'
            ),
            'group_user' => array(
                'join' => 'LEFT JOIN ' . $t_asso . ' ON ' . $t_group . '.id=pluf_group_id'
            )
        );
        // if (Pluf::f('pluf_custom_group', false))
        // $this->extended_init();
    }

    /**
     * Hook for extended class
     */
    function extended_init()
    {
        return;
    }

    function __toString()
    {
        return $this->name;
    }

    /**
     * تمام دسترسی‌ها حذف می‌شود.
     *
     * تمام دسترسی‌هایی که به این گروه داده شده است از سیستم حذف می‌شود.
     */
    function preDelete()
    {
        $_rpt = Pluf::factory('Pluf_RowPermission')->getSqlTable();
        $sql = new Pluf_SQL('owner_class=%s AND owner_id=%s', array(
            $this->_a['model'],
            $this->_data['id']
        ));
        $this->_con->execute('DELETE FROM ' . $_rpt . ' WHERE ' . $sql->gen());
    }

    /**
     * تمام دسترسی‌هایی که یک گروه دارد را تعیین می‌کند.
     *
     * این که گواهی مربوط به یک سطر است یا نه به صورت کلی تعیین شده است مهم نیست
     * و تنها وجود گواهی برای گروه در نظر گرفته می‌شود.
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
        // load group permissions
        $this->_cache_perms = (array) $this->get_permissions_list();
        
        // load row permission
        if ($this->id) {
            $this->loadRowPermissions();
        }
        return $this->_cache_perms;
    }

    /**
     * فهرستی از شی داده شده را برمی‌گرداند که این گروه دسترسی تعیین شده را روی
     * آن‌ها دارد.
     * به عنوان مثال فراخوانی این متد به صورت
     * getAllPermittedObject('App.manage',
     * new Pluf_Group(), 1)
     * فهرستی از Pluf_Group هایی را برمی‌گرداند که گروه جاری روی ان‌ها دسترسی
     * 'manage' رو در ملک با
     * شناسه یک دارد.
     *
     * @param Pluf_Model $object
     *            نمونه از شی مورد نظر
     * @param string $permission
     *            رشته حاوی code_name مربوط به گواهی مورد نظر
     */
    function getAllPermittedObject($permission, $object)
    {
        $permPattern = $permission . '#' . $object->_a['model'];
        $permList = $this->getAllPermissions(false);
        $result = array();
        $m = array();
        foreach ($permList as $rowPerm) {
            try {
                preg_match('/^(?P<perm>' . $permPattern . ')\((?P<id>\d+)\)/', $rowPerm, $m);
                $obj = new $object->_a['model']($m['id']);
                array_push($result, $obj);
            } catch (Exception $e) {}
        }
        return $result;
    }

    /*
     * تمام گواهی‌هایی که با جدول مشخص شده است را لود می‌کند.
     */
    private function loadRowPermissions()
    {
        $growp = new Pluf_RowPermission();
        $sql = new Pluf_SQL('owner_id=%s AND owner_class=%s', array(
            $this->id,
            'Pluf_Group'
        ));
        $perms = $growp->getList(array(
            'filter' => $sql->gen(),
            'view' => 'join_permission'
        ));
        foreach ($perms as $perm) {
            $perm_string = $perm->toString();
            if (! in_array($perm_string, $this->_cache_perms)) {
                $this->_cache_perms[] = $perm_string;
            }
        }
    }

    /**
     * تعیین گواهی برای شئی تعیین شده
     *
     * یگ گواهی برای یک مدل خاص است، در اینجا می‌توان تعیین کرد که آیا گروه
     * به شئی مورد نظر این گواهی را دارد.
     *
     * @param
     *            string Permission
     * @param
     *            Object Object for row level permission (null)
     * @return bool درستی اگر گروه گواهی مورد نظر برای شئی را دارد.
     */
    function hasPerm($perm, $obj = null)
    {
        $perms = $this->getAllPermissions(false);
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
     * تعیین می‌کند که آیا گروه یکی از مجوزهای نرم افزار را دارد یا نه.
     *
     * @return bool درستی اگر یکی از مجوزها وجود داشته باشد.
     */
    function hasAppPerms($app)
    {
        foreach ($this->getAllPermissions() as $perm) {
            if (0 === strpos($perm, $app . '.')) {
                return true;
            }
        }
        return false;
    }
}
