<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');

/**
 * فرم به روز رسانی اطلاعات کاربر را ایجاد می‌کند.
 */
class SaaSKM_Form_VoteCreate extends Pluf_Form
{

    var $tenant = null;

    /**
     * مقدار دهی فیلدها.
     *
     * @see Pluf_Form::initFields()
     */
    public function initFields ($extra = array())
    {
        $this->tenant = $extra['tenant'];
        
        $this->fields['vote_comment'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('key')
                ));
        $this->fields['vote_value'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('value')
                ));
        $this->fields['tag_title'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('title')
                ));
        $this->fields['tag_description'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => __('description')
                ));
    }

    /**
     *
     * @param $commit داده‌ها
     *            ذخیره شود یا نه
     * @return مدل داده‌ای ایجاد شده
     */
    function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception(
                    __('cannot save a tag from an invalid form'));
        }
        $tag = new SaaSKM_Tag();
        $tag->setFromFormData($this->cleaned_data);
        $tag->tenant = $this->tenant;
        
        { // XXX: maso, 1394: converto to clean (Check tag exist)
            $sqlSelect = new Pluf_SQL(
                    'tag_key=%s AND tag_value=%s AND tenant=%s', 
                    array(
                            $tag->tag_key,
                            $tag->tag_value,
                            $this->tenant->id
                    ));
            $str = $sqlSelect->gen();
            $count = Pluf::factory('SaaSKM_Tag')->getCount(
                    array(
                            'filter' => $sqlSelect->gen()
                    ));
            if ($count > 0) {
                throw new Pluf_Exception("Tag exist");
            }
        }
        if ($commit) {
            $tag->create();
        }
        return $tag;
    }
}
