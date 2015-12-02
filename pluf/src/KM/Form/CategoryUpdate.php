<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('KM_Shortcuts_categoryDateFactory');

/**
 * فرم به روز رسانی اطلاعات کاربر را ایجاد می‌کند.
 */
class KM_Form_CategoryUpdate extends KM_Form_CategoryCreate
{

    /**
     * مقدار دهی فیلدها.
     *
     * @see Pluf_Form::initFields()
     */
    public function initFields ($extra = array())
    {
        $this->category = $extra['category'];
        $this->fields['title'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('title'),
                        'initial' => $this->category->title
                ));
        
        $this->fields['description'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('description'),
                        'initial' => $this->category->description
                ));
        
        $this->fields['color'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('color'),
                        'initial' => $this->category->color
                ));
    }

    /**
     * داده‌های کاربر را به روز می‌کند.
     *
     * @throws Pluf_Exception
     */
    function update ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('cannot update a category from an invalid form'));
        }
        $this->category->setFromFormData($this->cleaned_data);
        // $this->category->user = $this->user;
        if ($commit) {
            $this->category->update();
        }
        return $this->category;
    }

}
