<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Jayab_Shortcuts_locationFactory');

/**
 * فرم به روز رسانی اطلاعات کاربر را ایجاد می‌کند.
 */
class Jayab_Form_Location extends Pluf_Form
{

    public $user = null;

    public $location = null;

    /**
     * مقدار دهی فیلدها.
     *
     * @see Pluf_Form::initFields()
     */
    public function initFields ($extra = array())
    {
        $this->user = $extra['user'];
        if (array_key_exists('location', $extra)) {
            $this->location = $extra['location'];
        }
        $this->location = Jayab_Shortcuts_locationFactory($this->location);
        
        $this->fields['name'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('location name'),
                        'initial' => $this->location->name
                ));
        
        $this->fields['description'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('location description'),
                        'initial' => $this->location->description
                ));
        
        $this->fields['latitude'] = new Pluf_Form_Field_Float(
                array(
                        'required' => false,
                        'label' => __('latitude'),
                        'initial' => $this->location->latitude
                ));
        
        $this->fields['longitude'] = new Pluf_Form_Field_Float(
                array(
                        'required' => false,
                        'label' => __('longitude'),
                        'initial' => $this->location->longitude
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
                    __('cannot save the location from an invalid form'));
        }
        // Set attributes
        $this->location->setFromFormData($this->cleaned_data);
        $this->location->reporter = $this->user;
        if ($commit) {
            if (! $this->location->create()) {
                throw new Pluf_Exception(__('fail to create the location'));
            }
        }
        return $this->location;
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
                    __('cannot save the location from an invalid form'));
        }
        // Set attributes
        $this->location->setFromFormData($this->cleaned_data);
        if ($commit) {
            if (! $this->location->update()) {
                throw new Pluf_Exception(
                        sprintf(__('fail to update the location %s'), 
                                $this->location->name));
            }
        }
        return $this->location;
    }
}
