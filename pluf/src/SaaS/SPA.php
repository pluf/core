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
    function init ()
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
                        'blank' => true,
                        'size' => 100
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
                        'join' => 'LEFT JOIN ' . $this->_con->pfx .
                                 'rowpermissions ON saas_sap.id=' .
                                 $this->_con->pfx . 'rowpermissions.model_id',
                                'select' => $this->getSelect() . ', permission',
                                'props' => array(
                                        'permission' => 'permission'
                                ),
                                'group' => 'rowpermissions.model_id'
                ),
                'spa_application_permission' => array(
                        'join' => 'LEFT JOIN ' . $this->_con->pfx .
                         'rowpermissions ON saas_spa.id=' . $this->_con->pfx .
                         'rowpermissions.model_id',
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
     * تنظیم‌های بسته را از سیستم لود می‌کند.
     */
    public function loadPackage ()
    {
        $filename = $this->getPackagePath() .
                 Pluf::f('saas_spa_package', "/spa.json");
        $myfile = fopen($filename, "r") or die("Unable to open file!");
        $json = fread($myfile, filesize($filename));
        fclose($myfile);
        $this->package = json_decode($json, true);
        return $this->package;
    }

    public function getPackagePath ()
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
            $filename = $repo . $this->path .
                     Pluf::f('saas_spa_package', "/spa.json");
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

    public function getMainViewPath ()
    {
        $p = $this->loadPackage();
        return $this->getPackagePath() . $p['view'];
    }

    public function getSourcePath ($name)
    {
        return $this->getPackagePath() . '/' . $name;
    }

    public static function getAssetsPath ($name)
    {
        $repos = Pluf::f('saas_spa_repository');
        if (! is_array($repos)) {
            $repos = array(
                    Pluf::f('saas_spa_repository')
            );
        }
        
        foreach ($repos as $repo) { // Load the package
            $filename = $repo. '/assets/' . $name;
            if (! is_readable($filename)) {
                continue;
            }
            return $filename;
        }
        throw new Pluf_Exception("The SPA package is not accessable.");
    }
}