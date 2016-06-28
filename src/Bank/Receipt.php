<?php

/**
 * ساختارهای داده‌ای برای رسید را ایجاد می‌کند.
 * 
 * رسید عبارت است از یک مجموعه از داده‌ها که برای پرداخت به بانک ارسال 
 * می‌شود. این رسید زمانی که بانک تایید کند به روز شده و اطلاعات دریافتی
 * از بانک نیز به آن اضافه می شود.
 * 
 * از رسید در کارهای متفاوتی می‌توان استفاده کرد. برای نمونه پرداخت‌هایی
 * که برای خرید یک کالا توسط یک فرد انجام می‌شود در ساختار رسید قرار می‌گیرد.
 * 
 * @author maso
 *
 */
class Bank_Receipt extends Pluf_Model {
	
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
				'amount' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'unique' => false,
						'verbose' => 'amount of reception' 
				),
				'title' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => false,
						'size' => 50,
						'verbose' => 'title of reception' 
				),
				'description' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 200,
						'verbose' => 'description of reception' 
				),
				'trans_id' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 200,
						'verbose' => 'successfull transaction id from bank' 
				),
				'verification' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 200,
						'verbose' => 'verification of the receipt from the bank' 
				),
				'owner_id' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'verbose' => 'owner ID' 
				),
				'owner_class' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => false,
						'size' => 50,
						'verbose' => 'owner class',
						'help_text' => 'For example Pluf_User or Pluf_Group.' 
				),
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