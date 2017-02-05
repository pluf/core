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
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Backup_Shortcuts_BackupRun');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

class Backup_Views
{

    /**
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public static function create ($request, $match)
    {
        $tenant = Pluf_Tenant::current();
        $object = new Backup_Backup();
        $form = Pluf_Shortcuts_GetFormForModel($object, $request->REQUEST);
        $object = $form->save();
        $object->file_path = sprintf('%s/%s/backups/%s', Pluf::f('upload_path'), 
                $tenant->id, $object->id);
        $object->update();
        Backup_Shortcuts_BackupRun($object->file_path);
        return new Pluf_HTTP_Response_Json($object);
    }

    /**
     * 
     * @param Pluf_HTTP_Request $request            
     * @param array $match
     */
    public static function restore ($request, $match)
    {}
    
    /**
     * 
     * @param Pluf_HTTP_Request $request            
     * @param array $match
     */
    public static function download ($request, $match)
    {}
}