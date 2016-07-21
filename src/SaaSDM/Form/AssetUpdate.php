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
        //$this->tenant = $extra['tenant'];
        
        $this->fields['name'] = new Pluf_Form_Field_Varchar(array(
            'required' => false,
            'label' => 'Name of Asset',
            'initial' => $this->asset->name,
            'help_text' => 'Name of Asset'
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
        
       
//         $this->fields['file'] = new Pluf_Form_Field_File(array(
//             'required' => false,
//             'max_size' => Pluf::f('upload_max_size', 2097152),
//             'move_function_params' => array(
//                 'upload_path' => Pluf::f('upload_path') . '/' . $this->tenant->id . '/cms',
//                 'file_name' => $this->asset->id,
//                 'upload_path_create' => true,
//                 'upload_overwrite' => true
//             )
//         ));
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
            // $myFile = $this->data['file'];
            $this->asset->file_name = $myFile['name'];
            $fileInfo = SaaS_FileUtil::getMimeType($this->asset->file_name);
            $this->asset->mime_type = $fileInfo[0];
            // $this->content->file_name = $this->cleaned_data['file'];
            // $fileInfo = SaaS_FileUtil::getMimeType($this->content->file_path . '/' . $this->content->id);
            // $this->content->mime_type = $fileInfo[0];
            $this->asset->file_size = filesize($this->asset->file_path . '/' . $this->asset->id);
        }
        
        if ($commit) {
            $this->asset->update();
        }
        return $this->asset;
    }
}
