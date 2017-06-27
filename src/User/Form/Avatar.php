<?php

/**
 * به روزرسانی یک محتوا
 *
 * با استفاده از این فرم می‌توان اطلاعات یک محتوا را به روزرسانی کرد.
 *
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *
 */
class User_Form_Avatar extends Pluf_Form_Model
{

    var $user;

    public function initFields ($extra = array())
    {
        $this->user = $extra['user'];
        parent::initFields($extra);
        
        // if (! is_dir($content->file_path)) {
        // if (false == @mkdir($content->file_path, 0777, true)) {
        // throw new Pluf_Form_Invalid(
        // 'An error occured when creating the upload path. Please try to send
        // the file again.');
        // }
        // }
        $tenant = Pluf_Tenant::current();
        $path = $tenant->storagePath() . '/avatar';
        $this->fields['file'] = new Pluf_Form_Field_File(
                array(
                        'required' => true,
                        'max_size' => Pluf::f('user_avatra_max_size', 2097152),
                        'move_function_params' => array(
                                'upload_path' => $path,
                                'file_name' => $this->user->id,
                                'upload_path_create' => true,
                                'upload_overwrite' => true
                        )
                ));
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Pluf_Form_Model::save()
     */
    function save ($commit = true)
    {
        $model = parent::save(false);
        
        // update the content
        {
            // Extract information of file
            $myFile = $this->data['file'];
            
            $tenant = Pluf_Tenant::current();
            
            $model->fileName = $myFile['name'];
            $model->filePath = $tenant->storagePath() . '/avatar';
            $model->user = $this->user;
        }
        
        if ($commit && $model->id) {
            $model->update();
        } elseif ($commit) {
            $model->create();
        }
        return $model;
    }
}
