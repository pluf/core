<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('SaaS_Shortcuts_libraryFactory');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Form_LibCreate extends Pluf_Form
{

    var $lib;

    public function initFields ($extra = array())
    {
        $this->lib = SaaS_Shortcuts_libraryFactory();
        $this->fields['mode'] = new Pluf_Form_Field_Integer(
                array(
                        'required' => false,
                        'label' => __('mode'),
                        'initial' => $this->lib->mode
                ));
        $this->fields['type'] = new Pluf_Form_Field_Integer(
                array(
                        'required' => false,
                        'label' => __('type'),
                        'initial' => $this->lib->type
                ));
        $this->fields['name'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('name'),
                        'initial' => $this->lib->name
                ));
        $this->fields['version'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('version'),
                        'initial' => $this->lib->version
                ));
        $this->fields['description'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('description'),
                        'initial' => $this->lib->description
                ));
        $this->fields['path'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('path'),
                        'initial' => $this->lib->path
                ));
    }

    function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('Cannot save the lib from an invalid form.'));
        }
        // Set attributes
        $this->lib->setFromFormData($this->cleaned_data);
        if ($commit) {
            if (! $this->lib->create()) {
                throw new Pluf_Exception(__('Fail to create the application.'));
            }
        }
        return $this->lib;
    }
}

