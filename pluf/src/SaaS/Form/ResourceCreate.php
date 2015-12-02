<?php

/**
 * Upload a file for download.
 *
 */
class SaaS_Form_ResourceCreate extends Pluf_Form
{

    public $user = null;

    public $app = null;

    public function initFields ($extra = array())
    {
        $this->user = $extra['user'];
        $this->app = $extra['application'];
        
        $this->fields['description'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => true,
                        'label' => __('description'),
                        'initial' => ''
                ));
        $this->fields['file'] = new Pluf_Form_Field_File(
                array(
                        'required' => true,
                        'label' => __('file'),
                        'initial' => '',
                        'max_size' => Pluf::f('upload_max_size', 2097152),
                        'move_function_params' => array(
                                'upload_path' => Pluf::f('upload_path') . '/' .
                                         $this->app->id . '/files',
                                        'upload_path_create' => true,
                                        'upload_overwrite' => false
                        )
                ));
    }

    public function clean_file ()
    {
        $extra = strtolower(
                implode('|', explode(' ', Pluf::f('upload_extra_ext'))));
        if (strlen($extra))
            $extra .= '|';
        if (! preg_match(
                '/\.(' . $extra .
                         'png|jpg|jpeg|gif|bmp|psd|tif|aiff|asf|avi|bz2|css|doc|eps|gz|jar|mdtext|mid|mov|mp3|mpg|ogg|pdf|ppt|ps|qt|ra|ram|rm|rtf|sdd|sdw|sit|sxi|sxw|swf|tgz|txt|wav|xls|xml|war|wmv|zip)$/i', 
                        $this->cleaned_data['file'])) {
            @unlink(
                    Pluf::f('upload_path') . '/' . $this->app->id . '/files/' .
                     $this->cleaned_data['file']);
            throw new Pluf_Form_Invalid(
                    __(
                            'for security reason, you cannot upload a file with this extension'));
        }
        return $this->cleaned_data['file'];
    }
    
    // /**
    // * Validate the interconnection in the form.
    // */
    // public function clean ()
    // {
    // $conf = new IDF_Conf();
    // $conf->setProject($this->project);
    // $onemax = array();
    // foreach (explode(',',
    // $conf->getVal('labels_download_one_max',
    // IDF_Form_UploadConf::init_one_max)) as $class) {
    // if (trim($class) != '') {
    // $onemax[] = mb_strtolower(trim($class));
    // }
    // }
    // $count = array();
    // for ($i = 1; $i < 7; $i ++) {
    // $this->cleaned_data['label' . $i] = trim(
    // $this->cleaned_data['label' . $i]);
    // if (strpos($this->cleaned_data['label' . $i], ':') !== false) {
    // list ($class, $name) = explode(':',
    // $this->cleaned_data['label' . $i], 2);
    // list ($class, $name) = array(
    // mb_strtolower(trim($class)),
    // trim($name)
    // );
    // } else {
    // $class = 'other';
    // $name = $this->cleaned_data['label' . $i];
    // }
    // if (! isset($count[$class]))
    // $count[$class] = 1;
    // else
    // $count[$class] += 1;
    // if (in_array($class, $onemax) and $count[$class] > 1) {
    // if (! isset($this->errors['label' . $i]))
    // $this->errors['label' . $i] = array();
    // $this->errors['label' . $i][] = sprintf(
    // __(
    // 'You cannot provide more than label from the %s class to an issue.'),
    // $class);
    // throw new Pluf_Form_Invalid(__('You provided an invalid label.'));
    // }
    // }
    // return $this->cleaned_data;
    // }
    
    /**
     * If we have uploaded a file, but the form failed remove it.
     */
    function failed ()
    {
        if (! empty($this->cleaned_data['file']) and file_exists(
                Pluf::f('upload_path') . '/' . $this->app->id . '/files/' .
                         $this->cleaned_data['file'])) {
            @unlink(
                    Pluf::f('upload_path') . '/' . $this->app->id . '/files/' .
                     $this->cleaned_data['file']);
        }
    }

    /**
     * Save the model in the database.
     *
     * @param
     *            bool Commit in the database or not. If not, the object
     *            is returned but not saved in the database.
     * @return Object Model with data set from the form.
     */
    function save ($commit = true)
    {
        if (! $this->isValid()) {
            $string1 = "";
            foreach ($this->errors as $err) {
                foreach ($err as $value) {
                    $string1 .= $value . " ";
                }
            }
            throw new Pluf_Exception(
                    __('Cannot save the model from an invalid form.').$string1);
        }
        // // Add a tag for each label
        // $tags = array();
        // for ($i = 1; $i < 7; $i ++) {
        // if (strlen($this->cleaned_data['label' . $i]) > 0) {
        // if (strpos($this->cleaned_data['label' . $i], ':') !== false) {
        // list ($class, $name) = explode(':',
        // $this->cleaned_data['label' . $i], 2);
        // list ($class, $name) = array(
        // trim($class),
        // trim($name)
        // );
        // } else {
        // $class = 'Other';
        // $name = trim($this->cleaned_data['label' . $i]);
        // }
        // $tags[] = IDF_Tag::add($name, $this->project, $class);
        // }
        // }
        // Create the upload
        $upload = new SaaS_Resource();
        $upload->application = $this->app;
        $upload->submitter = $this->user;
        $upload->description = trim($this->cleaned_data['description']);
        $upload->file = $this->cleaned_data['file'];
        $upload->file_path = '/' . $this->app->id . '/files';
        $upload->file_size = filesize(
                Pluf::f('upload_path') . $upload->file_path);
        $upload->downloads = 0;
        
        $upload->owner_write = true;
        $upload->owner_read = true;
        $upload->member_write = true;
        $upload->member_read = true;
        $upload->authorized_write = false;
        $upload->authorized_read = true;
        $upload->other_write = false;
        $upload->other_read = true;
        
        $upload->create();
        // foreach ($tags as $tag) {
        // $upload->setAssoc($tag);
        // }
        // // Send the notification
        // $upload->notify($this->project->getConf());
        // /**
        // * [signal]
        // *
        // * IDF_Upload::create
        // *
        // * [sender]
        // *
        // * IDF_Form_Upload
        // *
        // * [description]
        // *
        // * This signal allows an application to perform a set of tasks
        // * just after the upload of a file and after the notification run.
        // *
        // * [parameters]
        // *
        // * array('upload' => $upload);
        // */
        // $params = array(
        // 'upload' => $upload
        // );
        // Pluf_Signal::send('IDF_Upload::create', 'IDF_Form_Upload', $params);
        return $upload;
    }
}

