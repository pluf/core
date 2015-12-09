<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
#
# Plume Framework is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume Framework is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

class Pluf_Form_Field_File extends Pluf_Form_Field
{
    public $widget = 'Pluf_Form_Widget_FileInput';
    public $move_function = 'Pluf_Form_Field_File_moveToUploadFolder';
    public $max_size = 2097152; // 2MB
    public $move_function_params = array();

    /**
     * Validate some possible input for the field.
     *
     * @param mixed Input
     * @return string Path to the file relative to 'upload_path'
     */
    function clean($value)
    {
        parent::clean($value);
        if (is_null($value) and !$this->required) {
            return ''; // no file
        } elseif (is_null($value) and $this->required) {
            throw new Pluf_Form_Invalid(__('No files were uploaded. Please try to send the file again.'));
        }
        $errors = array();
        $no_files = false;
        switch ($value['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_INI_SIZE:
            throw new Pluf_Form_Invalid(sprintf(__('The uploaded file is too large. Reduce the size of the file to %s and send it again.'),
                      Pluf_Utils::prettySize(ini_get('upload_max_filesize'))));
            break;
        case UPLOAD_ERR_FORM_SIZE:
            throw new Pluf_Form_Invalid(sprintf(__('The uploaded file is too large. Reduce the size of the file to %s and send it again.'),
                      Pluf_Utils::prettySize($_REQUEST['MAX_FILE_SIZE'])));
            break;
        case UPLOAD_ERR_PARTIAL:
            throw new Pluf_Form_Invalid(__('The upload did not complete. Please try to send the file again.'));
            break;
        case UPLOAD_ERR_NO_FILE:
            if ($this->required) {
                throw new Pluf_Form_Invalid(__('No files were uploaded. Please try to send the file again.'));
            } else {
                return ''; // no file
            }
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
        case UPLOAD_ERR_CANT_WRITE:
            throw new Pluf_Form_Invalid(__('The server has no temporary folder correctly configured to store the uploaded file.'));
            break;
        case UPLOAD_ERR_EXTENSION:
            throw new Pluf_Form_Invalid(__('The uploaded file has been stopped by an extension.'));
            break;
        default:
            throw new Pluf_Form_Invalid(__('An error occured when upload the file. Please try to send the file again.'));
        }
        if ($value['size'] > $this->max_size) {
            throw new Pluf_Form_Invalid(sprintf(__('The uploaded file is to big (%1$s). Reduce the size to less than %2$s and try again.'), 
                                        Pluf_Utils::prettySize($value['size']),
                                        Pluf_Utils::prettySize($this->max_size)));
        }
        // copy the file to the final destination and updated $value
        // with the final path name. 'final_name' is relative to
        // Pluf::f('upload_path')
        Pluf::loadFunction($this->move_function);
        // Should throw a Pluf_Form_Invalid exception if error or the
        // value to be stored in the database.
        return call_user_func($this->move_function, $value, 
                              $this->move_function_params);
    }
}

/**
 * Default move function. The file name is sanitized.
 *
 * In the extra parameters, options can be used so that this function is
 * matching most of the needs:
 *
 *  * 'upload_path': The path in which the uploaded file will be
 *                   stored.  
 *  * 'upload_path_create': If set to true, try to create the
 *                          upload path if not existing.
 *
 *  * 'upload_overwrite': Set it to true if you want to allow overwritting.
 *
 *  * 'file_name': Force the file name to this name and do not use the
 *                 original file name. If this name contains '%s' for
 *                 example 'myid-%s', '%s' will be replaced by the
 *                 original filename. This can be used when for
 *                 example, you want to prefix with the id of an
 *                 article all the files attached to this article.
 *
 * If you combine those options, you can dynamically generate the path
 * name in your form (for example date base) and let this upload
 * function create it on demand.
 * 
 * @param array Upload value of the form.
 * @param array Extra parameters. If upload_path key is set, use it. (array())
 * @return string Name relative to the upload path.
 */
function Pluf_Form_Field_File_moveToUploadFolder($value, $params=array())
{
    $name = Pluf_Utils::cleanFileName($value['name']);
    $upload_path = Pluf::f('upload_path', '/tmp');
    if (isset($params['file_name'])) {
        if (false !== strpos($params['file_name'], '%s')) {
            $name = sprintf($params['file_name'], $name);
        } else {
            $name = $params['file_name'];
        }
    }
    if (isset($params['upload_path'])) {
        $upload_path = $params['upload_path'];
    }
    $dest = $upload_path.'/'.$name;
    if (isset($params['upload_path_create']) 
        and !is_dir(dirname($dest))) {
        if (false == @mkdir(dirname($dest), 0777, true)) {
            throw new Pluf_Form_Invalid(__('An error occured when creating the upload path. Please try to send the file again.'));
        }
    }
    if ((!isset($params['upload_overwrite']) or $params['upload_overwrite'] == false) and file_exists($dest)) {
        throw new Pluf_Form_Invalid(sprintf(__('A file with the name "%s" has already been uploaded.'), $name));
    }
    if (@!move_uploaded_file($value['tmp_name'], $dest)) {
        throw new Pluf_Form_Invalid(__('An error occured when uploading the file. Please try to send the file again.'));
    } 
    @chmod($dest, 0666);
    return $name;
}
