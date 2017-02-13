<?php

/**
 * ایجاد یک پلن جدید
 *
 * با استفاده از این فرم می‌توان یک پلن جدید را ایجاد کرد.
 * 
 * @author Mahdi
 *
 */
class SaaSDM_Form_PlanCreate extends Pluf_Form
{

    public $user = null;

    public function initFields ($extra = array())
    {
		$this->user = $extra['user'];

        $this->fields[ 'templateId' ] = new Pluf_Form_Field_Varchar(
        		array(
        				'required' => true,
        				'label' => 'PlanTemplateID',
        				'help_text' => 'Id of plan template'
        		)); 
    }

    function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception('cannot save the plan from an invalid form');
        }
        // Create the plan
        $plan = new SaaSDM_Plan();
        
        //Checking existnce of plan template
        $plantemplate = SaaSDM_Shortcuts_GetPlanTemplateOr404( $this->cleaned_data ['templateId']);
        $plan->period = $plantemplate->period;
        $plan->max_count = $plantemplate->max_count;
        $plan->max_volume = $plantemplate->max_volume;
        $plan->remain_count = $plantemplate->max_count;
        $plan->remain_volume = $plantemplate->max_volume;
        $plan->active = false;
        $plan->price = $plantemplate->price;
        //TODO: add relation to BANK payments
        
        $plan->user = $this->user;
        
        $plan->setFromFormData($this->cleaned_data);
        if ($commit) {
            $plan->create();
        }
        return $plan;
    }
}
