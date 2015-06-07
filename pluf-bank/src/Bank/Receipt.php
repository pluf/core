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
		$this->_a ['table'] = 'hm_payment';
		$this->_a ['model'] = 'HM_Models_Receipt';
		$this->_model = 'HM_Models_Receipt';
		$this->_a ['cols'] = array (
				'sequence' => array (
						'type' => 'Pluf_DB_Field_Sequence',
						'blank' => true 
						'verbose' => __ ( 'unique and no repreducable id fro reception' ) 
				),
				'amount' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'unique' => false 
						'verbose' => __ ( 'amount of reception' ) 
				),
				'title' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => false,
						'size' => 50 
						'verbose' => __ ( 'title of reception' ) 
				),
				'description' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 200 
						'verbose' => __ ( 'description of reception' ) 
				),
				
				'trans_id' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 200 
						'verbose' => __ ( 'successfull transaction id from bank' ) 
				),
				'verification' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 200 
						'verbose' => __ ( 'verification of the receipt from the bank' ) 
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