<?php

/**
 * مدل داده‌ای برچسب را ایجاد می‌کند.
 * 
 * @param unknown $object
 * @return Pluf_User
 */
function KM_Shortcuts_labelDateFactory ($object)
{
    if ($object === null || ! isset($object))
        return new KM_Label();
    return $object;
}

/**
 * مدل داده‌ای دسته را ایجاد می‌کند.
 *
 * @param unknown $object            
 * @return Pluf_User
 */
function KM_Shortcuts_categoryDateFactory ($object)
{
    if ($object === null || ! isset($object))
        return new KM_Category();
    return $object;
}

function KM_Shortcuts_GetLabelOr404 ($id)
{
    $item = new KM_Label($id);
    if ((int) $id > 0 && $item->id == $id) {
        return $item;
    }
    throw new Pluf_HTTP_Error404(sprintf(__("label not found (%s)"), $id), 4321);
}

function KM_Shortcuts_GetCategoryOr404 ($id)
{
    $item = new KM_Category($id);
    if ((int) $id > 0 && $item->id == $id) {
        return $item;
    }
    throw new Pluf_HTTP_Error404(sprintf(__("category not found (%s)"), $id), 
            4322);
}

function KM_Shortcuts_GetCommentOr404 ($id)
{
    $item = new KM_Comment($id);
    if ((int) $id > 0 && $item->id == $id) {
        return $item;
    }
    throw new Pluf_HTTP_Error404(sprintf(__("comment not found (%s)"), $id), 
            4323);
}
