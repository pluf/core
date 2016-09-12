<?php

/**
 * به روزرسانی یک اکانت
 *
 * با استفاده از این فرم می‌توان اطلاعات یک اکانت را به روزرسانی کرد.
 *
 * @author Mahdi
 *
 */
class SaaSDM_Form_AccountUpdate extends Pluf_Form {
	
	// public $user = null;
	public $account = null;
	public $tenant = null;
	public function initFields($extra = array()) {
		// $this->user = $extra['user'];
		$this->account = $extra ['account'];
		$this->tenant = $extra ['tenant'];
		
		$this->fields ['expiry'] = new Pluf_Form_Field_Integer ( array (
				'required' => false,
				'label' => 'Expiry date of an account',
				'initial' => $this->account->name,
				'help_text' => 'Expiry date' 
		) );
		$this->fields ['active'] = new Pluf_Form_Field_Boolean ( array (
				'required' => false,
				'label' => 'active',
				'help_text' => 'Account state' 
		) );
	}
	function update($commit = true) {
		if (! $this->isValid ()) {
			throw new Pluf_Exception ( 'cannot save the account from an invalid form' );
		}
		// update the account
		$this->account->setFromFormData ( $this->cleaned_data );

		if ($commit) {
			$this->account->update ();
		}
		return $this->account;
	}
}
