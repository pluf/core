<?php

/**
 * 
 * @author maso
 *
 */
class SaaS_SPA extends Pluf_Model
{

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

    public function loadPackage ()
    {
        $repo = Pluf::f('saas_spa_repository');
        $package = array();
        {
            $filename = $repo . $this->path .
                     Pluf::f('saas_spa_package', "/spa.json");
            if (is_readable($filename)) {
                $myfile = fopen($filename, "r") or die("Unable to open file!");
                $json = fread($myfile, filesize($filename));
                fclose($myfile);
                $package = json_decode($json, true);
            }
        }
        return $package;
    }
}