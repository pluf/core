<?php

/**
 * ایجاد یک قالب پلن جدید
 *
 * با استفاده از این فرم می‌توان یک قالب جدید برای پلن ها را ایجاد کرد.
 * 
 * @author mahdi
 *
 */
class SaaSDM_Form_PlanTemplateCreate extends Pluf_Form
{

    public $tenant = null;
//     public $user = null;

    public function initFields ($extra = array())
    {
        $this->tenant = $extra['tenant'];
//         $this->user = $extra['user'];

        $this->fields['label'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => 'plantemplate',
                        'help_text' => 'label of a plan template'
                ));
        
        $this->fields['description'] = new Pluf_Form_Field_Varchar(
        		array(
        				'required' => false,
        				'label' => 'description',
        				'help_text' => 'description of a plan template'
        		));
        $this->fields['content_name'] = new Pluf_Form_Field_Varchar(
        		array(
        				'required' => false,
        				'label' => 'content_name',
        				'help_text' => 'Content Name of a plan template'
        		));
        $this->fields['period'] = new Pluf_Form_Field_Integer(
        		array(
        				'required' => false,
        				'label' => 'Period',
        				'help_text' => 'Duration of a plan template'
        		));
        $this->fields['max_count'] = new Pluf_Form_Field_Integer(
        		array(
        				'required' => false,
        				'label' => 'Path',
        				'help_text' => 'Maximum count of allowed downloads in a plan template'
        		));
        $this->fields['max_volume'] = new Pluf_Form_Field_Integer(
        		array(
        				'required' => false,
        				'label' => 'max_volume',
        				'help_text' => 'Maximum volume of plan template'
        		));
        $this->fields['price'] = new Pluf_Form_Field_Integer(
        		array(
        				'required' => false,
        				'label' => 'price',
        				'help_text' => 'Price of a plan template'
        		));
        $this->fields['off'] = new Pluf_Form_Field_Integer(
        		array(
        				'required' => false,
        				'label' => 'off',
        				'help_text' => 'Discount of a plan template'
        		));
        $this->fields['tenant'] = new Pluf_Form_Field_Integer(
        		array(
        				'required' => false,
        				'label' => 'Tenant',
        				'help_text' => 'Related Tenant of plan template'
        		));
    }

    function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception('cannot save the plan template from an invalid form');
        }
        // Create the plan template
        $plantemplate = new SaaSDM_PlanTemplate();
        $plantemplate->setFromFormData($this->cleaned_data);

//         $asset->user = $this->user;

        $plantemplate->tenant = $this->tenant;
        if ($commit) {
            $plantemplate->create();
        }
        return $plantemplate;
    }
}
