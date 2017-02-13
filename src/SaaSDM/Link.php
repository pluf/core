<?php

class SaaSDM_Link extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'saasdm_link';
        $this->_a['model'] = 'SaaSDM_Link';
        $this->_model = 'SaaSDM_Link';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => false,
                'editable' => false,
                'readable' => true
            ),
            'secure_link' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 50,
                'editable' => false,
                'readable' => true
            ),
            'expiry' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => false,
                'size' => 50,
                'editable' => false,
                'readable' => true
            ),
            'download' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'blank' => false,
                'size' => 50,
                'editable' => false,
                'readable' => true
            ),
            'creation_dtime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => true,
                'editable' => true,
                'readable' => true
            ),
            'modif_dtime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => true,
                'editable' => true,
                'readable' => true
            ),
            
            // relations
            'asset' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'SaaSDM_Asset',
                'blank' => false,
                'editable' => false,
                'readable' => true,
                'relate_name' => 'asset'
            ),
            'user' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'Pluf_User',
                'blank' => false,
                'readable' => false,
                'editable' => false,
                'relate_name' => 'user'
            )
        );
        
        $this->_a['idx'] = array(
            'link_tenant_idx' => array(
                'col' => 'secure_link',
                'type' => 'unique', // normal, unique, fulltext, spatial
                'index_type' => '', // hash, btree
                'index_option' => '',
                'algorithm_option' => '',
                'lock_option' => ''
            )
        );
    }

    /**
     * \brief پیش ذخیره را انجام می‌دهد
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
     * حالت کار ایجاد شده را به روز می‌کند
     *
     * @see Pluf_Model::postSave()
     */
    function postSave($create = false)
    {
        //
    }

    public static function getLinkBySecureId($secure_link)
    {
        $sql = new Pluf_SQL('secure_link=%s', $secure_link);
        return Pluf::factory('SaaSDM_Link')->getOne($sql->gen());
    }
}