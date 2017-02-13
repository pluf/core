<?php

/**
 * به روزرسانی یک پلن
 *
 * با استفاده از این فرم می‌توان اطلاعات یک پلن را به روزرسانی کرد.
 *
 * @author Mahdi
 *
 */
class SaaSDM_Form_PlanUpdate extends Pluf_Form
{
    
    // public $user = null;
    public $plan = null;

    public $tenant = null;

    public function initFields($extra = array())
    {
        // $this->user = $extra['user'];
        $this->plan = $extra['plan'];
        $this->tenant = Pluf_Tenant::current();
        
        $this->fields['name'] = new Pluf_Form_Field_Varchar(array(
            'required' => false,
            'label' => 'Name of Plan',
            'initial' => $this->plan->name,
            'help_text' => 'Name of Plan'
        ));
        $this->fields['path'] = new Pluf_Form_Field_Varchar(array(
        		'required' => false,
        		'label' => 'Path of Plan',
        		'initial' => $this->plan->path,
        		'help_text' => 'Path of Plan'
        ));
        $this->fields['size'] = new Pluf_Form_Field_Varchar(array(
        		'required' => false,
        		'label' => 'Size of Plan',
        		'initial' => $this->plan->size,
        		'help_text' => 'Size of Plan'
        ));
        $this->fields['download'] = new Pluf_Form_Field_Varchar(array(
        		'required' => false,
        		'label' => 'download of Plan',
        		'initial' => $this->plan->download,
        		'help_text' => 'download of Plan'
        ));
        $this->fields['driver_type'] = new Pluf_Form_Field_Varchar(array(
        		'required' => false,
        		'label' => 'driver_type of Plan',
        		'initial' => $this->plan->driver_type,
        		'help_text' => 'driver_type of Plan'
        ));
        $this->fields['driver_id'] = new Pluf_Form_Field_Varchar(array(
        		'required' => false,
        		'label' => 'driver_id of Plan',
        		'initial' => $this->plan->driver_id,
        		'help_text' => 'driver_id of Plan'
        ));
        $this->fields['type'] = new Pluf_Form_Field_Varchar(array(
        		'required' => false,
        		'label' => 'type of Plan',
        		'initial' => $this->plan->type,
        		'help_text' => 'type of Plan'
        ));
        $this->fields['content_name'] = new Pluf_Form_Field_Varchar(array(
        		'required' => false,
        		'label' => 'content_name of Plan',
        		'initial' => $this->plan->content_name,
        		'help_text' => 'content_name of Plan'
        ));
        $this->fields['description'] = new Pluf_Form_Field_Varchar(array(
        		'required' => false,
        		'label' => 'description of Plan',
        		'initial' => $this->plan->description,
        		'help_text' => 'description of Plan'
        ));
        
        
        $this->fields['parent'] = new Pluf_Form_Field_Varchar(array(
            'required' => false,
            'label' => 'Parent',
            'initial' => $this->plan->parent,
            'help_text' => 'Parent of plan'
        ));
        
        $this->fields['file'] = new Pluf_Form_Field_File(array(
            'required' => false,
            'max_size' => Pluf::f('upload_max_size', 2097152),
            'move_function_params' => array(
                'upload_path' => Pluf::f('upload_path') . '/' . $this->tenant->id . '/dm',
                'file_name' => $this->plan->id,
                'upload_path_create' => true,
                'upload_overwrite' => true
            )
        ));
    }

    function update($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception('cannot save the plan from an invalid form');
        }
        // update the plan
        $this->plan->setFromFormData($this->cleaned_data);
        $this->plan->tenant = $this->tenant;
        if ($commit) {
            $this->plan->update();
        }
        return $this->plan;
    }
}
