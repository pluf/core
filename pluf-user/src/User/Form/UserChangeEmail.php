<?php

/**
 * رایانامه یک کاربر را تغییر می‌دهد
 * 
 * تغییر رایانامه تنها بر اساس کلیدی انجام می‌شود که در سیستم ایجاد شده است. 
 *
 */
class User_Form_UserChangeEmail extends Pluf_Form
{

    protected $user;

    public function initFields ($extra = array())
    {
        $this->fields['key'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => true,
                        'label' => __('Your verification key'),
                        'initial' => '',
                        'widget_attrs' => array(
                                'size' => 50
                        )
                ));
    }

    /**
     * مقدار کلید را تعیین می‌کند.
     *
     * @return multitype:
     */
    function clean_key ()
    {
        self::validateKey($this->cleaned_data['key']);
        return $this->cleaned_data['key'];
    }

    /**
     * کلید کاربر را اعتبار سنجی می‌کند.
     *
     * در صورتی که کلید معتبر نباشد خطای Pluf_Form_Invalid صادر خواهد شد.
     *
     * @param
     *            string Key
     * @return array array($new_email, $user_id, time())
     */
    public static function validateKey ($key)
    {
        $hash = substr($key, 0, 2);
        $encrypted = substr($key, 2);
        if ($hash != substr(md5(Pluf::f('secret_key') . $encrypted), 0, 2)) {
            throw new Pluf_Form_Invalid(
                    __(
                            'The validation key is not valid. Please copy/paste it from your confirmation email.'));
        }
        $cr = new Pluf_Crypt(md5(Pluf::f('secret_key')));
        return explode(':', $cr->decrypt($encrypted), 3);
    }

    /**
     * فرم تغییر ایمیل کاربر را ذخیره می‌کند.
     *
     * @param
     *            bool Commit در صورتی که درستی باشد تغیرهای کاربر ذخیره می‌شود.
     * @return Object Model
     */
    function save ($commit = true)
    {
        if (! $this->isValid()) {
            throw new Exception(
                    __('Cannot save the model from an invalid form.'));
        }
        return Pluf::f('url_base') . Pluf_HTTP_URL_urlForView(
                'IDF_Views_User::changeEmailDo', 
                array(
                        $this->cleaned_data['key']
                ));
    }
}
