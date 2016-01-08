<?php
Pluf::loadFunction('SaaS_Shortcuts_applicationFactory');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Form_Application extends Pluf_Form
{

    var $application = null;

    /**
     *
     * {@inheritDoc}
     *
     * @see Pluf_Form::initFields()
     */
    public function initFields ($extra = array())
    {
        $this->application = $extra['application'];
        $this->application = SaaS_Shortcuts_applicationFactory(
                $this->application);
        
        $this->fields['title'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('title'),
                        'initial' => $this->application->title
                ));
        $this->fields['domain'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('domain'),
                        'initial' => $this->application->domain
                ));
        $this->fields['subdomain'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('subdomain'),
                        'initial' => $this->application->subdomain
                ));
        $this->fields['description'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('description'),
                        'initial' => $this->application->description
                ));
    }

    function clean_domain ()
    {
        $domain = $this->cleaned_data['domain'];
        if (empty($domain)) {
            $domain = $this->data['subdomain'] .'.'.
                     Pluf::f('domian', 'localhost');
        }
        return $domain;
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
    function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('cannot create an application from an invalid form'));
        }
        // Set attributes
        $this->application->setFromFormData($this->cleaned_data);
        if ($commit) {
            if (! $this->application->create()) {
                throw new Pluf_Exception(__('fail to create the application'));
            }
        }
        return $this->application;
    }
}

