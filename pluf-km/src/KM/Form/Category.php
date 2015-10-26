<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('KM_Shortcuts_categoryDateFactory');

/**
 * فرم به روز رسانی اطلاعات کاربر را ایجاد می‌کند.
 */
class KM_Form_Category extends Pluf_Form
{

    public $category = null;

    public $parent = null;

    public $user;

    /**
     * مقدار دهی فیلدها.
     *
     * @see Pluf_Form::initFields()
     */
    public function initFields ($extra = array())
    {
        $this->parent = $extra['parent'];
        $this->user = $extra['user'];
        if (array_key_exists('category', $extra))
            $this->category = $extra['category'];
        $this->category = KM_Shortcuts_categoryDateFactory(
                $this->category);
        
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
     * مدل داده‌ای را ذخیره می‌کند
     *
     * مدل داده‌ای را بر اساس تغییرات تعیین شده توسط کاربر به روز می‌کند. در
     * صورتی
     * که پارامتر ورودی با نا درستی مقدار دهی شود تغییراد ذخیره نمی شود در غیر
     * این
     * صورت داده‌ها در پایگاه داده ذخیره می‌شود.
     *
     * @param $commit داده‌ها
     *            ذخیره شود یا نه
     * @return مدل داده‌ای ایجاد شده
     */
    function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('Cannot save the label from an invalid form.'));
        }
        $this->category->setFromFormData($this->cleaned_data);
        $this->category->community = true;
        $this->category->user = $this->user;
        $this->category->parent = $this->parent;
        if ($commit) {
            $this->category->create();
        }
        return $this->category;
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
                    __('Cannot update the label from an invalid form.'));
        }
        $this->category->setFromFormData($this->cleaned_data);
        // $this->category->user = $this->user;
        if ($commit) {
            $this->category->update();
        }
        return $this->category;
    }

}
