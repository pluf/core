<?php
Pluf::loadFunction('SaaS_Shortcuts_applicationFactory');

/**
 * دسترسی‌ها به یک نرم افزرا را تعیین می‌کند
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Form_ApplicationSpa extends Pluf_Form
{

    var $tenant = null;

    var $spa = null;

    /**
     *
     * {@inheritDoc}
     *
     * @see Pluf_Form::initFields()
     */
    public function initFields ($extra = array())
    {
        $this->tenant = $extra['tenant'];
        $this->spa = $extra['spa'];
        
        $this->fields['owner'] = new Pluf_Form_Field_Boolean(
                array(
                        'required' => false,
                        'label' => __('owner')
                ));
        $this->fields['member'] = new Pluf_Form_Field_Boolean(
                array(
                        'required' => false,
                        'label' => __('member')
                ));
        $this->fields['authorized'] = new Pluf_Form_Field_Boolean(
                array(
                        'required' => false,
                        'label' => __('authorized'),
                        'initial' => true
                ));
        $this->fields['anonymous'] = new Pluf_Form_Field_Boolean(
                array(
                        'required' => false,
                        'label' => __('anonymous'),
                        'initial' => true
                ));
    }

    function save ()
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('cannot update permisions'));
        }
        
        if(array_key_exists('owner', $this->cleaned_data)){
            if($this->cleaned_data['owner']){
                Pluf_RowPermission::add($this->tenant, $this->spa, 'SaaS.spa-owner-access');
            } else {
                Pluf_RowPermission::remove($this->tenant, $this->spa, 'SaaS.spa-owner-access');
            }
        }
        if(array_key_exists('member', $this->cleaned_data)){
            if($this->cleaned_data['member']){
                Pluf_RowPermission::add($this->tenant, $this->spa, 'SaaS.spa-member-access');
            } else {
                Pluf_RowPermission::remove($this->tenant, $this->spa, 'SaaS.spa-member-access');
            }
        }
        if(array_key_exists('authorized', $this->cleaned_data)){
            if($this->cleaned_data['authorized']){
                Pluf_RowPermission::add($this->tenant, $this->spa, 'SaaS.spa-authorized-access');
            } else {
                Pluf_RowPermission::remove($this->tenant, $this->spa, 'SaaS.spa-authorized-access');
            }
        }
        if(array_key_exists('anonymous', $this->cleaned_data)){
            if($this->cleaned_data['anonymous']){
                Pluf_RowPermission::add($this->tenant, $this->spa, 'SaaS.spa-anonymous-access');
            } else {
                Pluf_RowPermission::remove($this->tenant, $this->spa, 'SaaS.spa-anonymous-access');
            }
        }
        
        // Set attributes
        return $this->spa;
    }
}

