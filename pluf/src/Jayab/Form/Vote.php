<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Jayab_Shortcuts_voteFactory');

/**
 * فرم به روز رسانی یک رای
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *        
 */
class Jayab_Form_Vote extends Pluf_Form
{

    public $user = null;

    public $location = null;

    public $vote = null;

    /**
     * مقدار دهی فیلدها.
     *
     * @see Pluf_Form::initFields()
     */
    public function initFields ($extra = array())
    {
        $this->user = $extra['user'];
        $this->location = $extra['location'];
        if (array_key_exists('vote', $extra)) {
            $this->vote = $extra['vote'];
        }
        $this->vote = Jayab_Shortcuts_voteFactory($this->vote);
        
        $this->fields['like'] = new Pluf_Form_Field_Boolean(
                array(
                        'required' => true,
                        'label' => __('vote value'),
                        'initial' => $this->vote->like
                ));
    }

    function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('cannot vote the location from an invalid form'));
        }
        // Set attributes
        $this->vote->setFromFormData($this->cleaned_data);
        $this->vote->voter = $this->user;
        $this->vote->location = $this->location;
        if ($commit) {
            if (! $this->vote->create()) {
                throw new Pluf_Exception(__('fail to update the vote of the location'));
            }
        }
        return $this->vote;
    }

    function update ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('cannot vote the location from an invalid form'));
        }
        // Set attributes
        $this->vote->setFromFormData($this->cleaned_data);
        if ($commit) {
            if (! $this->vote->update()) {
                throw new Pluf_Exception(__('fail to update the vote of the location'));
            }
        }
        return $this->vote;
    }
}
