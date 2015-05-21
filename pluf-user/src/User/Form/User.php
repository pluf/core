<?php
Pluf::loadFunction ( 'Pluf_HTTP_URL_urlForView' );
Pluf::loadFunction ( 'User_Shortcuts_UserDateFactory' );

/**
 * فرم به روز رسانی اطلاعات کاربر را ایجاد می‌کند.
 */
class User_Form_User extends Pluf_Form {
	public $user_data = null;
	
	/**
	 * مقدار دهی فیلدها.
	 *
	 * @see Pluf_Form::initFields()
	 */
	public function initFields($extra = array()) {
		if (array_key_exists ( 'user', $extra ))
			$this->user_data = $extra ['user'];
		$this->user_data = User_Shortcuts_UserDateFactory ( $this->user_data );
		
		$this->fields ['login'] = new Pluf_Form_Field_Varchar ( array (
				'required' => true,
				'label' => __ ( 'login' ),
				'initial' => $this->user_data->login,
		) );
		
		$this->fields ['first_name'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'first name' ),
				'initial' => $this->user_data->first_name,
		) );
		
		$this->fields ['last_name'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'last name' ),
				'initial' => $this->user_data->last_name,
		) );
		
		$this->fields ['language'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'language' ),
				'initial' => $this->user_data->language,
		) );
		
		$this->fields ['password'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'your password' ),
				'initial' => '',
		) );
		
		$this->fields ['email'] = new Pluf_Form_Field_Email ( array (
				'required' => false,
				'label' => __ ( 'Email address' ),
				'initial' => $this->user_data->email,
		) );
	}
	
	// XXX: maso 1391: ارسال رایانامه برای فعال کردن کاربران
	private function send_validation_mail($new_email, $secondary_mail = false) {
		// XXX: maso 1392: use validation method
		// $type = "primary";
		// $cr = new Pluf_Crypt(md5(Pluf::f('secret_key')));
		// $encrypted = trim($cr->encrypt($new_email.':'.$this->user_data->id.':'.time().':'.$type), '~');
		// $key = substr(md5(Pluf::f('secret_key').$encrypted), 0, 2).$encrypted;
		// $url = Pluf::f('url_base').Pluf_HTTP_URL_urlForView('Peechak_Views_User::changeEmailDo', array($key), array(), false);
		// $urlik = Pluf::f('url_base').Pluf_HTTP_URL_urlForView('Peechak_Views_User::changeEmailInputKey', array(), array(), false);
		// $context = new Pluf_Template_Context(
		// array('key' => Pluf_Template::markSafe($key),
		// 'url' => Pluf_Template::markSafe($url),
		// 'urlik' => Pluf_Template::markSafe($urlik),
		// 'email' => $new_email,
		// 'user'=> $this->user_data,
		// )
		// );
		// $tmpl = new Pluf_Template('peechak/mail/user/changeemail-email.txt');
		// $text_email = $tmpl->render($context);
		// $email = new Pluf_Mail(Pluf::f('from_email'), $new_email,
		// __('Confirm your new email address.'));
		// $email->addTextMessage($text_email);
		// $email->sendMail();
		// $this->user_data->setMessage(sprintf(__('A validation email has been sent to "%s" to validate the email address change.'), Pluf_esc($new_email)));
	}
	
	/**
	 * مدل داده‌ای را ذخیره می‌کند
	 *
	 * مدل داده‌ای را بر اساس تغییرات تعیین شده توسط کاربر به روز می‌کند. در صورتی
	 * که پارامتر ورودی با نا درستی مقدار دهی شود تغییراد ذخیره نمی شود در غیر این
	 * صورت داده‌ها در پایگاه داده ذخیره می‌شود.
	 *
	 * @param $commit داده‌ها
	 *        	ذخیره شود یا نه
	 * @return مدل داده‌ای ایجاد شده
	 */
	function save($commit = true) {
		if (! $this->isValid ()) {
			throw new Pluf_Exception( __ ( 'Cannot save the model from an invalid form.' ) );
		}
		$old_email = $this->user_data->email;
		$new_email = $this->cleaned_data ['email'];
		$this->user_data->email = $new_email;
		unset ( $this->cleaned_data ['email'] );
		if ($old_email != $new_email) {
			$this->send_validation_mail ( $new_email );
		}
		$this->user_data->setFromFormData ( $this->cleaned_data );

		$user_active = Pluf::f ( 'user_signup_active', false );
		$this->user_data->active = $user_active;
		if ($commit) {
			$this->user_data->create ();
		}
		return $this->user_data;
	}
	
	/**
	 * داده‌های کاربر را به روز می‌کند.
	 *
	 * @throws Pluf_Exception
	 */
	function update() {
		if (! $this->isValid ()) {
			throw new Pluf_Exception ( __ ( 'Cannot save the model from an invalid form.' ) );
		}
		$old_email = $this->user_data->email;
		$new_email = $this->cleaned_data ['email'];
		// maso 1392: use validation method
		$this->user_data->email = $new_email;
		unset ( $this->cleaned_data ['email'] );
		if ($old_email != $new_email) {
			$this->send_validation_mail ( $new_email );
		}
		$this->user_data->setFromFormData ( $this->cleaned_data );
		$this->user_data->update ();
		return $this->user_data;
	}
	
	/**
	 * بررسی صحت نام خانوادگی
	 *
	 * @return string|unknown
	 */
	function clean_last_name() {
		$last_name = trim ( $this->cleaned_data ['last_name'] );
		if ($last_name == mb_strtoupper ( $last_name )) {
			return mb_convert_case ( mb_strtolower ( $last_name ), MB_CASE_TITLE, 'UTF-8' );
		}
		return $last_name;
	}
	
	/**
	 * بررسی صحت نام
	 *
	 * @return string|unknown
	 */
	function clean_first_name() {
		$first_name = trim ( $this->cleaned_data ['first_name'] );
		if ($first_name == mb_strtoupper ( $first_name )) {
			return mb_convert_case ( mb_strtolower ( $first_name ), MB_CASE_TITLE, 'UTF-8' );
		}
		return $first_name;
	}
	
	/**
	 * بررسی صحت رایانامه
	 *
	 * @throws Pluf_Form_Invalid
	 * @return multitype:
	 */
	function clean_email() {
		$this->cleaned_data ['email'] = mb_strtolower ( trim ( $this->cleaned_data ['email'] ) );
// 		$user = Pluf::factory ( 'IDF_EmailAddress' )->get_user_for_email_address ( $this->cleaned_data ['email'] );
// 		if ($user != null and $user->id != $this->user_data->id) {
// 			throw new Pluf_Form_Invalid ( sprintf ( __ ( 'The email "%s" is already used.' ), $this->cleaned_data ['email'] ) );
// 		}
		return $this->cleaned_data ['email'];
	}
}
