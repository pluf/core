<?php
class SaaSDM_PlanTemplate extends Pluf_Model {
	
	/**
	 * @brief مدل قالب پلن را بارگذاری می‌کند.
	 *
	 * @see Pluf_Model::init()
	 */
	function init() {
		$this->_a ['table'] = 'saasdm_plantemplate';
		$this->_a ['model'] = 'SaaSDM_Plantemplate';
		$this->_model = 'SaaSDM_Plantemplate';
		$this->_a ['cols'] = array (
				'id' => array (
						'type' => 'Pluf_DB_Field_Sequence',
						'blank' => false 
				),
				'description' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => false,
						'size' => 2500
				),
				'label' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => false,
						'size' => 250 
				),
				'period' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'size' => 250 
				),
				'max_count' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'size' => 250
				),
				'max_volume' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
				),				
				'content_name' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => false,
						'size' => 500 
				),
				'price' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'size' => 20
				),
				'off' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'size' => 10
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
