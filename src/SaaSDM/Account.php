<?php
class SaaSDM_Account extends Pluf_Model {
	
	/**
	 * @brief مدل اکانتینگ را بارگذاری می‌کند.
	 *
	 * @see Pluf_Model::init()
	 */
	function init() {
		$this->_a ['table'] = 'saasdm_account';
		$this->_a ['model'] = 'SaaSDM_Account';
		$this->_model = 'SaaSDM_Account';
		$this->_a ['cols'] = array (
				'id' => array (
						'type' => 'Pluf_DB_Field_Sequence',
						'blank' => false 
				),
				'expiry' => array (
						'type' => 'Pluf_DB_Field_Datetime',
						'blank' => false,
						'size' => 50
				),
				'active' => array(
						'type' => 'Pluf_DB_Field_Boolean',
						'blank' => false,
				),
				// relations
				'tenant' => array (
						'type' => 'Pluf_DB_Field_Foreignkey',
						'model' => 'SaaS_Application',
						'blank' => false,
						'relate_name' => 'tenant'
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
}
