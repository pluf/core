<?php

function SaaSCMS_Shortcuts_GetContentOr404 ($id)
{
	$item = new SaaSCMS_Content($id);
	if ((int) $id > 0 && $item->id == $id) {
		return $item;
	}
	throw new SaaSCMS_Exception_ObjectNotFound(
			"SaaSCMS content not found (Content id:" . $id . ")");
}

function SaaSCMS_Shortcuts_GetPageOr404 ($id)
{
	$item = new SaaSCMS_Page($id);
	if ((int) $id > 0 && $item->id == $id) {
		return $item;
	}
	throw new SaaSCMS_Exception_ObjectNotFound(
			"SaaSCMS page not found (Page id:" . $id . ")");
}