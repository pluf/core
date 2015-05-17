<?php

/**
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Label_Models_Label extends Pluf_Model {
	
	/**
	 * @brief مدل داده‌ای را بارگذاری می‌کند.
	 *
	 * @see Pluf_Model::init()
	 */
	function init() {
		$this->_a ['table'] = 'labels';
		$this->_a ['model'] = 'Label_Models_Label';
		$this->_model = 'Label_Models_Label';
		
		$cols ['id'] = array (
				'type' => 'Pluf_DB_Field_Sequence',
				'blank' => true 
		);
		$cols ['user'] = array (
				'type' => 'Pluf_DB_Field_Foreignkey',
				'model' => 'Pluf_User',
				'blank' => false,
				'unique' => true
		);
		
		$cols ['title'] = array (
				'type' => 'Pluf_DB_Field_Varchar',
				'blank' => false,
				'size' => 250,
				'verbose' => __ ( 'title' ),
				'help_text' => __ ( 'The title of the label must only contain letters, digits or the dash character. For example: My-new-Wiki-Page.' ) 
		);
		$cols ['description'] = array (
				'type' => 'Pluf_DB_Field_Varchar',
				'blank' => false,
				'size' => 500,
				'verbose' => __ ( 'description' ),
				'help_text' => __ ( 'The description of the label must only contain letters. For example: en.' ) 
		);
		$cols ['color'] = array (
				'type' => 'Pluf_DB_Field_Varchar',
				'blank' => false,
				'size' => 100,
				'verbose' => __ ( 'color' ),
				'help_text' => __ ( 'A one line description of the page content.' ) 
		);
		$cols ['creation_dtime'] = array (
				'type' => 'Pluf_DB_Field_Datetime',
				'blank' => true,
				'verbose' => __ ( 'creation date' ) 
		);
		$cols ['modif_dtime'] = array (
				'type' => 'Pluf_DB_Field_Datetime',
				'blank' => true,
				'verbose' => __ ( 'modification date' ) 
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