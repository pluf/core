<?php

/**
 * ساختارهای داده‌ای برای پرداخت را ایجاد می‌کند.
 * 
 * @author maso
 *
 */
class HM_Payment extends Pluf_Model {
	
	/**
	 * @brief مدل داده‌ای را بارگذاری می‌کند.
	 *
	 * تمام فیلدهای مورد نیاز برای این مدل داده‌ای در این متد تعیین شده و به
	 * صورت کامل ساختار دهی می‌شود.
	 *
	 * @see Pluf_Model::init()
	 */
	function init() {
		$this->_a ['table'] = 'hm_payment';
		$this->_a ['model'] = 'HM_Payment';
		$this->_model = 'HM_Payment';
		$this->_a ['cols'] = array (
				'id' => array (
						'type' => 'Pluf_DB_Field_Sequence',
						'blank' => true 
				),
				'receipts' => array (
						'type' => 'Pluf_DB_Field_Manytomany',
						'blank' => true,
						'model' => 'Bank_Receipt',
						'relate_name' => 'payment' 
				),
				'verified' => array (
						'type' => 'Pluf_DB_Field_Boolean',
						'blank' => true,
						'unique' => false 
				),
				'deleted' => array (
						'type' => 'Pluf_DB_Field_Boolean',
						'blank' => true,
						'unique' => false 
				),
				'part' => array (
						'type' => 'Pluf_DB_Field_Foreignkey',
						'model' => 'HM_Part',
						'blank' => false 
				),
				'amount' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'unique' => false 
				),
				'title' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => false,
						'size' => 50 
				),
				'description' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 200 
				),
				'creation_dtime' => array (
						'type' => 'Pluf_DB_Field_Datetime',
						'blank' => true,
						'verbose' => __ ( 'creation date' ) 
				),
				'modif_dtime' => array (
						'type' => 'Pluf_DB_Field_Datetime',
						'blank' => true,
						'verbose' => __ ( 'modification date' ) 
				) 
		);
		$this->_a ['views'] = array (
				'with_apartment' => array (
						'join' => 'LEFT JOIN ' . $this->_con->pfx . 'hm_part ON part=' . $this->_con->pfx . 'hm_part.id',
						'select' => $this->getSelect () . ',' . $this->_con->pfx . 'hm_part.apartment as apartment',
						'props' => array (
								'apartment' => 'apartment' 
						) 
				) 
		);
	}
	
	/**
	 * پیش ذخیره را انجام می‌دهد
	 *
	 * در این فرآیند نیازهای ابتدایی سیستم به آن اضافه می‌شود. این نیازها مقادیری هستند که
	 * در زمان ایجاد باید تعیین شوند. از این جمله می‌توان به کاربر و تاریخ اشاره کرد.
	 *
	 * @param $create حالت
	 *        	ساخت یا به روز رسانی را تعیین می‌کند
	 */
	function preSave($create = false) {
		if ($this->id == '') {
			$this->creation_dtime = gmdate ( 'Y-m-d H:i:s' );
			$this->access_count = 0;
		}
		$this->modif_dtime = gmdate ( 'Y-m-d H:i:s' );
	}
	
	/**
	 * حالت آپارتمان ایجاد شده را به روز می‌کند
	 *
	 * @see Pluf_Model::postSave()
	 */
	function postSave($create = false) {
		//
	}
}