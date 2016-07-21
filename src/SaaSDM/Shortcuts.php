<?php 

function SaaSDM_Shortcuts_GetSecureLinkOr404($secureLink){
	
	
}

function SaaSDM_Shortcuts_GetAssetOr404($id){
	$item = new SaaSDM_Asset($id);
	if ((int) $id > 0 && $item->id == $id) {
		return $item;
	}
	throw new SaaSDM_Exception_ObjectNotFound(
			"SaaSDM asset not found (asset id:" . $id . ")");

}
