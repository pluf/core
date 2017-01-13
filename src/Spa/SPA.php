<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
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
 * 
 * @author maso
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *
 */
class Spa_SPA extends Pluf_Model
{

    /**
     * دایرکتوری ریشه spa که حاوی فایل spa.json و سایر فایل‌ها و پوشه‌های spa
     * است
     *
     * @var rootPath
     */
    var $rootPath = null;

    /**
     * (non-PHPdoc)
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'saas_spa';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ),
                'name' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 50,
                        'readable' => true,
                        'editable' => false
                ),
                'version' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 100,
                        'readable' => true,
                        'editable' => false
                ),
                'title' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 50,
                        'readable' => true,
                        'editable' => true
                ),
                'license' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 250,
                        'readable' => true,
                        'editable' => false
                ),
                'description' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 250,
                        'readable' => false,
                        'editable' => false
                ),
                'path' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 100,
                        'verbose' => 'SPA installation path',
                        'readable' => false,
                        'editable' => false
                ),
                'main_page' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'default' => 'index.html',
                        'size' => 100,
                        'readable' => false,
                        'editable' => false
                ),
                'homepage' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 100
                ),
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'readable' => true,
                        'editable' => false
                ),
                'modif_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'readable' => true,
                        'editable' => false
                )
        );
        
        $this->_a['idx'] = array(
                'spa_idx' => array(
                        'col' => 'name, version',
                        'type' => 'unique', // normal, unique, fulltext, spatial
                        'index_type' => '', // hash, btree
                        'index_option' => '',
                        'algorithm_option' => '',
                        'lock_option' => ''
                )
        );
        
        $this->_a['views'] = array()
        ;
    }

    /**
     * پیش ذخیره را انجام می‌دهد
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
     * (non-PHPdoc)
     *
     * @see Pluf_Model::preDelete()
     */
    function preDelete ()
    {
        // @unlink(Pluf::f('upload_issue_path').'/'.$this->attachment);
        // TODO: hadi, 1395: قبل از حذف spa فایل‌های مربوط به این spa حذف شود
        // TODO: maso, 1395: از signal-slot استفاده شود و یک signal ارسال شود تا
        // سایرین که به
        // این spa وابسته هستند داده‌های مربوطه‌شان را حذف کنند.
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
     * مسیر دایرکتوری ریشه spa را برمی گرداند.
     *
     * @throws Pluf_Exception
     * @return
     *
     */
    public function getRootPath ()
    {
        return $this->path;
    }

    /**
     * مسیر فایل اصلی برای نمایش spa را برمی‌گرداند.
     * به عنوان مثال مسیر فایل index.html
     * در صورتی که در تنظمیات spa فایل main_page تعیین نشده باشد
     * نام index.html به عنوان نام پیش‌فرض صفحه اصلی در نظر گرفته می‌شود
     *
     * @return string
     */
    public function getMainPagePath ()
    {
        if ($this->main_page)
            return $this->getRootPath() . '/' . $this->main_page;
        return $this->getRootPath() . '/index.html';
    }

    /**
     * spa با نام تعیین شده را برمی‌گرداند.
     * فرض می‌شود که نام spa ها یکتاست. در غیر این صورت
     * اولین spa که نامش با نام تعیین شده یکی باشد برگردانده می‌شود
     *
     * @param string $name نام
     * @param Pluf_Tenant $tenant            
     */
    public static function getSpaByName ($name, $tenant = null)
    {
        $sql = new Pluf_SQL('name=%s', array($name));
        return Pluf::factory('SPA')->getOne($sql->gen());
    }
}