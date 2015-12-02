<?php

Pluf::loadFunction('Pluf_Shortcuts_RenderToResponse');

/**
 * یک ساختار داده‌ای برای یک واحد ایجاد می‌کند
 * 
 * @param unknown $object
 * @return HM_Part|unknown
 */
function HM_Shortcuts_partFactory($object) {
	if ($object == null || ! isset ( $object ))
		return new HM_Part();
	return $object;
}

/**
 * یک مدل داده جدید برای پرداخت ایجاد می‌کند.
 * 
 * @param unknown $object
 * @return HM_Payment|unknown
 */
function HM_Shortcuts_paymentFactory($object) {
	if ($object == null || ! isset ( $object ))
		return new HM_Payment();
	return $object;
}

/**
 * یک مدل داده جدید برای پیام ایجاد می‌کند.
 *
 * @param unknown $object
 * @return HM_Message|unknown
 */
function HM_Shortcuts_messageFactory($object) {
	if ($object == null || ! isset ( $object ))
		return new HM_Message();
	return $object;
}