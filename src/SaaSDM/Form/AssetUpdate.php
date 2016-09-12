<?php

/**
 * به روزرسانی یک دارایی
 *
 * با استفاده از این فرم می‌توان اطلاعات یک دارایی را به روزرسانی کرد.
 *
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *
 */
class SaaSDM_Form_AssetUpdate extends Pluf_Form
{
    
    // public $user = null;
    public $asset = null;

    public $tenant = null;

    public function initFields($extra = array())
    {
        // $this->user = $extra['user'];
        $this->asset = $extra['asset'];
        $this->tenant = $extra['tenant'];
        
        $this->fields['name'] = new Pluf_Form_Field_Varchar(array(
            'required' => false,
            'label' => 'Name of Asset',
            'initial' => $this->asset->name,
            'help_text' => 'Name of Asset'
        ));
        $this->fields['path'] = new Pluf_Form_Field_Varchar(array(
        		'required' => false,
        		'label' => 'Path of Asset',
        		'initial' => $this->asset->path,
        		'help_text' => 'Path of Asset'
        ));
        $this->fields['size'] = new Pluf_Form_Field_Varchar(array(
        		'required' => false,
        		'label' => 'Size of Asset',
        		'initial' => $this->asset->size,
        		'help_text' => 'Size of Asset'
        ));
        $this->fields['download'] = new Pluf_Form_Field_Varchar(array(
        		'required' => false,
        		'label' => 'download of Asset',
        		'initial' => $this->asset->download,
        		'help_text' => 'download of Asset'
        ));
        $this->fields['driver_type'] = new Pluf_Form_Field_Varchar(array(
        		'required' => false,
        		'label' => 'driver_type of Asset',
        		'initial' => $this->asset->driver_type,
        		'help_text' => 'driver_type of Asset'
        ));
        $this->fields['driver_id'] = new Pluf_Form_Field_Varchar(array(
        		'required' => false,
        		'label' => 'driver_id of Asset',
        		'initial' => $this->asset->driver_id,
        		'help_text' => 'driver_id of Asset'
        ));
        $this->fields['type'] = new Pluf_Form_Field_Varchar(array(
        		'required' => false,
        		'label' => 'type of Asset',
        		'initial' => $this->asset->type,
        		'help_text' => 'type of Asset'
        ));
        $this->fields['content_name'] = new Pluf_Form_Field_Varchar(array(
        		'required' => false,
        		'label' => 'content_name of Asset',
        		'initial' => $this->asset->content_name,
        		'help_text' => 'content_name of Asset'
        ));
        $this->fields['description'] = new Pluf_Form_Field_Varchar(array(
        		'required' => false,
        		'label' => 'description of Asset',
        		'initial' => $this->asset->description,
        		'help_text' => 'description of Asset'
        ));
        
        
        $this->fields['parent'] = new Pluf_Form_Field_Varchar(array(
            'required' => false,
            'label' => 'Parent',
            'initial' => $this->asset->parent,
            'help_text' => 'Parent of asset'
        ));
        
        $this->fields['tenant'] = new Pluf_Form_Field_Varchar(array(
            'required' => false,
            'label' => 'Tenant',
            'initial' => $this->asset->tenant,
            'help_text' => 'tenant that this asset belonged to'
        ));
        
       
        $this->fields['file'] = new Pluf_Form_Field_File(array(
            'required' => false,
            'max_size' => Pluf::f('upload_max_size', 2097152),
            'move_function_params' => array(
                'upload_path' => Pluf::f('upload_path') . '/' . $this->tenant->id . '/dm',
                'file_name' => $this->asset->id,
                'upload_path_create' => true,
                'upload_overwrite' => true
            )
        ));
    }

    function update($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception('cannot save the content from an invalid form');
        }
        // update the asset
        $this->asset->setFromFormData($this->cleaned_data);
        
        if (array_key_exists('file', $this->cleaned_data)) {
            // Extract information of file
            $myFile = $this->data['file'];
            $this->asset->file_name = $myFile['name'];
            $fileInfo = SaaS_FileUtil::getMimeType($this->asset->file_name);
            $this->asset->mime_type = $fileInfo[0];
            
            // $this->content->file_name = $this->cleaned_data['file'];
            // $fileInfo = SaaS_FileUtil::getMimeType($this->content->file_path . '/' . $this->content->id);
            // $this->content->mime_type = $fileInfo[0];
            $this->asset->size = filesize($this->asset->path . '/' . $this->asset->id);
        }
        
        if ($commit) {
            $this->asset->update();
        }
        return $this->asset;
    }
}
