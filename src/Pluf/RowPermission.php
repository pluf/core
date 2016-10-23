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
 * ساختار داده‌ای گواهی‌ها
 *
 * با استفاده از این ساختار داده‌ای تعیین می‌شود که کاربران چه گواهی‌هایی
 * در سیستم دارند.
 *
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class Pluf_RowPermission extends Pluf_Model
{

    public $_model = 'Pluf_RowPermission';

    /**
     * مدل رشته‌ای این گواهی را تعیین می‌کند
     *
     * @var unknown
     */
    private $_cache_to_string;

    function init()
    {
        $this->_a['table'] = 'rowpermissions';
        $this->_a['model'] = 'Pluf_RowPermission';
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
            'model_id' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'blank' => false,
                'verbose' => __('model ID')
            ),
            'model_class' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 50,
                'verbose' => __('model class')
            ),
            'owner_id' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'blank' => false,
                'verbose' => __('owner ID')
            ),
            'owner_class' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 50,
                'verbose' => __('owner class'),
                'help_text' => __('For example Pluf_User or Pluf_Group.')
            ),
            'negative' => array(
                'type' => 'Pluf_DB_Field_Boolean',
                'blank' => false,
                'default' => false,
                'verbose' => __('do not have the permission')
        ),
            /*
             * XXX: maso, 1395: بهتر هست که ساختار کلود توی همین بسته بیاد
             */
            'tenant' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'blank' => false
            ),
            'permission' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'Pluf_Permission',
                'blank' => false,
                'verbose' => __('permission')
            )
        );
        $this->_a['idx'] = array(
            'permission_combo_idx' => array(
                'type' => 'unique',
                'col' => 'model_id, model_class, owner_id, owner_class, permission, tenant'
            )
        );
        $t_perm = $this->_con->pfx . 'permissions';
        $this->_a['views'] = array(
            'join_permission' => array(
                'select' => $this->getSelect() . ', ' . $t_perm . '.code_name AS code_name, ' . $t_perm . '.application AS application ',
                'join' => 'LEFT JOIN ' . $t_perm . ' ON ' . $t_perm . '.id=permission',
                'props' => array(
                    'code_name' => 'code_name',
                    'application' => 'application'
                )
            )
        );
    }

    /**
     * یک گواهی را به سیستم اضافه می‌کند.
     *
     * @param Pluf_Model $owner            
     * @param Pluf_Model $object            
     * @param string $perm            
     * @param bool $negative            
     * @throws Exception
     */
    public static function add($owner, $object, $perm, $negative = false, $tenant = 0)
    {
        if (! is_object($perm)) {
            // Find matching permission
            $found = Pluf_Permission::getFromString($perm);
            if (false === $found) {
                throw new Pluf_Exception(sprintf('The permission %s does not exist.', $perm));
            }
            $perm = $found;
        }
        Pluf_RowPermission::remove($owner, $object, $perm, $tenant);
        $nperm = new Pluf_RowPermission();
        $nperm->owner_id = $owner->id;
        $nperm->owner_class = $owner->_a['model'];
        if (isset($model) && $model->isAnonymous()) {
            $nperm->model_id = $object->id;
            $nperm->model_class = $object->_a['model'];
        }
        $nperm->permission = $perm;
        $nperm->negative = $negative;
        $nperm->tenant = $tenant;
        $nperm->create();
        return $nperm;
    }

    /**
     * یک گواهی را از سیستم حذف می‌کند.
     *
     * @param unknown $owner            
     * @param unknown $object            
     * @param unknown $perm            
     * @throws Exception
     */
    public static function remove($owner, $object, $perm, $tenant = 0)
    {
        if (! is_object($perm)) {
            $found = Pluf_Permission::getFromString($perm);
            if (false === $found) {
                throw new Pluf_Exception(sprintf('The permission %s does not exist.', $perm));
            }
            $perm = $found;
        }
        $growp = new Pluf_RowPermission();
        if (isset($model) && ! $model->isAnonymous()) {
            $sql = new Pluf_SQL('owner_id=%s AND owner_class=%s AND model_id=%s AND model_class=%s AND permission=%s AND tenant=%s', array(
                $owner->id,
                $owner->_a['model'],
                $object->id,
                $object->_a['model'],
                $perm->id,
                $tenant
            ));
        } else {
            $sql = new Pluf_SQL('owner_id=%s AND owner_class=%s AND permission=%s AND tenant=%s', array(
                $owner->id,
                $owner->_a['model'],
                $perm->id,
                $tenant
            ));
        }
        $perms = $growp->getList(array(
            'filter' => $sql->gen()
        ));
        foreach ($perms as $p) {
            $p->delete();
        }
        return true;
    }

    /**
     * این مجوز را به رشته تبدیل می‌کند
     */
    public function toString()
    {
        if (isset($this->_cache_to_string)) {
            return $this->_cache_to_string;
        }
        $application = null;
        $code_name = null;
        if (isset($this->application)) {
            $application = $this->application;
            $code_name = $this->code_name;
        } else {
            $perm = $this->get_permission();
            $application = $perm->application;
            $code_name = $perm->code_name;
        }
        // create string
        if (isset($this->model_class)) {
            $this->_cache_to_string = sprintf('%s.%s#%s(%s)', $application, $code_name, $this->model_class, $this->model_id);
        } else {
            $this->_cache_to_string = sprintf('%s.%s', $application, $code_name);
        }
        if ($this->negative) {
            $this->_cache_to_string = '!' . $this->_cache_to_string;
        }
        return $this->_cache_to_string;
    }
}
