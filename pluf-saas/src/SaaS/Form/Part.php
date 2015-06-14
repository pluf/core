<?php
Pluf::loadFunction ( 'Pluf_HTTP_URL_urlForView' );

/**
 * فرم به روز رسانی و ایجاد واحد
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 */
class HM_Form_Part extends Pluf_Form {
	/**
	 * واحد مورد نظر
	 *
	 * @var unknown
	 */
	var $part = null;
	var $apartment = null;
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see Pluf_Form::initFields()
	 */
	public function initFields($extra = array()) {
		$this->apartment = $extra ['apartment'];
		$this->part = $extra ['part'];
		
		$property = array (
				'label' => __ ( 'title' ),
				'initial' => '',
				'widget_attrs' => array (
						'maxlength' => 50 
				) 
		);
		$this->fields ['title'] = new Pluf_Form_Field_Varchar ( $property );
		
		$property = array (
				'label' => __ ( 'count' ) 
		);
		$this->fields ['count'] = new Pluf_Form_Field_Integer ( $property );
		
		$property = array (
				'label' => __ ( 'part number' ) 
		);
		$this->fields ['part_number'] = new Pluf_Form_Field_Varchar ( $property );
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
	public function save() {
		if (! $this->isValid ()) {
			throw new Pluf_Exception ( __ ( 'Cannot save the apartment from an invalid form.' ) );
		}
		// Set attributes
		$part = new HM_Models_Part ();
		$part->title = $this->cleaned_data ['title'];
		$part->count = $this->cleaned_data ['count'];
		$part->part_number = $this->cleaned_data ['part_number'];
		$part->apartment = $this->apartment;
		
		if (! $part->create ()) {
			throw new Pluf_Exception ( __ ( 'Fail to update the apartment.' ) );
		}
		return $part;
	}
	
	/**
	 * مدل داده‌ای را به روز می‌کند.
	 *
	 * @throws Pluf_Exception
	 */
	public function update() {
		if (! $this->isValid ()) {
			throw new Pluf_Exception ( __ ( 'Cannot save the apartment from an invalid form.' ) );
		}
		// Set attributes
		if (isset ( $this->cleaned_data ['title'] )) {
			$this->part->title = $this->cleaned_data ['title'];
		}
		if (isset ( $this->cleaned_data ['count'] )) {
			$this->part->count = $this->cleaned_data ['count'];
		}
		if (isset ( $this->cleaned_data ['part_number'] )) {
			$this->part->part_number = $this->cleaned_data ['part_number'];
		}
		
		if (! $this->part->update ()) {
			throw new Pluf_Exception ( __ ( 'Fail to update the apartment.' ) );
		}
		return $this->part;
	}
}

