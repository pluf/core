<?php

/**
 * 
 * @author maso
 *
 */
class SaaS_SAP extends Pluf_Model
{

    /**
     * (non-PHPdoc)
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_model = 'SaaS_SAP';
        $this->_a['table'] = 'saas_sap';
        $this->_a['model'] = $this->_model;
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ),
                'title' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 100
                ),
                'type' => array(
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
                'sap_application_permission' => array(
                        'join' => 'LEFT JOIN '.$this->_con->pfx.'rowpermissions ON saas_sap.id='.$this->_con->pfx.'rowpermissions.model_id',
                        'select' => $this->getSelect().', permission',
                        'props' => array(
                                'permission' => 'permission'
                        ),
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
        $repo = Pluf::f('saas_sap_repository');
        $package = array();
        {
            $filename = $repo . $this->path .
                     Pluf::f('saas_sap_package', "/sap.json");
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