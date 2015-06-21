<?php

/**
 * تنظیم‌های مربوط به اعضای یک نرم‌افزار
 * 
 * برای سادگی مدیریت افراد یک نرم‌افزار یک فهرست از نام‌های کاربری برای
 * اعضا نگهداری می‌شود. این فرم این امکان را ایجاد می‌کند که با استفاده از
 * این فهرست‌ها اعضا را ویرایش کرد.
 * 
 * در پس زمینه این فرم با استفاده از الگوهای معرفی شده در دسترسی‌ها
 * تنظیم‌های مناسب برای کاربران ایجاد می‌شود.
 *
 */
class SaaS_Form_Member extends Pluf_Form {
	
	/**
	 * نرم افزار معادل را تعیین می‌کند.
	 *
	 * @var unknown
	 */
	public $application = null;
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see Pluf_Form::initFields()
	 */
	public function initFields($extra = array()) {
		$this->application = $extra ['application'];
		
		$this->fields ['owners'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'application owners' ),
				'initial' => '',
				'widget' => 'Pluf_Form_Widget_TextareaInput',
				'widget_attrs' => array (
						'rows' => 5,
						'cols' => 40 
				) 
		) );
		$this->fields ['members'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'application members' ),
				'widget_attrs' => array (
						'rows' => 7,
						'cols' => 40 
				),
				'widget' => 'Pluf_Form_Widget_TextareaInput' 
		) );
		$this->fields ['authorized'] = new Pluf_Form_Field_Varchar ( array (
				'required' => false,
				'label' => __ ( 'application authorized' ),
				'widget_attrs' => array (
						'rows' => 7,
						'cols' => 40 
				),
				'widget' => 'Pluf_Form_Widget_TextareaInput' 
		) );
	}
	
	/**
	 * اعضا را ذخیره می‌کند
	 *
	 * @param string $commit        	
	 * @throws Exception
	 */
	public function save($commit = true) {
		if (! $this->isValid ()) {
			throw new Exception ( __ ( 'Cannot save the model from an invalid form.' ) );
		}
		self::updateMemberships ( $this->application, $this->cleaned_data );
		// TODO: maso, 1394: سیگنال مناسب تولید شود.
		// $this->application->membershipsUpdated ();
		return $this->application;
	}
	
	/**
	 * صحت مالک‌های نرم‌افزار را بررسی می‌کند.
	 *
	 * @return string
	 */
	public function clean_owners() {
		return self::checkBadLogins ( $this->cleaned_data ['owners'] );
	}
	
	/**
	 * صحت اعضای نرم‌افزار را بررسی می‌کند.
	 *
	 * @return string
	 */
	public function clean_members() {
		return self::checkBadLogins ( $this->cleaned_data ['members'] );
	}
	
	/**
	 * صحت افراد مجاز را تعیین می‌کند.
	 *
	 * @return string
	 */
	public function clean_authorized() {
		return self::checkBadLogins ( $this->cleaned_data ['authorized'] );
	}
	
	/**
	 * بررسی وجود افراد تعیین شده
	 *
	 * @throws Pluf_Form_Invalid exception when bad logins are found
	 * @param
	 *        	string Comma, new line delimited list of logins
	 * @return string Comma, new line delimited list of logins
	 */
	public static function checkBadLogins($logins) {
		$bad = array ();
		foreach ( preg_split ( "/\015\012|\015|\012|\,/", $logins, - 1, PREG_SPLIT_NO_EMPTY ) as $login ) {
			$sql = new Pluf_SQL ( 'login=%s', array (
					trim ( $login ) 
			) );
			try {
				$user = Pluf::factory ( 'Pluf_User' )->getOne ( array (
						'filter' => $sql->gen () 
				) );
				if (null == $user) {
					$bad [] = $login;
				}
			} catch ( Exception $e ) {
				$bad [] = $login;
			}
		}
		$n = count ( $bad );
		if ($n) {
			$badlogins = Pluf_esc ( implode ( ', ', $bad ) );
			throw new Pluf_Form_Invalid ( sprintf ( _n ( 'The following login is invalid: %s.', 'The following logins are invalid: %s.', $n ), $badlogins ) );
		}
		return $logins;
	}
	
	/**
	 * عضویت در نرم‌افزار را به روز می‌کند.
	 *
	 * در این قسمت بر اساس تنظیم‌های ایجاد شد عضویت در نرم‌افزار را به روز می‌کند.
	 *
	 * @param
	 *        	SaaS_Application نرم‌افزار مورد نظر
	 * @param
	 *        	array داده‌های جدید مربوط به عضویت‌ها که با کلید‌های 'owners'، 'members' و 'autorized' تعیین می‌شوند.
	 */
	public static function updateMemberships($application, $cleaned_data) {
		// remove all the permissions
		$cm = $application->getMembershipData ();
		$def = array (
				'owners' => Pluf_Permission::getFromString ( 'IDF.project-owner' ),
				'members' => Pluf_Permission::getFromString ( 'IDF.project-member' ) 
		);
		$guser = new Pluf_User ();
		foreach ( $def as $key => $perm ) {
			foreach ( $cm [$key] as $user ) {
				Pluf_RowPermission::remove ( $user, $project, $perm );
			}
			foreach ( preg_split ( "/\015\012|\015|\012|\,/", $cleaned_data [$key], - 1, PREG_SPLIT_NO_EMPTY ) as $login ) {
				$sql = new Pluf_SQL ( 'login=%s', array (
						trim ( $login ) 
				) );
				$users = $guser->getList ( array (
						'filter' => $sql->gen () 
				) );
				if ($users->count () == 1) {
					Pluf_RowPermission::add ( $users [0], $project, $perm );
				}
			}
		}
	}
}


