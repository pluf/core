<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. http://dpq.co.ir
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
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');
Pluf::loadFunction('User_Shortcuts_UserJsonResponse');

/**
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 * @since 0.1.0
 */
class User_Views
{

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function account ($request, $match)
    {
        return User_Shortcuts_UserJsonResponse($request->user);
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function updateAccount ($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function profile ($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }

    /**
     * اطلاعات حساب کاربری را به روز می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return unknown
     */
    public function updateProfile ($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }
}
