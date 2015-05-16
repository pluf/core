<?php
/**
 * مدل داده‌ای کاربر را ایجاد می‌کند.
 * 
 * @param unknown $object
 * @return Pluf_User
 */
function User_Shortcuts_UserDateFactory($object) {
	$user_model = Pluf::f ( 'pluf_custom_user', 'Pluf_User' );
// 	$group_model = Pluf::f ( 'pluf_custom_group', 'Pluf_Group' );
	if ($object == null || ! isset ( $object ))
		return new $user_model ();
	$object;
}
