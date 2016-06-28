<?php

class Bank_Gateway extends Pluf_Model {
	
	/**
	 * @brief مدل داده‌ای را بارگذاری می‌کند.
	 *
	 * تمام فیلدهای مورد نیاز برای این مدل داده‌ای در این متد تعیین شده و به
	 * صورت کامل ساختار دهی می‌شود.
	 *
	 * @see Pluf_Model::init()
	 */
	function init() {
		$this->_a ['table'] = 'bank_receipt';
		$this->_a ['model'] = 'Bank_Receipt';
		$this->_model = 'Bank_Receipt';
		$this->_a ['cols'] = array (
				'id' => array (
						'type' => 'Pluf_DB_Field_Sequence',
						'blank' => true,
						'verbose' => 'unique and no repreducable id fro reception' 
				),
				// XXX: maso,  1395: پارامترهای مورد نیاز آورده شود.
				'creation_dtime' => array (
						'type' => 'Pluf_DB_Field_Datetime',
						'blank' => true,
						'verbose' => 'creation date' 
				),
				'modif_dtime' => array (
						'type' => 'Pluf_DB_Field_Datetime',
						'blank' => true,
						'verbose' => 'modification date' 
				) 
		);
		$this->_a ['views'] = array ();
	}
	
	/*
	 * @see Pluf_Model::preSave()
	 */
	function preSave($create = false) {
		if ($this->id == '') {
			$this->creation_dtime = gmdate ( 'Y-m-d H:i:s' );
		}
		$this->modif_dtime = gmdate ( 'Y-m-d H:i:s' );
	}
}