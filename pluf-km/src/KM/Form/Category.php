<?php
Pluf::loadFunction ( 'Pluf_HTTP_URL_urlForView' );
Pluf::loadFunction ( 'KM_Shortcuts_categoryDateFactory' );

/**
 * فرم به روز رسانی اطلاعات کاربر را ایجاد می‌کند.
 */
class KM_Form_Category extends Pluf_Form {
	public $category_date = null;
	public $category_parent = null;
	public $user;
	
	/**
	 * مقدار دهی فیلدها.
	 *
	 * @see Pluf_Form::initFields()
	 */
	public function initFields($extra = array()) {
		if (array_key_exists ( 'category', $extra ))
			$this->category_date = $extra ['category'];
		$this->$category_parent = $extra ['parent'];
		$this->user = $extra ['user'];
		$this->category_date = KM_Shortcuts_categoryDateFactory ( $this->category_date );
		
		$this->fields ['title'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'title' ),
				'initial' => $this->category_date->title 
		) );
		
		$this->fields ['description'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'description' ),
				'initial' => $this->category_date->description 
		) );
		
		$this->fields ['color'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'color' ),
				'initial' => $this->category_date->color 
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
	 * @return مدل داده‌ای ایجاد شده
	 */
	function save($commit = true) {
		if (! $this->isValid ()) {
			throw new Pluf_Exception ( __ ( 'Cannot save the label from an invalid form.' ) );
		}
		$this->category_date->setFromFormData ( $this->cleaned_data );
		$this->category_date->user = $this->user;
		if ($commit) {
			$this->category_date->create ();
		}
		return $this->category_date;
	}
	
	/**
	 * داده‌های کاربر را به روز می‌کند.
	 *
	 * @throws Pluf_Exception
	 */
	function update($commit = true) {
		if (! $this->isValid ()) {
			throw new Pluf_Exception ( __ ( 'Cannot update the label from an invalid form.' ) );
		}
		$this->category_date->setFromFormData ( $this->cleaned_data );
		// $this->category_date->user = $this->user;
		if ($commit) {
			$this->category_date->update ();
		}
		return $this->category_date;
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
