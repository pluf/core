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

function SaaSDM_Shortcuts_GetPlanTemplateOr404($id){
	$item = new SaaSDM_PlanTemplate($id);
	if ((int) $id > 0 && $item->id == $id) {
		return $item;
	}
	throw new SaaSDM_Exception_ObjectNotFound(
			"SaaSDM plan template not found (plan template id:" . $id . ")");

}
