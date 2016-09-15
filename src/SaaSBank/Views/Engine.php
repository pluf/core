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
Pluf::loadFunction('SaaSBank_Shortcuts_GetEngineOr404');

/**
 *
 * @author maso <mostafa.barmsohry@dpq.co.ir>
 *        
 */
class SaaSBank_Views_Engine
{

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function find ($request, $match)
    {
        // XXX: maso, 1395:
        $items = SaaSBank_Service::engines();
        $page = array(
                'items' => $items,
                'counts' => count($items),
                'current_page' => 0,
                'items_per_page' => count($items),
                'page_number' => 1
        );
        return new Pluf_HTTP_Response_Json($page);
    }

    /**
     *
     * پارامترهایی که در این نمایش به عنوان ورودی در نظر گرفته می‌شوند عبارتند
     * از:
     *
     * <ul>
     * <li>type: نوع متور جستجو را تعیین می‌کند</li>
     * </ul>
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function get ($request, $match)
    {
        return new Pluf_HTTP_Response_Json(
                SaaSBank_Shortcuts_GetEngineOr404($match['type']));
    }
}
