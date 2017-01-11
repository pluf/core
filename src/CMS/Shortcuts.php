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
 * Get content based on name
 * 
 * @param string $name
 * @throws CMS_Exception_ObjectNotFound
 * @return ArrayObject
 */
function CMS_Shortcuts_GetNamedContentOr404 ($name)
{
    $q = new Pluf_SQL('name=%s', array(
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
function CMS_Shortcuts_CleanName ($name)
{
    if ($name === 'new' || $name === 'find') {
        throw new Pluf_Exception(__('content name must not be new, find'));
    }
    $q = new Pluf_SQL('name=%s', array(
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