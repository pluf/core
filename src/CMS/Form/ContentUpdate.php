<?php

/**
 * به روزرسانی یک محتوا
 *
 * با استفاده از این فرم می‌توان اطلاعات یک محتوا را به روزرسانی کرد.
 *
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *
 */
class CMS_Form_ContentUpdate extends Pluf_Form
{
    
    // public $user = null;
    public $content = null;

    public $tenant = null;

    public function initFields($extra = array())
    {
        // $this->user = $extra['user'];
        $this->content = $extra['content'];
        $this->tenant = $extra['tenant'];
        
        $this->fields['title'] = new Pluf_Form_Field_Varchar(array(
            'required' => false,
            'label' => 'Title',
            'initial' => $this->content->title,
            'help_text' => 'Title of content'
        ));
        
        $this->fields['description'] = new Pluf_Form_Field_Varchar(array(
            'required' => false,
            'label' => 'Description',
            'initial' => $this->content->description,
            'help_text' => 'Description about content'
        ));
        
        $this->fields['file_name'] = new Pluf_Form_Field_Varchar(array(
            'required' => false,
            'label' => 'File Name',
            'initial' => $this->content->file_name,
            'help_text' => 'Name for file related to content'
        ));
        
        $this->fields['mime_type'] = new Pluf_Form_Field_Varchar(array(
            'required' => false,
            'label' => 'MIME Type',
            'initial' => $this->content->mime_type,
            'help_text' => 'MIME type of content'
        ));
        
        $this->fields['file'] = new Pluf_Form_Field_File(array(
            'required' => false,
            'max_size' => Pluf::f('upload_max_size', 2097152),
            'move_function_params' => array(
                'upload_path' => Pluf::f('upload_path') . '/' . $this->tenant->id . '/cms',
                'file_name' => $this->content->id,
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
        // update the content
        $this->content->setFromFormData($this->cleaned_data);
        
        if (array_key_exists('file', $this->cleaned_data)) {
            // Extract information of file
            $myFile = $this->data['file'];
            $this->content->file_name = $myFile['name'];
            $fileInfo = SaaS_FileUtil::getMimeType($this->content->file_name);
            $this->content->mime_type = $fileInfo[0];
            // $this->content->file_name = $this->cleaned_data['file'];
            // $fileInfo = SaaS_FileUtil::getMimeType($this->content->file_path . '/' . $this->content->id);
            // $this->content->mime_type = $fileInfo[0];
            $this->content->file_size = filesize($this->content->file_path . '/' . $this->content->id);
        }
        
        if ($commit) {
            $this->content->update();
        }
        return $this->content;
    }
}
