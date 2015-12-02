<?php
Pluf::loadFunction ( 'Pluf_HTTP_URL_urlForView' );

/**
 * فرم به روز رسانی آپارتمان
 *
 * این فرم تمام داده‌های یک آپارتمان را دریافت کرده و آپارتمان معادل را
 * به روز می‌کند. در صورتی که خطایی در این سطح رخ دهد به صورت استثنا صادر
 * خواهد شد
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class HM_Form_Profile extends Pluf_Form {
	var $apartment = null;
	public function initFields($extra = array()) {
		$this->apartment = $extra ['user_profile'];
		
		$this->fields ['title'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'title' ),
				'initial' => $this->apartment->title
		) );
		$this->fields ['state'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'state' ),
				'initial' => $this->apartment->state
		) );
		$this->fields ['city'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'city' ),
				'initial' => $this->apartment->city
		) );
		$this->fields ['country'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'country' ),
				'initial' => $this->apartment->country
		) );
		$this->fields ['address'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'address' ),
				'initial' => $this->apartment->address
		) );
		$this->fields ['postal_code'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'postal_code' ),
				'initial' => $this->apartment->postal_code
		) );
		$this->fields ['phone_number'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'phone number' ),
				'initial' => $this->apartment->phone_number
		) );
		$this->fields ['mobile_number'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'mobile number' ),
				'initial' => $this->apartment->mobile_number
		) );
		$this->fields ['national_id'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'national id' ),
				'initial' => $this->apartment->national_id
		) );
		$this->fields ['shaba'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'shaba' ),
				'initial' => $this->apartment->shaba
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
	function save($commit = true) {
		if (! $this->isValid ()) {
			throw new Pluf_Exception ( __ ( 'Cannot save the apartment from an invalid form.' ) );
		}
		// Set attributes
		$this->apartment->setFromFormData ( $this->cleaned_data );
		if ($commit) {
			if (! $this->apartment->create ()) {
				throw new Pluf_Exception ( __ ( 'Fail to update the apartment.' ) );
			}
		}
		return $this->apartment;
	}
	
	/**
	 * موجودیت را به روز می‌کند.
	 *
	 * @param string $commit        	
	 * @throws Pluf_Exception
	 * @return Ambigous <unknown, Advisor_Models_UserProfile>
	 */
	function update($commit = true) {
		if (! $this->isValid ()) {
			throw new Pluf_Exception ( __ ( 'Cannot save the apartment from an invalid form.' ) );
		}
		// Set attributes
		$this->apartment->setFromFormData ( $this->cleaned_data );
		if ($commit) {
			if (! $this->apartment->update ()) {
				throw new Pluf_Exception ( __ ( 'Fail to update the apartment.' ) );
			}
		}
		return $this->apartment;
	}
	
	/**
	 * تمام داده‌های تهی پاک می‌شوند
	 * 
	 * @see Pluf_Form::clean()
	 */
	public function clean() {
		foreach ( $this->cleaned_data as $key => $value ) {
			if (is_null ( $value ) || $value === '')
				unset ( $this->cleaned_data [$key] );
		}
		return $this->cleaned_data;
	}
}

