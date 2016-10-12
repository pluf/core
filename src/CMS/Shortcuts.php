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
    if (isset($item) && $item->count() == 1) {
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

/**
 * یک نام جدید را بررسی می‌کند.
 *
 * نام یک محتوی باید در یک ملک به صورت انحصاری تعیین شود. بنابر این روال
 * بررسی می‌کند که آیا محتویی هم نام با نام در نظر گرفته شده در ملک وجود دارد
 * یا نه.
 *
 * این فراخوانی در فرم‌ها کاربرد دارد.
 *
 * @param unknown $name            
 * @param unknown $tenant            
 * @throws Pluf_Exception
 * @return unknown
 */
function CMS_Shortcuts_CleanName ($name, $tenant)
{
    if ($name === 'new' || $name === 'find') {
        throw new Pluf_Exception(__('content name must not be new, find'));
    }
    $q = new Pluf_SQL('tenant=%s and name=%s', 
            array(
                    $tenant->id,
                    $name
            ));
    $items = Pluf::factory('CMS_Content')->getList(
            array(
                    'filter' => $q->gen()
            ));
    if (! isset($items) || $items->count() == 0) {
        return $name;
    }
    throw new Pluf_Exception(
            sprintf(__('content with the same name exist (name: %s'), $name));
}