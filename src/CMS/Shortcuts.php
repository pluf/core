<?php

function CMS_Shortcuts_GetContentOr404 ($id)
{
    $item = new CMS_Content($id);
    if ((int) $id > 0 && $item->id == $id) {
        return $item;
    }
    throw new CMS_Exception_ObjectNotFound(
            "CMS content not found (Content id:" . $id . ")");
}

function CMS_Shortcuts_GetNamedContentOr404 ($tenant, $name)
{
    $q = new Pluf_SQL('tenant=%s and name=%s', 
            array(
                    $tenant->id,
                    $name
            ));
    $item = new CMS_Content();
    $item = $item->getList(
            array(
                    'filter' => $q->gen()
            ));
    if (isset($item) && ! $item->count() == 1) {
        return $item[0];
    }
    if ($item->count() > 1) {
        Pluf_Log::error(
                sprintf(
                        'more than one content exist with the name $s in tenant $s', 
                        $name, $tenant->id));
        return $item[0];
    }
    throw new CMS_Exception_ObjectNotFound(
            "CMS content not found (Content name:" . $name . ")");
}

function CMS_Shortcuts_GetPageOr404 ($id)
{
    $item = new CMS_Page($id);
    if ((int) $id > 0 && $item->id == $id) {
        return $item;
    }
    throw new CMS_Exception_ObjectNotFound(
            "CMS page not found (Page id:" . $id . ")");
}