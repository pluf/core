<?php

/**
 * دنبال‌کننده تعیین شده با شناسه را پیدا می‌کند.
 * 
 * در صورتی که دنبال‌کننده پیدا نشود خطا ۴۰۴ به عنوان نتیجه تولید و منتشر خواهد شد.
 *
 * @param int شناسه دنبال‌کننده را تعیین می‌کند
 * @throws SaaSNewspaper_Exception_FollowerNotFound
 * @return SaaSNewspaper_Follower دنبال‌کننده‌ای که پیدا شده است.
 */
function SaasNewspaper_Shortcuts_GetFollowerOr404 ($id)
{
    $item = new SaaSNewspaper_Follower($id);
    if ((int) $id > 0 && $item->id == $id) {
        return $item;
    }
    throw new SaaSNewspaper_Exception_FollowerNotFound(
            "Follower not found (Follower id:" . $id . ")");
}


function SaaSNewspaper_Shortcuts_GetFollowerListCount ($request)
{
    $count = 20;
    if (array_key_exists('_px_c', $request->GET)) {
        $count = $request->GET['_px_c'];
        if ($count > 20) {
            $count = 20;
        }
    }
    return $count;
}
