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
 * متدهایی را برای بررسی شرط‌های امنیتی کار با دیجی‌دکی فراهم می‌کند.
 *
 */
class Message_Security
{

    public static function canAccessMessage ($request, $message)
    {
        if ($request->user->administrator) {
            return true;
        }
        if ($message->user === $request->user->id &&
                 $message->tenant === $request->tenant->id)
            return true;
        throw new Pluf_Exception_PermissionDenied(
                'You are not permited to access this message');
    }
}