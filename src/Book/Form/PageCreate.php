<?php

/**
 * ایجاد یک صفحه ویکی جدید
 *
 * با استفاده از این فرم می‌توان یک صفحه جدید ویکی را ایجاد کرد.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Book_Form_PageCreate extends Pluf_Form
{

    public $user = null;

    public $book = null;

    public function initFields ($extra = array())
    {
        $this->user = $extra['user'];
        $this->book = $extra['book'];
        $initial = __('empty page');
        $initname = (! empty($extra['name'])) ? $extra['name'] : __('page name');
        $this->fields['title'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('page title'),
                        'initial' => $initname,
                        'widget_attrs' => array(
                                'maxlength' => 200,
                                'size' => 67
                        ),
                        'help_text' => __(
                                'the page name must contains only letters, digits and the dash (-) character')
                ));
        $this->fields['summary'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('Description'),
                        'help_text' => __(
                                'this one line description is displayed in the list of pages'),
                        'initial' => '',
                        'widget_attrs' => array(
                                'maxlength' => 200,
                                'size' => 67
                        )
                ));
        $this->fields['content'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('content'),
                        'initial' => $initial,
                        'widget' => 'Pluf_Form_Widget_TextareaInput',
                        'widget_attrs' => array(
                                'cols' => 68,
                                'rows' => 26
                        )
                ));
        $this->fields['content_type'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('content type'),
                        'initial' => 'text/plain',
                        'widget' => 'Pluf_Form_Widget_TextareaInput',
                        'widget_attrs' => array(
                                'cols' => 68,
                                'rows' => 26
                        )
                ));
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
            throw new Pluf_Exception(
                    __('Cannot save the page from an invalid form'));
        }
        // // Add a tag for each label
        // $tags = array();
        // if ($this->show_full) {
        // for ($i = 1; $i < 4; $i ++) {
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
        // }
        // Create the page
        $page = new Book_Page();
        // $page->project = $this->project;
        $page->setFromFormData($this->cleaned_data);
        $page->submitter = $this->user;
        $page->book = $this->book;
        if ($commit) {
            $page->create();
        }
        // foreach ($tags as $tag) {
        // $page->setAssoc($tag);
        // }
        // add the first revision
        // $rev = new IDF_WikiRevision();
        // $rev->wikipage = $page;
        // $rev->content = $this->cleaned_data['content'];
        // $rev->submitter = $this->user;
        // $rev->summary = __('Initial page creation');
        // $rev->create();
        // $rev->notify($this->project->getConf());
        return $page;
    }
}
