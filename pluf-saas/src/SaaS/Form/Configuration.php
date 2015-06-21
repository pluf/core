<?php
Pluf::loadFunction ( 'Pluf_HTTP_URL_urlForView' );
Pluf::loadFunction ( 'SaaS_Shortcuts_configurationFactory' );

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Form_Configuration extends Pluf_Form {
	var $application = null;
	var $config = null;
	
	/**
	 * (non-PHPdoc)
	 * @see Pluf_Form::initFields()
	 */
	public function initFields($extra = array()) {
		$this->application = $extra ['application'];
		if (array_key_exists ( 'configuration', $extra ))
			$this->config = $extra ['configuration'];
		$this->config = SaaS_Shortcuts_configurationFactory ( $this->config );
		
		$this->fields ['key'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'key' ),
				'initial' => $this->config->key 
		) );
		$this->fields ['value'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'value' ),
				'initial' => $this->config->value 
		) );
		$this->fields ['description'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'description' ),
				'initial' => $this->config->description 
		) );
		$this->fields ['type'] = new Pluf_Form_Field_Integer ( array (
				'required' => false,
				'label' => __ ( 'type' ),
				'initial' => $this->config->type 
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
			throw new Pluf_Exception ( __ ( 'Cannot save the configuration from an invalid form.' ) );
		}
		// Set attributes
		$this->config->setFromFormData ( $this->cleaned_data );
		$this->config->application = $this->application;
		if ($commit) {
			if (! $this->config->create ()) {
				throw new Pluf_Exception ( __ ( 'Fail to update the configuration.' ) );
			}
		}
		return $this->config;
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
			throw new Pluf_Exception ( __ ( 'Cannot save the configuration from an invalid form.' ) );
		}
		// Set attributes
		$this->config->setFromFormData ( $this->cleaned_data );
		if ($commit) {
			if (! $this->config->update ()) {
				throw new Pluf_Exception ( __ ( 'Fail to update the configuration.' ) );
			}
		}
		return $this->config;
	}
}

