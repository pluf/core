<?php

/**
 * به روزرسانی یک دنبال‌کننده
*
* با استفاده از این فرم می‌توان اطلاعات یک دنبال‌کننده را به روزرسانی کرد.
*
* @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
*
*/
class SaaSNewspaper_Form_FollowerUpdate extends Pluf_Form
{

// 	public $user = null;
	public $follower = null;

	public function initFields ($extra = array())
	{
// 		$this->user = $extra['user'];
		$this->follower = $extra['follower'];
		
		$this->fields['address'] = new Pluf_Form_Field_Varchar(
				array(
						'required' => false,
						'label' => 'Address',
						'initial' => $this->follower->address,
						'help_text' => 'Address of follower'
				));
		
		$this->fields['type'] = new Pluf_Form_Field_Varchar(
				array(
						'required' => false,
						'label' => 'Type',
						'initial' => $this->follower->type,
						'help_text' => 'Type of address'
				));
		
		$this->fields['validated'] = new Pluf_Form_Field_Varchar(
				array(
						'required' => false,
						'label' => 'Validated',
						'initial' => $this->follower->validated,
						'help_text' => 'Validation state of follower'
				));
	}

	function update ($commit = true)
	{
		if (! $this->isValid()) {
			throw new Pluf_Exception('cannot save the follower from an invalid form');
		}
		// update the follower
		$this->follower->setFromFormData($this->cleaned_data);
		if ($commit) {
			$this->follower->update();
		}
		return $this->follower;
	}
}
