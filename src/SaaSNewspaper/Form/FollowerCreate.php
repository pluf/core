<?php

/**
 * ایجاد یک دنبال‌کننده جدید
 *
 * با استفاده از این فرم می‌توان یک دنبا‌ل‌کننده جدید را ایجاد کرد.
 * 
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *
 */
class SaaSNewspaper_Form_FollowerCreate extends Pluf_Form
{

    public $tenant = null;

    public function initFields ($extra = array())
    {
        $this->tenant = $extra['tenant'];
        
        $this->fields['email'] = new Pluf_Form_Field_Email(
                array(
                        'required' => true,
                        'label' => 'Email',
                        'help_text' => 'Your Email'
                ));
    }

    function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception('cannot save the follower from an invalid form');
        }
        // Create the follower
        $follower = new SaaSNewspaper_Follower();
        $follower->setFromFormData($this->cleaned_data);
        $follower->tenant = $this->tenant;
        if ($commit) {
            $follower->create();
        }
        return $follower;
    }
}
