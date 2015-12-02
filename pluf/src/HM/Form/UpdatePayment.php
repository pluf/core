<?php
Pluf::loadFunction ( 'Pluf_HTTP_URL_urlForView' );
Pluf::loadFunction ( 'HM_Shortcuts_paymentFactory' );

/**
 * فرم ایجاد یک پرداخت
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 */
class HM_Form_UpdatePayment extends Pluf_Form {
	var $payment = null;
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see Pluf_Form::initFields()
	 */
	public function initFields($extra = array()) {
		if (array_key_exists ( 'payment', $extra )) {
			$this->payment = $extra ['payment'];
		}
		$this->location = HM_Shortcuts_paymentFactory ( $this->payment );
		
		$this->fields ['title'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'title' ),
				'initial' => $this->payment->title
		) );
		$this->fields ['description'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'description' ),
				'initial' => $this->payment->description
		) );
		$this->fields ['verified'] = new Pluf_Form_Field_Boolean( array (
				'required' => false,
				'label' => __ ( 'verified' ),
				'initial' => $this->payment->verified
		) );
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
			throw new Pluf_Exception ( __ ( 'Cannot save the payment from an invalid form.' ) );
		}
		// Set attributes
		$this->payment->setFromFormData ( $this->cleaned_data );
		if ($commit) {
			if (! $this->payment->update ()) {
				throw new Pluf_Exception ( __ ( 'Fail to update the apartment.' ) );
			}
		}
		return $this->payment;
	}

}

