<?php
/**
 * مدل داده‌ای کاربر
 * 
 */
class Pluf_User extends Pluf_Model {
	public $_model = 'Pluf_User';
	
	/**
	 * کلد جلسه کاربر را تعیین می‌کند.
	 */
	public $session_key = '_PX_Pluf_User_auth';
	
	/**
	 * Cache of the permissions.
	 */
	public $_cache_perms = null;
	function init() {
		$langs = Pluf::f ( 'languages', array (
				'en' 
		) );
		$this->_a ['verbose'] = __ ( 'user' );
		$this->_a ['table'] = 'users';
		$this->_a ['model'] = 'Pluf_User';
		$this->_a ['cols'] = array (
				// It is mandatory to have an "id" column.
				'id' => array (
						'type' => 'Pluf_DB_Field_Sequence',
						// It is automatically added.
						'blank' => true 
				),
				'version' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => true 
				),
				'login' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => false,
						'unique' => true,
						'size' => 50,
						'verbose' => __ ( 'login' ) 
				),
				'first_name' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 100,
						'verbose' => __ ( 'first name' ) 
				),
				'last_name' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => false,
						'size' => 100,
						'verbose' => __ ( 'last name' ) 
				),
				'email' => array (
						'type' => 'Pluf_DB_Field_Email',
						'blank' => false,
						'verbose' => __ ( 'email' ) 
				),
				'password' => array (
						'type' => 'Pluf_DB_Field_Password',
						'blank' => false,
						'verbose' => __ ( 'password' ),
						'size' => 150,
						'help_text' => __ ( 'Format: [algo]:[salt]:[hash]' ) 
				),
				'groups' => array (
						'type' => 'Pluf_DB_Field_Manytomany',
						'blank' => true,
						'model' => Pluf::f ( 'pluf_custom_group', 'Pluf_Group' ),
						'relate_name' => 'users' 
				),
				'permissions' => array (
						'type' => 'Pluf_DB_Field_Manytomany',
						'blank' => true,
						'model' => 'Pluf_Permission' 
				),
				'administrator' => array (
						'type' => 'Pluf_DB_Field_Boolean',
						'default' => false,
						'blank' => true,
						'verbose' => __ ( 'administrator' ) 
				),
				'staff' => array (
						'type' => 'Pluf_DB_Field_Boolean',
						'default' => false,
						'blank' => true,
						'verbose' => __ ( 'staff' ) 
				),
				'active' => array (
						'type' => 'Pluf_DB_Field_Boolean',
						'default' => true,
						'blank' => true,
						'verbose' => __ ( 'active' ) 
				),
				'language' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'default' => $langs [0],
						'size' => 5,
						'verbose' => __ ( 'language' ),
						'help_text' => __ ( 'Prefered language of the user for the interface. Use the 2 or 5 letter code like "fr", "en", "fr_QC" or "en_US".' ) 
				),
				'timezone' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'default' => 'Europe/Berlin',
						'size' => 45,
						'verbose' => __ ( 'time zone' ),
						'help_text' => __ ( 'Time zone of the user to display the time in local time.' ) 
				),
				'date_joined' => array (
						'type' => 'Pluf_DB_Field_Datetime',
						'blank' => true,
						'verbose' => __ ( 'date joined' ),
						'editable' => false 
				),
				'last_login' => array (
						'type' => 'Pluf_DB_Field_Datetime',
						'blank' => true,
						'verbose' => __ ( 'last login' ),
						'editable' => false 
				) 
		);
		$this->_a ['idx'] = array (
				'login_idx' => array (
						'col' => 'login',
						'type' => 'unique' 
				) 
		);
		$this->_a ['views'] = array ();
		if (Pluf::f ( 'pluf_custom_user', false ))
			$this->extended_init ();
	}
	
	/**
	 * Hook for extended class
	 */
	function extended_init() {
		return;
	}
	
	/**
	 * نمایش رشته‌ای از یک کاربر
	 *
	 * این کلاس یک نمایش رشته‌ای از کاربر ایجاد می‌کند.
	 */
	function __toString() {
		$repr = $this->last_name;
		if (strlen ( $this->first_name ) > 0) {
			$repr = $this->first_name . ' ' . $repr;
		}
		return $repr;
	}
	
	/**
	 * فراخوانی‌های پیش از حذف کاربر
	 *
	 * پیش از این که کاربر حذف شود یک سیگنال به کل سیستم ارسال شده و حذف کاربر گزارش می‌شود.
	 */
	function preDelete() {
		/**
		 * [signal]
		 *
		 * Pluf_User::preDelete
		 *
		 * [sender]
		 *
		 * Pluf_User
		 *
		 * [description]
		 *
		 * This signal allows an application to perform special
		 * operations at the deletion of a user.
		 *
		 * [parameters]
		 *
		 * array('user' => $user)
		 */
		$params = array (
				'user' => $this 
		);
		Pluf_Signal::send ( 'Pluf_User::preDelete', 'Pluf_User', $params );
		
		if (Pluf::f ( 'pluf_use_rowpermission', false )) {
			$_rpt = Pluf::factory ( 'Pluf_RowPermission' )->getSqlTable ();
			$sql = new Pluf_SQL ( 'owner_class=%s AND owner_id=%s', array (
					$this->_a ['model'],
					$this->_data ['id'] 
			) );
			$this->_con->execute ( 'DELETE FROM ' . $_rpt . ' WHERE ' . $sql->gen () );
		}
	}
	
	/**
	 * Set the password of a user.
	 *
	 * You need to manually save the user to store the password in the
	 * database. The supported algorithms are md5, crc32 and sha1,
	 * sha1 being the default.
	 *
	 * @param
	 *        	string New password
	 * @return bool Success
	 */
	function setPassword($password) {
		$salt = Pluf_Utils::getRandomString ( 5 );
		$this->password = 'sha1:' . $salt . ':' . sha1 ( $salt . $password );
		return true;
	}
	
	/**
	 * تعیین صحت گذرواژه کاربر
	 *
	 * در صورتی که گذرواژه کاربر تعیین شود، این متد بررسی می‌کن که آیا مقدار درستی برای
	 * آن تعیین شده است یا نه.
	 *
	 * @param
	 *        	string گذرواژه
	 * @return bool مقدار درستی در صورت موفقیت
	 */
	function checkPassword($password) {
		if ($this->password == '') {
			return false;
		}
		list ( $algo, $salt, $hash ) = explode ( ':', $this->password );
		if ($hash == $algo ( $salt . $password )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Check if the login creditentials are valid.
	 *
	 * @param
	 *        	string Login
	 * @param
	 *        	string Password
	 * @return mixed False or matching user
	 */
	function checkCreditentials($login, $password) {
		$where = 'login = ' . $this->_toDb ( $login, 'login' );
		$users = $this->getList ( array (
				'filter' => $where 
		) );
		if ($users === false or count ( $users ) !== 1) {
			return false;
		}
		if ($users [0]->active and $users [0]->checkPassword ( $password )) {
			return $users [0];
		}
		return false;
	}
	
	/**
	 * خصوصیت‌های کاربر را استخراج کرده و در اختیار قرار می دهد.
	 * 
	 * @param unknown $login
	 * @return boolean|ArrayObject
	 */
	function getUser($login) {
		$where = 'login = ' . $this->_toDb ( $login, 'login' );
		$users = $this->getList ( array (
				'filter' => $where 
		) );
		if ($users === false or count ( $users ) !== 1) {
			return false;
		}
		return $users [0];
	}
	
	/**
	 * Set the last_login and date_joined before creating.
	 */
	function preSave($create = false) {
		if (! ($this->id > 0)) {
			$this->last_login = gmdate ( 'Y-m-d H:i:s' );
			$this->date_joined = gmdate ( 'Y-m-d H:i:s' );
		}
	}
	
	/**
	 * Check if a user is anonymous.
	 *
	 * @return bool True if the user is anonymous.
	 */
	function isAnonymous() {
		return (0 === ( int ) $this->id);
	}
	
	/**
	 * Get all the permissions of a user.
	 *
	 * @param
	 *        	bool Force the reload of the list of permissions (false)
	 * @return array List of permissions
	 */
	function getAllPermissions($force = false) {
		if ($force == false and ! is_null ( $this->_cache_perms )) {
			return $this->_cache_perms;
		}
		$this->_cache_perms = array ();
		$perms = ( array ) $this->get_permissions_list ();
		$groups = $this->get_groups_list ();
		$ids = array ();
		foreach ( $groups as $group ) {
			$ids [] = $group->id;
		}
		if (count ( $ids ) > 0) {
			$gperm = new Pluf_Permission ();
			$f_name = strtolower ( Pluf::f ( 'pluf_custom_group', 'Pluf_Group' ) ) . '_id';
			$perms = array_merge ( $perms, ( array ) $gperm->getList ( array (
					'filter' => $f_name . ' IN (' . join ( ', ', $ids ) . ')',
					'view' => 'join_group' 
			) ) );
		}
		foreach ( $perms as $perm ) {
			if (! in_array ( $perm->application . '.' . $perm->code_name, $this->_cache_perms )) {
				$this->_cache_perms [] = $perm->application . '.' . $perm->code_name;
			}
		}
		if (Pluf::f ( 'pluf_use_rowpermission', false ) and $this->id) {
			$growp = new Pluf_RowPermission ();
			$sql = new Pluf_SQL ( 'owner_id=%s AND owner_class=%s', array (
					$this->id,
					'Pluf_User' 
			) );
			if (count ( $ids ) > 0) {
				$sql2 = new Pluf_SQL ( 'owner_id IN (' . join ( ', ', $ids ) . ') AND owner_class=%s', array (
						Pluf::f ( 'pluf_custom_group', 'Pluf_Group' ) 
				) );
				$sql->SOr ( $sql2 );
			}
			$perms = $growp->getList ( array (
					'filter' => $sql->gen (),
					'view' => 'join_permission' 
			) );
			foreach ( $perms as $perm ) {
				$perm_string = $perm->application . '.' . $perm->code_name . '#' . $perm->model_class . '(' . $perm->model_id . ')';
				if ($perm->negative) {
					$perm_string = '!' . $perm_string;
				}
				if (! in_array ( $perm_string, $this->_cache_perms )) {
					$this->_cache_perms [] = $perm_string;
				}
			}
		}
		return $this->_cache_perms;
	}
	
	/**
	 * Check if a user as a permission.
	 *
	 * @param
	 *        	string Permission
	 * @param
	 *        	Object Object for row level permission (null)
	 * @return bool True if the user has the permission.
	 */
	function hasPerm($perm, $obj = null) {
		if (! $this->active)
			return false;
		if ($this->administrator)
			return true;
		$perms = $this->getAllPermissions ();
		if (! is_null ( $obj )) {
			$perm_row = $perm . '#' . $obj->_a ['model'] . '(' . $obj->id . ')';
			if (in_array ( '!' . $perm_row, $perms ))
				return false;
			if (in_array ( $perm_row, $perms ))
				return true;
		}
		if (in_array ( $perm, $perms ))
			return true;
		return false;
	}
	
	/**
	 * Check if a user one or more application permission.
	 *
	 * @return bool True if the user has some.
	 */
	function hasAppPerms($app) {
		if ($this->administrator)
			return true;
		foreach ( $this->getAllPermissions () as $perm ) {
			if (0 === strpos ( $perm, $app . '.' )) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Set a message.
	 *
	 * The user must not be anonymous.
	 *
	 * @param
	 *        	string Message
	 * @return bool Success
	 */
	function setMessage($message) {
		if ($this->isAnonymous ()) {
			return false;
		}
		$m = new Pluf_Message ();
		$m->user = $this;
		$m->message = $message;
		return $m->create ();
	}
	
	/**
	 * Get messages and delete them.
	 *
	 * The user must not be anonymous.
	 *
	 * @return mixed False if anonymous, else ArrayObject
	 */
	function getAndDeleteMessages() {
		if ($this->isAnonymous ()) {
			return false;
		}
		$messages = new ArrayObject ();
		$ms = $this->get_pluf_message_list ();
		foreach ( $ms as $m ) {
			$messages [] = $m->message;
			$m->delete ();
		}
		return $messages;
	}
	
	/**
	 * Get profile.
	 *
	 * Retrieve the profile of the current user. If not profile in the
	 * database a Pluf_Exception_DoesNotExist exception is thrown,
	 * just catch it and create a profile.
	 *
	 * @return Pluf_Model User profile
	 */
	function getProfile() {
		$pclass = Pluf::f ( 'user_profile_class', false );
		if (false == $pclass) {
			throw new Pluf_Exception_SettingError ( __ ( '"user_profile_class" setting not defined.' ) );
		}
		$db = $this->getDbConnection ();
		$sql = new Pluf_SQL ( sprintf ( '%s=%%s', $db->qn ( 'user' ) ), array (
				$this->id 
		) );
		$users = Pluf::factory ( $pclass )->getList ( array (
				'filter' => $sql->gen () 
		) );
		if ($users->count () != 1) {
			throw new Pluf_Exception_DoesNotExist ( sprintf ( __ ( 'No profiles available for user: %s' ), ( string ) $this ) );
		}
		return $users [0];
	}
}
