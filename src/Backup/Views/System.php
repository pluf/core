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
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');
Pluf::loadFunction('Backup_Shortcuts_BackupRun');
Pluf::loadFunction('Pluf_Form_Field_File_moveToUploadFolder');

class Backup_Views_System
{

    /**
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public static function create ($request, $match)
    {
        // Create
        $file_path = sprintf('%s/backup', Pluf::f('upload_path'));
        Backup_Shortcuts_BackupRun($file_path, false);
        
        // compress
        $zip = new ZipArchive();
        $file = Pluf::f('tmp_folder', '/var/tmp') . '/backup.zip';
        $zip->open($file, ZipArchive::CREATE);
        foreach (glob($file_path . '/*') as $f) {
            $zip->addFile($f, basename($f));
        }
        $zip->close();
        $response = new Pluf_HTTP_Response_File($file, 'application/zip', true);
        $response->headers['Content-Disposition'] = 'attachment; filename="backup.zip"';
        return $response;
    }

    /**
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public static function restore ($request, $match)
    {
        $file_path = sprintf('%s/backup', Pluf::f('upload_path'));
        if (array_key_exists('file', $request->FILES)) {
            Pluf_FileUtil::removedir($file_path);
            if (false == @mkdir($file_path, 0777, true)) {
                throw new Pluf_Form_Invalid(
                        'An error occured when creating the upload path.');
            }
            Pluf_Form_Field_File_moveToUploadFolder($request->FILES['file'], 
                    array(
                            'file_name' => 'backup.zip',
                            'upload_path' => $file_path,
                            'upload_path_create' => true,
                            'upload_overwrite' => true
                    ));
            
            $zip = new ZipArchive();
            if ($zip->open($file_path . '/backup.zip') === TRUE) {
                $zip->extractTo($file_path);
                $zip->close();
            } else {
                throw new Pluf_Exception('Unable to unzip SPA.');
            }
            unlink($file_path . '/backup.zip');
        }
        
        // Create
        Backup_Shortcuts_RestoreRun($file_path, false);
        return new Pluf_HTTP_Response_Json(true);
    }

    /**
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public static function delete ($request, $match)
    {
        $file_path = sprintf('%s/backup', Pluf::f('upload_path'));
        Pluf_FileUtil::removedir($file_path);
        return new Pluf_HTTP_Response_Json(true);
    }
}