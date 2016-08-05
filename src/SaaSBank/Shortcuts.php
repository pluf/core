<?php

Pluf::loadFunction('Pluf_Shortcuts_RenderToResponse');

/**
 * یک ساختار داده‌ای برای یک اپارتمان ایجاد می‌کند
 * 
 * @param unknown $object
 * @return HM_Models_Apartment|unknown
 */
function SaaSBank_Shortcuts_receiptFactory($object) {
	if ($object == null || ! isset ( $object ))
		return new Bank_Receipt();
	return $object;
}
