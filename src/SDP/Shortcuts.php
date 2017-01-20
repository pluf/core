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
function SDP_Shortcuts_GetAccountOr404($id) {
	$item = new SDP_Account ( $id );
	if (( int ) $id > 0 && $item->id == $id) {
		return $item;
	}
	throw new SDP_Exception_ObjectNotFound ( "SDP account not found (plan id:" . $id . ")" );
}

function SDP_Shortcuts_GetTagByNameOr404 ($tenant, $name)
{
    $q = new Pluf_SQL('tenant=%s and name=%s',
        array(
            $tenant->id,
            $name
        ));
    $item = new SDP_Tag();
    $item = $item->getList(
        array(
            'filter' => $q->gen()
        ));
    if (isset($item) && $item->count() == 1) {
        return $item[0];
    }
    if ($item->count() > 1) {
        Pluf_Log::error(
            sprintf(
                'more than one tag exist with the name $s in tenant $s',
                $name, $tenant->id));
        return $item[0];
    }
    throw new SDP_Exception_ObjectNotFound(
        "SDP tag not found (Tag name:" . $name . ")");
}