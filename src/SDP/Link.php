<?php

class SDP_Link extends Pluf_Model
{
	/**
	 * @brief مدل داده‌ای را بارگذاری می‌کند.
	 *
	 * @see Pluf_Model::init()
	 */
	function init() {
		$this->_a ['table'] = 'sdp_link';
        $this->_a['verbose'] = 'SDP Link';
        $this->_model = 'SDP_Link';
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
                'editable' => false,
                'readable' => true
            ),
            'modif_dtime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => true,
                'editable' => false,
                'readable' => true
            ),
            'active' => array(
                'type' => 'Pluf_DB_Field_Boolean',
                'blank' => false,
                'editable' => false,
                'readable' => true
            ),
            
            // relations
            'asset' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'SDP_Asset',
                'blank' => false,
                'editable' => false,
                'readable' => true,
                'relate_name' => 'asset'
            ),
            'user' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'Pluf_User',
                'blank' => false,
                'editable' => false,
                'readable' => true,
                'relate_name' => 'user'
            ),
            'payment' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'SaaSBank_Receipt',
                'blank' => false,
                'editable' => false,
                'readable' => true,
                'relate_name' => 'payment'
            ),
            'tenant' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'SaaS_Application',
                'blank' => false,
                'editable' => false,
                'readable' => false,
                'relate_name' => 'tenant'
            )
        );
        
        $this->_a['idx'] = array(
            'link_tenant_idx' => array(
                'col' => 'tenant, secure_link',
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
        return Pluf::factory('SDP_Link')->getOne($sql->gen());
    }

    function isActive()
    {
        return $this->active;
    }

    function activate()
    {
        $this->active = true;
        $this->update();
    }
		$this->_a ['cols'] = array (
				'id' => array (
						'type' => 'Pluf_DB_Field_Sequence',
						'blank' => false,
						'editable' => false,
						'readable' => true 
				)
				,
				'secure_link' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => false,
						'size' => 50,
						'editable' => false,
						'readable' => true
				),
				'expiry' => array (
						'type' => 'Pluf_DB_Field_Datetime',
						'blank' => false,
						'size' => 50,
						'editable' => false,
						'readable' => true						
				),
				'download' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'size' => 50,
						'editable' => false,
						'readable' => true						
				),
				'creation_dtime' => array (
						'type' => 'Pluf_DB_Field_Datetime',
						'blank' => true,
						'editable' => false,
						'readable' => true						
				),
				'modif_dtime' => array (
						'type' => 'Pluf_DB_Field_Datetime',
						'blank' => true,
						'editable' => false,
						'readable' => true						
				),
				'active' => array (
						'type' => 'Pluf_DB_Field_Boolean',
						'blank' => false,
						'editable' => false,
						'readable' => true
				),
				
				// relations
				'asset' => array (
						'type' => 'Pluf_DB_Field_Foreignkey',
						'model' => 'SDP_Asset',
						'blank' => false,
						'editable' => false,
						'readable' => true,						
						'relate_name' => 'asset' 
				),
				'user' => array (
						'type' => 'Pluf_DB_Field_Foreignkey',
						'model' => 'Pluf_User',
						'blank' => false,
						'editable' => false,
						'readable' => true,						
						'relate_name' => 'user' 
				),
				'payment' => array(
						'type' => 'Pluf_DB_Field_Foreignkey',
						'model' => 'SaaSBank_Receipt',
						'blank' => false,
						'editable' => false,
						'readable' => true,
						'relate_name' => 'payment'
				)
		);
		
		$this->_a ['idx'] = array (
				'link_tenant_idx' => array (
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
	 *        	ساخت یا به روز رسانی را تعیین می‌کند
	 */
	function preSave($create = false) {
		if ($this->id == '') {
			$this->creation_dtime = gmdate ( 'Y-m-d H:i:s' );
		}
		$this->modif_dtime = gmdate ( 'Y-m-d H:i:s' );
	}
	
	/**
	 * حالت کار ایجاد شده را به روز می‌کند
	 *
	 * @see Pluf_Model::postSave()
	 */
	function postSave($create = false) {
		//
	}
	public static function getLinkBySecureId($secure_link) {
		$sql = new Pluf_SQL ( 'secure_link=%s', $secure_link );
		return Pluf::factory ( 'SDP_Link' )->getOne ( $sql->gen () );
	}
	function isActive ()
	{
		return $this->active;
	}
	
	function activate(){
		$this->active = true;
		$this->update();
	}
}