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

        $this->fields['title'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => 'Title',
                        'help_text' => 'Title of asset'
                ));
        
        $this->fields['description'] = new Pluf_Form_Field_Varchar(
        		array(
        				'required' => false,
        				'label' => 'Description',
        				'help_text' => 'Description about asset'
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
