<?php

/**
 * 
 * @author maso
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *
 */
class SaaS_SPA extends Pluf_Model
{

    /**
     * اطلاعات موجود در فایل spa.json به صورت یک آرایه نام‌دار در این شی قرار می‌گیرد.
     *
     * @var config
     */
    var $config = null;

    /**
     * دایرکتوری ریشه spa که حاوی فایل spa.json و سایر فایل‌ها و پوشه‌های spa است
     *
     * @var rootPath
     */
    var $rootPath = null;

    /**
     * (non-PHPdoc)
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_model = 'SaaS_SPA';
        $this->_a['table'] = 'saas_spa';
        $this->_a['model'] = $this->_model;
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => true
            ),
            'name' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 50
            ),
            'version' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 100
            ),
            'title' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 50
            ),
            'license' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 250
            ),
            'description' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 250
            ),
            'path' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 100
            ),
            'main_page' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'default' => 'index.html',
                'size' => 100
            ),
            'homepage' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 100
            ),
            'creation_dtime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => true
            ),
            'modif_dtime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => true
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
        
        $this->_a['views'] = array(
            'spa_application' => array(
                'join' => 'LEFT JOIN ' . $this->_con->pfx . 'rowpermissions ON saas_spa.id=' . $this->_con->pfx . 'rowpermissions.model_id',
                'select' => $this->getSelect() . ', permission',
                'props' => array(
                    'permission' => 'permission'
                ),
                'group' => 'rowpermissions.model_id'
            ),
            'spa_application_permission' => array(
                'join' => 'LEFT JOIN ' . $this->_con->pfx . 'rowpermissions ON saas_spa.id=' . $this->_con->pfx . 'rowpermissions.model_id',
                'select' => $this->getSelect() . ', permission',
                'props' => array(
                    'permission' => 'permission'
                )
            )
        );
    }

    /**
     * پیش ذخیره را انجام می‌دهد
     *
     * @param $create حالت
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
     * (non-PHPdoc)
     *
     * @see Pluf_Model::preDelete()
     */
    function preDelete()
    {
        // @unlink(Pluf::f('upload_issue_path').'/'.$this->attachment);
        // TODO: hadi, 1395: قبل از حذف spa فایل‌های مربوط به این spa حذف شود
        // TODO: maso, 1395: از signal-slot استفاده شود و یک signal ارسال شود تا سایرین که به
        // این spa وابسته هستند داده‌های مربوطه‌شان را حذف کنند.
    }

    /**
     * حالت کار ایجاد شده را به روز می‌کند
     *
     * @see Pluf_Model::postSave()
     */
    function postSave($create = false)
    {
        //
    }

    /**
     * تنظیمات بسته را از سیستم بارگزاری می‌کند
     *
     * به عبارتی این متد محتویات فایل spa.josn را خوانده و در متغیر config از این کلاس ذخیره می‌کند.
     */
    public function loadConfig()
    {
        if ($this->config != null) {
            return $this->config;
        }
        $filename = $this->getRootPath() . '/' . Pluf::f('saas_spa_config', "spa.json");
        $myfile = fopen($filename, "r") or die("Unable to open file!");
        $json = fread($myfile, filesize($filename));
        fclose($myfile);
        $this->config = json_decode($json, true);
        return $this->config;
    }

    /**
     * مسیر دایرکتوری ریشه spa را برمی گرداند.
     *
     * @throws Pluf_Exception
     * @return
     *
     */
    public function getRootPath()
    {
        if ($this->rootPath != null) {
            return $this->rootPath;
        }
        
        $repos = Pluf::f('saas_spa_repository');
        if (! is_array($repos)) {
            $repos = array(
                Pluf::f('saas_spa_repository')
            );
        }
        
        foreach ($repos as $repo) { // Load the package
            $filename = $repo . $this->path . '/' . Pluf::f('saas_spa_config', "spa.json");
            if (! is_readable($filename)) {
                continue;
            }
            $this->rootPath = $repo . $this->path;
            break;
        }
        if ($this->rootPath == null) {
            // TODO: Exception handling
            throw new Pluf_Exception("The root path of SPA is not accessible.");
        }
        return $this->rootPath;
    }

    /**
     * مسیر فایل اصلی برای نمایش spa را برمی‌گرداند.
     * به عنوان مثال مسیر فایل index.html
     * در صورتی که در تنظمیات spa فایل main_page تعیین نشده باشد
     * نام index.html به عنوان نام پیش‌فرض صفحه اصلی در نظر گرفته می‌شود
     *
     * @return string
     */
    public function getMainPagePath()
    {
        if ($this->main_page)
            return $this->getRootPath() . '/' . $this->main_page;
        return $this->getRootPath() . '/index.html';
    }

    public function getResourcePath($name)
    {
        return $this->getRootPath() . '/' . $name;
    }

    /**
     * مسیر دایرکتوری مربوط به asset های پلتفورم را برمی‌گرداند.
     * این مسیر یک مسیر عمومی است و فقط مخصوص spa جاری نیست.
     *
     * مسیر asset مربوط به پلتفورم در تنظیمات کلی پلتفورم قابل تنظیم است
     *
     * @param unknown $name            
     * @throws Pluf_Exception
     */
    public static function getAssetPath($name)
    {
        $repos = Pluf::f('saas_spa_repository');
        if (! is_array($repos)) {
            $repos = array(
                Pluf::f('saas_spa_repository')
            );
        }
        
        foreach ($repos as $repo) { // Load the package
            $filename = $repo . '/assets/' . $name;
            if (! is_readable($filename)) {
                continue;
            }
            return $filename;
        }
        throw new Pluf_Exception("The SPA asset is not accessible.");
    }

    /**
     * spa با نام تعیین شده را برمی‌گرداند.
     * فرض می‌شود که نام spa ها یکتاست. در غیر این صورت
     * اولین spa که نامش با نام تعیین شده یکی باشد برگردانده می‌شود
     *
     * @param $name نام            
     */
    public static function getSpaByName($name)
    {
        $sql = new Pluf_SQL('name=%s', $name);
        return Pluf::factory('SaaS_SPA')->getOne($sql->gen());
    }
}