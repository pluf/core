<?php

Pluf::loadFunction('Pluf_Shortcuts_RenderToResponse');

/**
 * یک ساختار داده‌ای برای یک اپارتمان ایجاد می‌کند
 * 
 * @param unknown $object
 * @return HM_Models_Apartment|unknown
 */
function SaaS_Shortcuts_apartmentFactory($object) {
	if ($object == null || ! isset ( $object ))
		return new HM_Models_Apartment();
	return $object;
}


