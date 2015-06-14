<?php

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaS_Configuration extends Pluf_Model {
	
	/**
	 * @brief مدل داده‌ای را بارگذاری می‌کند.
	 *
	 * @see Pluf_Model::init()
	 */
	function init() {
		$this->_a ['table'] = 'saas_configuration';
		$this->_a ['model'] = 'SaaS_Configuration';
		$this->_model = 'SaaS_Configuration';
		
		$cols ['id'] = array (
				'type' => 'Pluf_DB_Field_Sequence',
				'blank' => true 
		);
		$cols ['application'] = array (
				'type' => 'Pluf_DB_Field_Foreignkey',
				'model' => 'SaaS_Application',
				'blank' => true,
				'relate_name' => 'configuration',
				'verbose' => __ ( 'application' ),
				'help_text' => __ ( 'Related application.' ) 
		);
		$cols ['key'] = array (
				'type' => 'Pluf_DB_Field_Varchar',
				'model' => 'KM_Label',
				'blank' => false,
				'relate_name' => 'location' 
		);
		$cols ['value'] = array (
				'type' => 'Pluf_DB_Field_Varchar',
				'blank' => true,
				'size' => 250,
				'verbose' => __ ( 'value' ),
				'help_text' => __ ( 'A one line value of the configuration content.' ) 
		);
		$cols ['description'] = array (
				'type' => 'Pluf_DB_Field_Varchar',
				'blank' => false,
				'size' => 250,
				'verbose' => __ ( 'description' ),
				'help_text' => __ ( 'A one line description of the configuration.' ) 
		);
		$cols ['creation_dtime'] = array (
				'type' => 'Pluf_DB_Field_Datetime',
				'blank' => true,
				'verbose' => __ ( 'creation date' ),
				'help_text' => __ ( 'Creation date of the configuration.' ) 
		);
		$cols ['modif_dtime'] = array (
				'type' => 'Pluf_DB_Field_Datetime',
				'blank' => true,
				'verbose' => __ ( 'modification date' ),
				'help_text' => __ ( 'Modification date of the configuration.' ) 
		);
		
		$this->_a ['cols'] = $cols;
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