<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('HM_Shortcuts_partFactory');

/**
 * فرم به روز رسانی و ایجاد واحد
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 */
class HM_Form_Part extends Pluf_Form
{

    /**
     * واحد مورد نظر
     *
     * @var unknown
     */
    var $part = null;

    var $apartment = null;

    /**
     * (non-PHPdoc)
     *
     * @see Pluf_Form::initFields()
     */
    public function initFields ($extra = array())
    {
        $this->apartment = $extra['apartment'];
        if (array_key_exists('part', $extra)) {
            $this->part = $extra['part'];
        }
        $this->part = HM_Shortcuts_partFactory($this->part);
        
        $property = array(
                'label' => __('title'),
                'initial' => '',
                'widget_attrs' => array(
                        'maxlength' => 50
                )
        );
        $this->fields['title'] = new Pluf_Form_Field_Varchar($property);
        
        $property = array(
                'label' => __('count')
        );
        $this->fields['count'] = new Pluf_Form_Field_Integer($property);
        
        $property = array(
                'label' => __('part number')
        );
        $this->fields['part_number'] = new Pluf_Form_Field_Varchar($property);
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
     * @return مدل داده‌ای تغییر یافته
     */
    public function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('Cannot create a part from an invalid form.'));
        }
        // Set attributes
        $this->part->setFromFormData($this->cleaned_data);
        $this->part->apartment = $this->apartment;
        if ($commit && ! $this->part->create()) {
            throw new Pluf_Exception(__('Fail to create a new part.'));
        }
        return $this->part;
    }

    /**
     * مدل داده‌ای را به روز می‌کند.
     *
     * @throws Pluf_Exception
     */
    public function update ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('Cannot update the part from an invalid form.'));
        }
        // Set attributes
        $this->part->setFromFormData($this->cleaned_data);
        if ($commit && ! $this->part->update()) {
            throw new Pluf_Exception(__('Fail to update the apartment.'));
        }
        return $this->part;
    }
}

