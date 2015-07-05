<?php

Pluf::loadFunction('Pluf_Shortcuts_RenderToResponse');

/**
 * یک ساختار داده‌ای برای یک نرم‌افزار ایجاد می‌کند
 * 
 * @param unknown $object
 * @return SaaS_Application|unknown
 */
function SaaS_Shortcuts_applicationFactory($object) {
	if ($object == null || ! isset ( $object ))
		return new SaaS_Application();
	return $object;
}

/**
 * 
 * @param unknown $object
 * @return SaaS_Configuration|unknown
 */
function SaaS_Shortcuts_configurationFactory($object) {
	if ($object == null || ! isset ( $object ))
		return new SaaS_Configuration();
	return $object;
}


