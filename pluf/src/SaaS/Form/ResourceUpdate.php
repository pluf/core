<?php

/**
 * Upload a file for download.
 *
 */
class SaaS_Form_ResourceUpdate extends SaaS_Form_ResourceCreate
{

    public $resource;

    public function initFields ($extra = array())
    {
//         $this->user = $extra['user'];
        $this->app = $extra['application'];
        $this->resource =  $extra['resource'];
        
        $this->fields['description'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('description'),
                        'initial' => ''
                ));
//         $this->fields['file'] = new Pluf_Form_Field_File(
//                 array(
//                         'required' => false,
//                         'label' => __('file'),
//                         'initial' => '',
//                         'max_size' => Pluf::f('upload_max_size', 2097152),
//                         'move_function_params' => array(
//                                 'upload_path' => Pluf::f('upload_path') . '/' .
//                                          $this->app->id . '/files',
//                                         'upload_path_create' => true,
//                                         'upload_overwrite' => false
//                         )
//                 ));
    }

    /**
     * Save the model in the database.
     *
     * @param
     *            bool Commit in the database or not. If not, the object
     *            is returned but not saved in the database.
     * @return Object Model with data set from the form.
     */
    function update ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('Cannot save the model from an invalid form.'));
        }
        // Create the upload
        $this->resource->setFromFormData($this->cleaned_data);
        if($commit){
            $this->resource->update();
        }
        return $this->resource;
    }
}

