<?php

/**
 * به روزرسانی یک قالب پلن
 *
 * با استفاده از این فرم می‌توان اطلاعات یک قالب پلن را به روزرسانی کرد.
 *
 * @author Mahdi
 *
 */
class SaaSDM_Form_PlanTemplateUpdate extends Pluf_Form
{
    
    public $plantemplate = null;

    public function initFields($extra = array())
    {
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
    }

    function update($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception('cannot save(update) the plan template from an invalid form');
        }
        // update the asset
        $this->plantemplate->setFromFormData($this->cleaned_data);
        
        if (array_key_exists('file', $this->cleaned_data)) {
            // Extract information of file
            $myFile = $this->data['file'];
            $this->plantemplate->file_name = $myFile['name'];
            $fileInfo = Pluf_FileUtil::getMimeType($this->plantemplate->file_name);
            $this->plantemplate->type = $fileInfo[0];
            // $this->content->file_name = $this->cleaned_data['file'];
            // $fileInfo = Pluf_FileUtil::getMimeType($this->content->file_path . '/' . $this->content->id);
            // $this->content->mime_type = $fileInfo[0];
            $this->plantemplate->size = filesize($this->plantemplate->path . '/' . $this->plantemplate->id);
        }
        
        if ($commit) {
            $this->plantemplate->update();
        }
        return $this->plantemplate;
    }
}
