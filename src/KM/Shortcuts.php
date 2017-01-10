<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

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
