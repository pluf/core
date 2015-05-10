<?php
Pluf::loadFunction ( 'Pluf_HTTP_URL_urlForView' );

/**
 * فرم به روز رسانی اطلاعات کاربر را ایجاد می‌کند.
 */
class Peechak_Form_UserAccount extends Pluf_Form {
	public $user = null;
	public function initFields($extra = array()) {
		$this->user = $extra ['user'];
		// $user_data = IDF_UserData::factory($this->user);
		
		$property = array (
				'label' => __ ( 'first name' ),
				'initial' => $this->user->first_name,
				'widget_attrs' => array (
						'maxlength' => 50,
						'size' => 15,
						'placeholder' => __ ( 'first name example:user' ) 
				),
				'required' => false 
		);
		$this->fields ['first_name'] = new Pluf_Form_Field_Varchar ( $property );
		
		$property = array ();
		$property ['required'] = true;
		$property ['label'] = __ ( 'last name' );
		$property ['initial'] = $this->user->last_name;
		$property ['widget_attrs'] = array (
				'maxlength' => 50,
				'size' => 20,
				'placeholder' => __ ( 'last name example:user' ) 
		);
		$this->fields ['last_name'] = new Pluf_Form_Field_Varchar ( $property );
		
		$property = array ();
		$property ['required'] = true;
		$property ['label'] = __ ( 'email' );
		$property ['initial'] = $this->user->email;
		$property ['help_text'] = __ ( 'If you change your email address, an email will be sent to the new address to confirm it.' );
		$property ['widget_attrs'] = array (
				'placeholder' => __ ( 'email example:user@phoenix.org.ir' ) 
		);
		$this->fields ['email'] = new Pluf_Form_Field_Email ( $property );
		
		$property = array ();
		$property ['required'] = true;
		$property ['label'] = __ ( 'language' );
		$property ['initial'] = $this->user->language;
		$property ['widget'] = 'Pluf_Form_Widget_SelectInput';
		$property ['widget_attrs'] = array (
				'choices' => Pluf_L10n::getInstalledLanguages () 
		);
		$this->fields ['language'] = new Pluf_Form_Field_Varchar ( $property );
		
		$this->fields ['password'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'your password' ),
				'initial' => '',
				'widget' => 'Pluf_Form_Widget_PasswordInput',
				'help_text' => Pluf_Template::markSafe ( __ ( 'Leave blank if you do not want to change your password.' ) . '<br />' . __ ( 'Your password must be hard for other people to find it, but easy for you to remember.' ) ),
				'widget_attrs' => array (
						'autocomplete' => 'off',
						'maxlength' => 50,
						'size' => 15 
				) 
		) );
		$this->fields ['password2'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'confirm your password' ),
				'initial' => '',
				'widget' => 'Pluf_Form_Widget_PasswordInput',
				'widget_attrs' => array (
						'autocomplete' => 'off',
						'maxlength' => 50,
						'size' => 15 
				) 
		) );
		
		// $this->fields['description'] = new Pluf_Form_Field_Varchar(
		// array('required' => false,
		// 'label' => __('Description'),
		// 'initial' => $user_data->description,
		// 'widget_attrs' => array('rows' => 3,
		// 'cols' => 40),
		// 'widget' => 'Pluf_Form_Widget_TextareaInput',
		// ));
		
		// $this->fields['twitter'] = new Pluf_Form_Field_Varchar(
		// array('required' => false,
		// 'label' => __('Twitter username'),
		// 'initial' => $user_data->twitter,
		// 'widget_attrs' => array(
		// 'maxlength' => 50,
		// 'size' => 15,
		// ),
		// ));
		
		// $this->fields['public_email'] = new Pluf_Form_Field_Email(
		// array('required' => false,
		// 'label' => __('Public email address'),
		// 'initial' => $user_data->public_email,
		// 'widget_attrs' => array(
		// 'maxlength' => 50,
		// 'size' => 15,
		// ),
		// ));
		
		// $this->fields['website'] = new Pluf_Form_Field_Url(
		// array('required' => false,
		// 'label' => __('Website URL'),
		// 'initial' => $user_data->website,
		// 'widget_attrs' => array(
		// 'maxlength' => 50,
		// 'size' => 15,
		// ),
		// ));
		
		// $this->fields['custom_avatar'] = new Pluf_Form_Field_File(
		// array('required' => false,
		// 'label' => __('Upload custom avatar'),
		// 'initial' => '',
		// 'max_size' => Pluf::f('max_upload_size', 2097152),
		// 'move_function_params' => array('upload_path' => Pluf::f('upload_path').'/avatars',
		// 'upload_path_create' => true,
		// 'upload_overwrite' => true,
		// 'file_name' => 'user_'.$this->user->id.'_%s'),
		// 'help_text' => __('An image file with a width and height not larger than 60 pixels (bigger images are scaled down).'),
		// ));
		
		// $this->fields['remove_custom_avatar'] = new Pluf_Form_Field_Boolean(
		// array('required' => false,
		// 'label' => __('Remove custom avatar'),
		// 'initial' => false,
		// 'widget' => 'Pluf_Form_Widget_CheckboxInput',
		// 'widget_attrs' => array(),
		// 'help_text' => __('Tick this to delete the custom avatar.'),
		// ));
		
		$this->fields ['public_key'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'add a public key' ),
				'initial' => '',
				'widget_attrs' => array (
						'rows' => 3,
						'cols' => 40,
						'maxlength' => 2048 
				),
				'widget' => 'Pluf_Form_Widget_TextareaInput',
				'help_text' => __ ( 'Paste an SSH or monotone public key. Be careful to not provide your private key here!' ) 
		) );
		
		// $this->fields['secondary_mail'] = new Pluf_Form_Field_Email(
		// array('required' => false,
		// 'label' => __('Add a secondary email address'),
		// 'initial' => '',
		// 'help_text' => __('You will get an email to confirm that you own the address you specify.'),
		// ));
	}
	
	// XXX: maso 1391: ارسال رایانامه برای فعال کردن کاربران
	private function send_validation_mail($new_email, $secondary_mail = false) {
		// $type = "primary";
		// $cr = new Pluf_Crypt(md5(Pluf::f('secret_key')));
		// $encrypted = trim($cr->encrypt($new_email.':'.$this->user->id.':'.time().':'.$type), '~');
		// $key = substr(md5(Pluf::f('secret_key').$encrypted), 0, 2).$encrypted;
		// $url = Pluf::f('url_base').Pluf_HTTP_URL_urlForView('Peechak_Views_User::changeEmailDo', array($key), array(), false);
		// $urlik = Pluf::f('url_base').Pluf_HTTP_URL_urlForView('Peechak_Views_User::changeEmailInputKey', array(), array(), false);
		// $context = new Pluf_Template_Context(
		// array('key' => Pluf_Template::markSafe($key),
		// 'url' => Pluf_Template::markSafe($url),
		// 'urlik' => Pluf_Template::markSafe($urlik),
		// 'email' => $new_email,
		// 'user'=> $this->user,
		// )
		// );
		// $tmpl = new Pluf_Template('peechak/mail/user/changeemail-email.txt');
		// $text_email = $tmpl->render($context);
		// $email = new Pluf_Mail(Pluf::f('from_email'), $new_email,
		// __('Confirm your new email address.'));
		// $email->addTextMessage($text_email);
		// $email->sendMail();
		// $this->user->setMessage(sprintf(__('A validation email has been sent to "%s" to validate the email address change.'), Pluf_esc($new_email)));
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
	 * @return مدل داده‌ای تغییر یافته
	 */
	function save($commit = true) {
		if (! $this->isValid ()) {
			throw new Pluf_Exception ( __ ( 'Cannot save the model from an invalid form.' ) );
		}
		unset ( $this->cleaned_data ['password2'] );
		$update_pass = false;
		if (strlen ( $this->cleaned_data ['password'] ) == 0) {
			unset ( $this->cleaned_data ['password'] );
		} else {
			$update_pass = true;
		}
		$old_email = $this->user->email;
		$new_email = $this->cleaned_data ['email'];
		// XXX: maso 1392: use validation method
		$this->user->email = $new_email;
		unset ( $this->cleaned_data ['email'] );
		if ($old_email != $new_email) {
			$this->send_validation_mail ( $new_email );
		}
		$this->user->setFromFormData ( $this->cleaned_data );
		
		if ($commit) {
			$this->user->update ();
		}
		return $this->user;
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
		$user = Pluf::factory ( 'IDF_EmailAddress' )->get_user_for_email_address ( $this->cleaned_data ['email'] );
		if ($user != null and $user->id != $this->user->id) {
			throw new Pluf_Form_Invalid ( sprintf ( __ ( 'The email "%s" is already used.' ), $this->cleaned_data ['email'] ) );
		}
		return $this->cleaned_data ['email'];
	}
	
	/**
	 * بررسی یکی بودن گذرواژه‌ها
	 *
	 * @see Pluf_Form::clean()
	 */
	public function clean() {
		if (! isset ( $this->errors ['password'] ) && ! isset ( $this->errors ['password2'] )) {
			$password1 = $this->cleaned_data ['password'];
			$password2 = $this->cleaned_data ['password2'];
			if ($password1 != $password2) {
				throw new Pluf_Form_Invalid ( __ ( 'The passwords do not match. Please give them again.' ) );
			}
		}
		return $this->cleaned_data;
	}
}
