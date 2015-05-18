<?php
Pluf::loadFunction ( 'Pluf_HTTP_URL_urlForView' );
Pluf::loadFunction ( 'Label_Shortcuts_labelDateFactory' );

/**
 * فرم به روز رسانی اطلاعات کاربر را ایجاد می‌کند.
 */
class Label_Form_Label extends Pluf_Form {
	public $label_data = null;
	public $user;
	
	/**
	 * مقدار دهی فیلدها.
	 *
	 * @see Pluf_Form::initFields()
	 */
	public function initFields($extra = array()) {
		if (array_key_exists ( 'label', $extra ))
			$this->label_data = $extra ['label'];
		$this->user = $extra ['user'];
		$this->label_data = Label_Shortcuts_labelDateFactory ( $this->label_data );
		
		$this->fields ['title'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'title' ),
				'initial' => $this->label_data->title 
		) );
		
		$this->fields ['description'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'description' ),
				'initial' => $this->label_data->description 
		) );
		
		$this->fields ['color'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'color' ),
				'initial' => $this->label_data->color 
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
		$this->label_data->setFromFormData ( $this->cleaned_data );
		$this->label_data->user = $this->user;
		if ($commit) {
			$this->label_data->create ();
		}
		return $this->label_data;
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
		$this->label_data->setFromFormData ( $this->cleaned_data );
		// $this->label_data->user = $this->user;
		if ($commit) {
			$this->label_data->update ();
		}
		return $this->label_data;
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
