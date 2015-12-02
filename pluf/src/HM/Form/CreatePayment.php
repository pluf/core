<?php
Pluf::loadFunction ( 'Pluf_HTTP_URL_urlForView' );
Pluf::loadFunction ( 'HM_Shortcuts_paymentFactory' );

/**
 * فرم ایجاد یک پرداخت
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 */
class HM_Form_CreatePayment extends Pluf_Form {
	/**
	 * واحد مورد نظر
	 *
	 * @var unknown
	 */
	var $part = null;
	var $payment = null;
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see Pluf_Form::initFields()
	 */
	public function initFields($extra = array()) {
		$this->part = $extra ['part'];
		if (array_key_exists ( 'payment', $extra )) {
			$this->payment = $extra ['payment'];
		}
		$this->payment = HM_Shortcuts_paymentFactory ( $this->payment );
		
		
		$this->fields ['title'] = new Pluf_Form_Field_Varchar ( array (
				'required' => true,
				'label' => __ ( 'title' ),
				'initial' => $this->payment->title
		) );
		$this->fields ['description'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'description' ),
				'initial' => $this->payment->description
		) );
		$this->fields ['amount'] = new Pluf_Form_Field_Integer ( array (
				'required' => true,
				'label' => __ ( 'amount' ),
				'initial' => $this->payment->amount
		) );
	}
	
	/**
	 * مدل داده‌ای را ذخیره می‌کند
	 *
	 * مدل داده‌ای را بر اساس تغییرات تعیین شده توسط کاربر به روز می‌کند. در صورتی
	 * که پارامتر ورودی با نا درستی مقدار دهی شود تغییراد ذخیره نمی شود در غیر این
	 * صورت داده‌ها در پایگاه داده ذخیره می‌شود.
	 *
	 * @param $commit داده‌ها
	 *        	ذخیره شود یا نه
	 * @return مدل داده‌ای تغییر یافته
	 */
	public function save($commit = true) {
		if (! $this->isValid ()) {
			throw new Pluf_Exception ( __ ( 'Cannot save the payment from an invalid form.' ) );
		}
		// Set attributes
		$this->payment->setFromFormData ( $this->cleaned_data );
		$this->payment->part = $this->part;
		if ($commit) {
			if (! $this->payment->create ()) {
				throw new Pluf_Exception ( __ ( 'Fail to update the apartment.' ) );
			}
		}
		return $this->payment;
	}
	
	/**
	 * داده‌های ایجاد شده در فرم را در مدل داده‌ای قرار می‌دهد
	 * 
	 * @param unknown $payment
	 */
	public function fill($payment = null) {
		$payment = HM_Shortcuts_paymentFactory ( $payment );
		$payment->setFromFormData ( $this->cleaned_data );
		$payment->part = $this->part;
		return $payment;
	}

}

