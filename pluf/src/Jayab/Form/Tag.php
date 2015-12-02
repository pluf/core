<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Jayab_Shortcuts_tagFactory');

/**
 * فرم به روز رسانی اطلاعات کاربر را ایجاد می‌کند.
 */
class Jayab_Form_Tag extends Pluf_Form
{

    public $user = null;

    public $tag = null;

    /**
     * مقدار دهی فیلدها.
     *
     * @see Pluf_Form::initFields()
     */
    public function initFields ($extra = array())
    {
        if (array_key_exists('tag', $extra)) {
            $this->tag = $extra['tag'];
        }
        $this->tag = Jayab_Shortcuts_tagFactory($this->tag);
        
        $this->fields['tag_key'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('tag key'),
                        'initial' => $this->tag->tag_key
                ));
        $this->fields['tag_value'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('tag value'),
                        'initial' => $this->tag->tag_value
                ));
        
        $this->fields['description'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('location description'),
                        'initial' => $this->tag->description
                ));
    }

    /**
     * داده‌های مکان را ایجاد می‌کند.
     *
     * از این فراخوانی برای ایجاد یک مکان جدید باید استفاده شود.
     *
     * @param string $commit            
     * @throws Pluf_Exception
     */
    function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('cannot save the tag from an invalid form'));
        }
        // Set attributes
        $this->tag->setFromFormData($this->cleaned_data);
        if ($commit) {
            if (! $this->tag->create()) {
                throw new Pluf_Exception(__('fail to create the tag'));
            }
        }
        return $this->tag;
    }

    /**
     * داده‌های یک مکان را به روز می کند.
     *
     * از این فراخوانی تنها برای به روز کردن داده‌ها باید استفاده شود.
     *
     * @param string $commit            
     * @throws Pluf_Exception
     */
    function update ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('cannot save the tag from an invalid form'));
        }
        // Set attributes
        $this->tag->setFromFormData($this->cleaned_data);
        if ($commit) {
            if (! $this->tag->update()) {
                throw new Pluf_Exception(
                        sprintf(__('fail to update the tag %s'), 
                                $this->tag->tag_key.":".$this->tag->tag_value));
            }
        }
        return $this->tag;
    }
}
