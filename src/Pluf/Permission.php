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
 * مدل دسترسی‌ها را در سیستم ایجاد می‌کند.
 *
 * @author maso
 *        
 */
class Pluf_Permission extends Pluf_Model
{

    public $_model = 'Pluf_Permission';

    private $_cache_to_string;

    function init ()
    {
        $this->_a['verbose'] = __('permission');
        $this->_a['table'] = 'permissions';
        $this->_a['model'] = 'Pluf_Permission';
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
                'name' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 50,
                        'verbose' => __('name')
                ),
                'code_name' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 100,
                        'verbose' => __('code name'),
                        'help_text' => __(
                                'The code name must be unique for each application. Standard permissions to manage a model in the interface are "Model_Name-create", "Model_Name-update", "Model_Name-list" and "Model_Name-delete".')
                ),
                'description' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 250,
                        'verbose' => __('description')
                ),
                'application' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'size' => 150,
                        'blank' => false,
                        'verbose' => __('application'),
                        'help_text' => __(
                                'The application using this permission, for example "YourApp", "CMS" or "SView".')
                )
        );
        $this->_a['idx'] = array(
                'code_name_idx' => array(
                        'type' => 'normal',
                        'col' => 'code_name'
                ),
                'application_idx' => array(
                        'type' => 'normal',
                        'col' => 'application'
                ),
                'perme_idx' => array(
                        'col' => 'application, code_name',
                        'type' => 'unique', // normal, unique, fulltext, spatial
                        'index_type' => '', // hash, btree
                        'index_option' => '',
                        'algorithm_option' => '',
                        'lock_option' => ''
                )
        );
        $hay = array(
                strtolower(Pluf::f('pluf_custom_group', 'Pluf_Group')),
                strtolower($this->_a['model'])
        );
        sort($hay);
        $t_asso = $this->_con->pfx . $hay[0] . '_' . $hay[1] . '_assoc';
        $t_perm = $this->_con->pfx . 'permissions';
        $this->_a['views'] = array(
                'join_group' => array(
                        'join' => 'LEFT JOIN ' . $t_asso . ' ON ' . $t_perm .
                                 '.id=pluf_permission_id'
                )
        );
    }

    function __toString ()
    {
        return $this->name . ' (' . $this->application . '.' . $this->code_name .
                 ')';
    }

    /**
     * Get the matching permission object from the permission string.
     *
     * @param
     *            string Permission string, for example 'Pluf_User.create'.
     * @return false|Pluf_Permission The matching permission or false.
     */
    public static function getFromString ($perm)
    {
        list ($app, $code) = explode('.', trim($perm));
        $sql = new Pluf_SQL('code_name=%s AND application=%s', 
                array(
                        $code,
                        $app
                ));
        $perms = Pluf::factory('Pluf_Permission')->getList(
                array(
                        'filter' => $sql->gen()
                ));
        if ($perms->count() != 1) {
            return false;
        }
        return $perms[0];
    }

    public function toString ()
    {
        if (isset($this->_cache_to_string)) {
            return $this->_cache_to_string;
        }
        $this->_cache_to_string = sprintf('%s.%s', $this->application, 
                $this->code_name);
        return $this->_cache_to_string;
    }
}

