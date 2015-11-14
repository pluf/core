<?php

/**
 * Update a file for download.
 *
 */
class SaaS_Form_ResourceUpdate extends Pluf_Form
{

    public $user = null;

    public $app = null;

    public $upload = null;

    public function initFields ($extra = array())
    {
        $this->user = $extra['user'];
        $this->app = $extra['application'];
        $this->upload = $extra['resource'];
        
        $this->fields['description'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => true,
                        'label' => __('Summary'),
                        'initial' => $this->upload->summary,
                        'widget_attrs' => array(
                                'maxlength' => 200,
                                'size' => 67
                        )
                ));
    }

//     /**
//      * Validate the interconnection in the form.
//      */
//     public function clean ()
//     {
//         $conf = new IDF_Conf();
//         $conf->setProject($this->project);
//         $onemax = array();
//         foreach (explode(',', 
//                 $conf->getVal('labels_download_one_max', 
//                         IDF_Form_UploadConf::init_one_max)) as $class) {
//             if (trim($class) != '') {
//                 $onemax[] = mb_strtolower(trim($class));
//             }
//         }
//         $count = array();
//         for ($i = 1; $i < 7; $i ++) {
//             $this->cleaned_data['label' . $i] = trim(
//                     $this->cleaned_data['label' . $i]);
//             if (strpos($this->cleaned_data['label' . $i], ':') !== false) {
//                 list ($class, $name) = explode(':', 
//                         $this->cleaned_data['label' . $i], 2);
//                 list ($class, $name) = array(
//                         mb_strtolower(trim($class)),
//                         trim($name)
//                 );
//             } else {
//                 $class = 'other';
//                 $name = $this->cleaned_data['label' . $i];
//             }
//             if (! isset($count[$class]))
//                 $count[$class] = 1;
//             else
//                 $count[$class] += 1;
//             if (in_array($class, $onemax) and $count[$class] > 1) {
//                 if (! isset($this->errors['label' . $i]))
//                     $this->errors['label' . $i] = array();
//                 $this->errors['label' . $i][] = sprintf(
//                         __(
//                                 'You cannot provide more than label from the %s class to an issue.'), 
//                         $class);
//                 throw new Pluf_Form_Invalid(__('You provided an invalid label.'));
//             }
//         }
//         return $this->cleaned_data;
//     }

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
            throw new Exception(
                    __('Cannot save the model from an invalid form.'));
        }
//         // Add a tag for each label
//         $tags = array();
//         for ($i = 1; $i < 7; $i ++) {
//             if (strlen($this->cleaned_data['label' . $i]) > 0) {
//                 if (strpos($this->cleaned_data['label' . $i], ':') !== false) {
//                     list ($class, $name) = explode(':', 
//                             $this->cleaned_data['label' . $i], 2);
//                     list ($class, $name) = array(
//                             trim($class),
//                             trim($name)
//                     );
//                 } else {
//                     $class = 'Other';
//                     $name = trim($this->cleaned_data['label' . $i]);
//                 }
//                 $tag = IDF_Tag::add($name, $this->project, $class);
//                 $tags[] = $tag->id;
//             }
//         }
        // Create the upload
        $this->upload->description = trim($this->cleaned_data['description']);
        $this->upload->modif_dtime = gmdate('Y-m-d H:i:s');
        $this->upload->update();
//         $this->upload->batchAssoc('IDF_Tag', $tags);
//         /**
//          * [signal]
//          *
//          * IDF_Upload::update
//          *
//          * [sender]
//          *
//          * IDF_Form_UpdateUpload
//          *
//          * [description]
//          *
//          * This signal allows an application to perform a set of tasks
//          * just after the update of an uploaded file.
//          *
//          * [parameters]
//          *
//          * array('upload' => $upload);
//          */
//         $params = array(
//                 'upload' => $this->upload
//         );
//         Pluf_Signal::send('IDF_Upload::update', 'IDF_Form_UpdateUpload', 
//                 $params);
        return $this->upload;
    }
}

