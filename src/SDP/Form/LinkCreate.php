<?php
/**
 * ایجاد یک لینک امن جدید
 *
 * با استفاده از این فرم می‌توان یک لینک امن جدید را ایجاد کرد.
 *
 * @author Mahdi
 *
 */
class SDP_Form_LinkCreate extends Pluf_Form
{

	public $asset = null;

	public function initFields ($extra = array())
	{
		$this->asset = $extra['asset'];
		
		$this->user = $extra['user'];

		$this->fields['secure_link'] = new Pluf_Form_Field_Varchar(
				array(
						'required' => false,
						'label' => 'SecureLink',
						'help_text' => 'Secure Link'
				));
		$this->fields['expiry'] = new Pluf_Form_Field_Varchar(
				array(
						'required' => false,
						'label' => 'expiry',
						'help_text' => 'Datetime of expiry'
				));
		$this->fields['download'] = new Pluf_Form_Field_Integer(
				array(
						'required' => false,
						'label' => 'Path',
						'help_text' => 'Path of asset'
				));
	}

	function save ($commit = true)
	{
		if (! $this->isValid()) {
			throw new Pluf_Exception('cannot save the link from an invalid form');
		}
		// Create the link
		$link = new SDP_Link();
		$link->secure_link = chunk_split(substr(md5(time().rand(10000,99999)), 0, 20), 6, '');
		//TODO: mahdi: Set expiry time here
		$link->expiry = date('Y-m-d H:i:s' , strtotime(' +1 day'));
		$link->setFromFormData($this->cleaned_data);
		$link->asset = $this->asset;
		if ($commit) {
			$link->create();
		}
		return $link;
	}
}
