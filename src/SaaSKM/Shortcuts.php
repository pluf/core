<?php

function SaaSKM_Shortcuts_GetTagOr404 ($id)
{
    $item = new SaaSKM_Tag($id);
    if ((int) $id > 0 && $item->id == $id) {
        return $item;
    }
    throw new SaaSKM_Exception_TagNotFound("Tag not found (Tag id:" . $id . ")");
}
