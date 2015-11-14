<?php
Pluf::loadFunction('SaaS_Shortcuts_applicationFactory');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Form_ApplicationUpdate extends Pluf_Form
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
        $this->fields['title'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('title'),
                        'initial' => $this->application->title
                ));
        $this->fields['description'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('description'),
                        'initial' => $this->application->description
                ));
    }

    /**
     * موجودیت را به روز می‌کند.
     *
     * @param string $commit            
     * @throws Pluf_Exception
     * @return Ambigous <unknown, Advisor_Models_UserProfile>
     */
    function update ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('cannot update the application from an invalid form'));
        }
        // Set attributes
        $this->application->setFromFormData($this->cleaned_data);
        if ($commit) {
            if (! $this->application->update()) {
                throw new Pluf_Exception(__('fail to update the application'));
            }
        }
        return $this->application;
    }
}

