<?php
class SaaSDM_Plan extends Pluf_Model {
	
	/**
	 * @brief مدل پلن را بارگذاری می‌کند.
	 *
	 * @see Pluf_Model::init()
	 */
	function init() {
		$this->_a ['table'] = 'saasdm_plan';
		$this->_a ['model'] = 'SaaSDM_Plan';
		$this->_model = 'SaaSDM_Plan';
		$this->_a ['cols'] = array (
				'id' => array (
						'type' => 'Pluf_DB_Field_Sequence',
						'blank' => false,
						'editable' => false,
						'readable' => true
				),
				'period' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'size' => 250,
						'editable' => false,
						'readable' => true
				),				
				'expiry' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'size' => 250,
						'editable' => false,
						'readable' => true
				),
				'max_count' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'size' => 250,
						'editable' => false,
						'readable' => true
				),
				'remain_count' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'size' => 250,
						'editable' => false,
						'readable' => true
				),				
				'max_volume' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'editable' => false,
						'readable' => true
				),
				'remain_volume' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'editable' => false,
						'readable' => true
				),
				'price' => array(
						'type' =>'Pluf_DB_Field_Integer',
						'blank' => false,
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
				'tenant' => array (
						'type' => 'Pluf_DB_Field_Foreignkey',
						'model' => 'SaaS_Application',
						'blank' => false,
						'readable' => false,
						'editable' => false,
						'relate_name' => 'tenant' 
				),
				'user' => array (
						'type' => 'Pluf_DB_Field_Foreignkey',
						'model' => 'Pluf_User',
						'blank' => false,
						'readable' => false,	
						'editable' => false,
						'relate_name' => 'user'
				),
				'payment' => array(
						'type' => 'Pluf_DB_Field_Foreignkey',
						'model' => 'Bank_Receipt',
						'blank' => false,
						'editable' => false,
						'readable' => true,
						'relate_name' => 'payment'
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
	
	function isActive ()
	{
		return $this->active;
	}
	
	function activate(){
		$this->active = true;
		$this->update();
	}
}
