<?php

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaS_Application extends Pluf_Model {
	
	/**
	 * @brief مدل داده‌ای را بارگذاری می‌کند.
	 *
	 * @see Pluf_Model::init()
	 */
	function init() {
		$this->_a ['table'] = 'saas_application';
		$this->_a ['model'] = 'SaaS_Application';
		$this->_model = 'SaaS_Application';
		$this->_a ['cols'] = array (
				'id' => array (
						'type' => 'Pluf_DB_Field_Sequence',
						'blank' => true 
				),
				'level' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'unique' => false 
				),
				'access_count' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'unique' => false 
				),
				'validate' => array (
						'type' => 'Pluf_DB_Field_Boolean',
						'default' => false,
						'blank' => true,
						'verbose' => __ ( 'validate' ) 
				),
				'title' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 100 
				),
				'description' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 250 
				),
				'creation_dtime' => array (
						'type' => 'Pluf_DB_Field_Datetime',
						'blank' => true 
				),
				'modif_dtime' => array (
						'type' => 'Pluf_DB_Field_Datetime',
						'blank' => true 
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
	
	
	/**
	 * دسترسی به تنظیم های عمومی 
	 * 
	 * @param unknown $name
	 * @param string $default
	 * @return string
	 */
	public function getProperty($name, $default = null){
		// XXX: maso, 1394: دسترسی به تنظیم‌های عمومی
		return $default;
	}
}