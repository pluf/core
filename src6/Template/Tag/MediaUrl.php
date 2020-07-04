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
namespace Pluf\Template\Tag;

use Pluf;

class MediaUrl extends \Pluf\Template\Tag
{

    function start($file = '')
    {
        echo MediaUrl::url($file);
    }

    public static function url($file = '')
    {
        if ($file !== '' && Pluf::f('last_update_file', false) && false !== ($last_update = Pluf::fileExists(Pluf::f('last_update_file')))) {
            $file = $file . '?' . substr(md5(filemtime($last_update)), 0, 5);
        }
        return Pluf::f('url_media', Pluf::f('app_base') . '/media') . $file;
    }
}

