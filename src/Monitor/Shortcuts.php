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
/**
 * Return monitor level
 * 
 * @param Pluf_HTTP_Request $request
 * @throws Pluf_HTTP_Error403
 * @return number
 */
function Monitor_Shortcuts_UserLevel ($request)
{
    $user = $request->user;
    if ($user->isAnonymous() || !$user->active) {
        return 10;
    }
    if($user->administrator){
        return 0;
    }
    if($user->staff){
        return 1;
    }
    if($user->hasPerm('Pluf::owner')){
        return 2;
    }
    if($user->hasPerm('Pluf::member')){
        return 3;
    }
    if($user->hasPerm('Pluf::authorized')){
        return 4;
    }
}
