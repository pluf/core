<?php

/**
 * به روزرسانی یک محتوا
 *
 * با استفاده از این فرم می‌توان اطلاعات یک محتوا را به روزرسانی کرد.
 *
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *
 */
class CMS_Form_ContentUpdate extends Pluf_Form_Model
{

    public $tenant = null;

    public function initFields($extra = array())
    {
        // $this->user = $extra['user'];
        // $this->content = $extra['content'];
        $this->tenant = $extra['tenant'];
        parent::initFields($extra);
        
        // $this->fields['title'] = new Pluf_Form_Field_Varchar(
        // array(
        // 'required' => false,
        // 'label' => 'Title',
        // 'initial' => $this->content->title,
        // 'help_text' => 'Title of content'
        // ));
        // $this->fields['description'] = new Pluf_Form_Field_Varchar(
        // array(
        // 'required' => false,
        // 'label' => 'Description',
        // 'initial' => $this->content->description,
        // 'help_text' => 'Description about content'
        // ));
        // $this->fields['file_name'] = new Pluf_Form_Field_Varchar(
        // array(
        // 'required' => false,
        // 'label' => 'File Name',
        // 'initial' => $this->content->file_name,
        // 'help_text' => 'Name for file related to content'
        // ));
        // $this->fields['mime_type'] = new Pluf_Form_Field_Varchar(
        // array(
        // 'required' => false,
        // 'label' => 'MIME Type',
        // 'initial' => $this->content->mime_type,
        // 'help_text' => 'MIME type of content'
        // ));
        
        $this->fields['file'] = new Pluf_Form_Field_File(array(
            'required' => false,
            'max_size' => Pluf::f('upload_max_size', 2097152),
            'move_function_params' => array(
                'upload_path' => $this->model->file_path,
                'file_name' => $this->model->id,
                'upload_path_create' => true,
                'upload_overwrite' => true
            )
        ));
    }

    public function clean_name()
    {
        $name = $this->cleaned_data['name'];
        if (empty($name))
            return null;
            // Note: If old name is same as new name we do not check uniqueness of the name.
        if (strcmp($name, $this->model->name) === 0) {
            return $name;
        }
        return CMS_Shortcuts_CleanName($name, $this->tenant);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Pluf_Form_Model::save()
     */
    function save($commit = true)
    {
        $model = parent::save(false);
        // update the content
        if (array_key_exists('file', $this->cleaned_data)) {
            // Extract information of file
            $myFile = $this->data['file'];
            $model->file_name = $myFile['name'];
            // $fileInfo =
            // SaaS_FileUtil::getMimeType($this->content->file_name);
            // $model->mime_type = $fileInfo[0];
            // $this->content->file_name = $this->cleaned_data['file'];
            // $fileInfo = SaaS_FileUtil::getMimeType($this->content->file_path
            // . '/' . $this->content->id);
            // $this->content->mime_type = $fileInfo[0];
            // $model->file_size = filesize($model->getPath());
        }
        
        if ($commit) {
            $model->update();
        }
        return $model;
    }
}
