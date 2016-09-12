<?php

/**
 * ایجاد یک اکانت جدید
 *
 * با استفاده از این فرم می‌توان یک اکانت جدید را ایجاد کرد.
 * 
 * @author Mahdi
 *
 */
class SaaSDM_Form_AccountCreate extends Pluf_Form
{

    public $tenant = null;
//     public $user = null;

    public function initFields ($extra = array())
    {
        $this->tenant = $extra['tenant'];
//         $this->user = $extra['user'];

        $this->fields['expiry'] = new Pluf_Form_Field_Integer(
                array(
                        'required' => false,
                        'label' => 'Expiry',
                        'help_text' => 'Date of Expiry'
                ));
        
        $this->fields['active'] = new Pluf_Form_Field_Boolean(
        		array(
        				'required' => false,
        				'label' => 'Active',
        				'help_text' => 'Account is active or NOT'
        		));

        $this->fields['tenant'] = new Pluf_Form_Field_Integer(
        		array(
        				'required' => false,
        				'label' => 'tenant',
        				'help_text' => 'Related Tenant of asset'
        		));        
    }

    function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception('cannot save the account from an invalid form');
        }
        // Create the asset
        $account = new SaaSDM_Account();
        $account->setFromFormData($this->cleaned_data);
//         $asset->user = $this->user;
        $account->tenant = $this->tenant;
        if ($commit) {
            $account->create();
        }
        return $account;
    }
}
