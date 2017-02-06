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
class Tenant_Monitor
{

    public static function count ()
    {
        // XXX: maso, 1395
    }
    
    public static function storage ()
    {
        $result = array(
                'value' => 35,
                'min' => 0,
                'max' => Config_Service::get('storage.size.max', 1048576), // 1Mb
                'unit' => 'byte',
                'interval' => 1000000,
                'type' => 'scalar'
        );
        // maso, 2017: find storage size
        // FIXME: maso, 2017: using php native if is not linux
        $file_directory = Pluf_Tenant::storagePath();
        $output = exec('du -sk ' . $file_directory);
        $result['value'] = trim(str_replace($file_directory, '', $output)) * 1024;
        return $result;
    }
}

