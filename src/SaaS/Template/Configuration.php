<?php

/**
 * دسترسی به تنظیم‌های عمومی نرم افزار در الگو
 * 
 * @author maso
 *
 */
class SaaS_Template_Configuration extends Pluf_Template_Tag {
	
	/**
	 *
	 * @param unknown $app        	
	 * @param unknown $key        	
	 * @param unknown $default        	
	 */
	function start($app, $key, $default) {
		$application = Pluf_Shortcuts_GetObjectOr404 ( 'Pluf_Tenant', $app );
		echo $application->getProperty ( $key, $default );
	}
}
