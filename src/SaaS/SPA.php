<?php

/**
 * 
 * @author maso
 *
 */
class SaaS_SPA extends Pluf_Model
{

    var $package = null;

    var $packagePath = null;

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
                'unique' => true,
                'size' => 50
            ),
            'title' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'unique' => false,
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
            'version' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 100
            ),
            'path' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 100
            ),
            'homepage' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
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

    public static function getByName($name)
    {
        $sql = new Pluf_SQL('name=%s', $name);
        return Pluf::factory('SaaS_SPA')->getOne($sql->gen());
    }

    /**
     * تنظیم‌های بسته را از سیستم لود می‌کند.
     */
    public function loadPackage()
    {
        if ($this->package != null) {
            return $this->package;
        }
        $filename = $this->getPackagePath() . Pluf::f('saas_spa_package', "/spa.json");
        $myfile = fopen($filename, "r") or die("Unable to open file!");
        $json = fread($myfile, filesize($filename));
        fclose($myfile);
        $this->package = json_decode($json, true);
        
        { // Load file list
            $pkeys = array(
                'src',
                'resource'
            );
            $pp = $this->getPackagePath() . '/';
            foreach ($pkeys as $key) {
                if (array_key_exists($key, $this->package)) { // Load source
                    $tmp = $this->package[$key];
                    $this->package[$key] = array();
                    foreach ($tmp as $value) {
                        $tp = $pp . $value;
                        foreach (glob($tp) as $filename) {
                            // TODO: Check if is file
                            // TODO: Check if is readable
                            // Remove prefix
                            $str = substr($filename, strlen($pp));
                            $this->package[$key][] = $str;
                        }
                    }
                } else { // Default value
                    $this->package[$key] = array();
                }
            }
        }
        
        return $this->package;
    }

    public function getPackagePath()
    {
        if ($this->packagePath != null) {
            return $this->packagePath;
        }
        
        $repos = Pluf::f('saas_spa_repository');
        if (! is_array($repos)) {
            $repos = array(
                Pluf::f('saas_spa_repository')
            );
        }
        
        foreach ($repos as $repo) { // Load the package
            $filename = $repo . $this->path . Pluf::f('saas_spa_package', "/spa.json");
            if (! is_readable($filename)) {
                continue;
            }
            $this->packagePath = $repo . $this->path;
            break;
        }
        if ($this->packagePath == null) {
            // TODO: Exception handling
            throw new Pluf_Exception("The SPA package is not accessable.");
        }
        return $this->packagePath;
    }

    /**
     * مسیر نمایش اصلی سایت را تعیین می‌کند.
     *
     * @return string
     */
    public function getMainViewPath()
    {
        $p = $this->loadPackage();
        if (array_key_exists('view', $p))
            return $this->getPackagePath() . $p['view'];
        return false;
    }

    public function getIndexPath()
    {
        $p = $this->loadPackage();
        if (array_key_exists('index', $p))
            return $this->getPackagePath() . $p['index'];
        return false;
    }

    public function getSourcePath($name)
    {
        return $this->getPackagePath() . '/' . $name;
    }

    public static function getAssetsPath($name)
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
        throw new Pluf_Exception("The SPA package is not accessable.");
    }
}