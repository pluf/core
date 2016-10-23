<?php
function SDP_Shortcuts_GetLinkOr404($id) {
	$item = new SDP_Link ( $id );
	if (( int ) $id > 0 && $item->id == $id) {
		return $item;
	}
	throw new SDP_Exception_ObjectNotFound ( "SDP link not found (link id:" . $id . ")" );
}
function SDP_Shortcuts_GetLinkBySecureIdOr404($secure_id) {
	$item = SDP_Link::getLinkBySecureId ( $secure_id );
	if ($item == null || $item->id <= 0) {
		throw new SDP_Exception_ObjectNotFound ( "SDP link not found (link id:" . $secure_id . ")" );
	}
	return $item;
}
function SDP_Shortcuts_GetAssetOr404($id) {
	$item = new SDP_Asset ( $id );
	if (( int ) $id > 0 && $item->id == $id) {
		return $item;
	}
	throw new SDP_Exception_ObjectNotFound ( "SDP asset not found (asset id:" . $id . ")" );
}
function SDP_Shortcuts_GetPlanTemplateOr404($id) {
	$item = new SDP_PlanTemplate ( $id );
	if (( int ) $id > 0 && $item->id == $id) {
		return $item;
	}
	throw new SDP_Exception_ObjectNotFound ( "SDP plan template not found (plan template id:" . $id . ")" );
}
function SDP_Shortcuts_GetPlanOr404($id) {
	$item = new SDP_Plan ( $id );
	if (( int ) $id > 0 && $item->id == $id) {
		return $item;
	}
	throw new SDP_Exception_ObjectNotFound ( "SDP plan not found (plan id:" . $id . ")" );
}
function SDP_Shortcuts_GetAccountOr404($id) {
	$item = new SDP_Account ( $id );
	if (( int ) $id > 0 && $item->id == $id) {
		return $item;
	}
	throw new SDP_Exception_ObjectNotFound ( "SDP account not found (plan id:" . $id . ")" );
}
