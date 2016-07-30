<?php

/**
 * ایجاد یک دارایی جدید
 *
 * با استفاده از این فرم می‌توان یک دارایی جدید را ایجاد کرد.
 * 
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *
 */
class SaaSDM_Form_AssetCreate extends Pluf_Form
{

    public $tenant = null;
//     public $user = null;

    public function initFields ($extra = array())
    {
        $this->tenant = $extra['tenant'];
//         $this->user = $extra['user'];

        $this->fields['name'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => 'Name',
                        'help_text' => 'Name of asset'
                ));
        
        $this->fields['path'] = new Pluf_Form_Field_Varchar(
        		array(
        				'required' => false,
        				'label' => 'Path',
        				'help_text' => 'Path of asset'
        		));
        $this->fields['size'] = new Pluf_Form_Field_Integer(
        		array(
        				'required' => false,
        				'label' => 'Path',
        				'help_text' => 'Path of asset'
        		));
        $this->fields['download'] = new Pluf_Form_Field_Integer(
        		array(
        				'required' => false,
        				'label' => 'Path',
        				'help_text' => 'Path of asset'
        		));
        $this->fields['driver_type'] = new Pluf_Form_Field_Varchar(
        		array(
        				'required' => false,
        				'label' => 'Path',
        				'help_text' => 'Path of asset'
        		));
        $this->fields['driver_id'] = new Pluf_Form_Field_Integer(
        		array(
        				'required' => false,
        				'label' => 'Path',
        				'help_text' => 'Path of asset'
        		));
        $this->fields['parent'] = new Pluf_Form_Field_Integer(
        		array(
        				'required' => false,
        				'label' => 'Path',
        				'help_text' => 'Path of asset'
        		));
        $this->fields['type'] = new Pluf_Form_Field_Varchar(
        		array(
        				'required' => false,
        				'label' => 'Path',
        				'help_text' => 'Path of asset'
        		));
        $this->fields['content_name'] = new Pluf_Form_Field_Varchar(
        		array(
        				'required' => false,
        				'label' => 'Path',
        				'help_text' => 'Path of asset'
        		));
        $this->fields['description'] = new Pluf_Form_Field_Varchar(
        		array(
        				'required' => false,
        				'label' => 'Path',
        				'help_text' => 'Path of asset'
        		));
        $this->fields['tenant'] = new Pluf_Form_Field_Integer(
        		array(
        				'required' => false,
        				'label' => 'Path',
        				'help_text' => 'Path of asset'
        		));        
    }

    function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception('cannot save the asset from an invalid form');
        }
        // Create the asset
        $asset = new SaaSDM_Asset();
        $asset->setFromFormData($this->cleaned_data);
        $asset->path = Pluf::f('upload_path') . '/' . $this->tenant->id . '/dm';
        if(!is_dir($asset->path)) {
        	if (false == @mkdir($asset->path, 0777, true)) {
        		throw new Pluf_Form_Invalid('An error occured when creating the upload path. Please try to send the file again.');
        	}
        }
//         $asset->user = $this->user;
        $asset->tenant = $this->tenant;
        if ($commit) {
            $asset->create();
        }
        return $asset;
    }
}
