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
namespace Pluf\Form\Field;

use Pluf\Form\Field;
use Pluf\FormInvalidException;
use Pluf\Utils;

class File extends Field
{

    public $widget = 'Pluf_Form_Widget_FileInput';

    public $move_function = 'Pluf_Form_Field_File_moveToUploadFolder';

    public $max_size = 2097152;

    // 2MB
    public $move_function_params = array();

    /**
     * Validate some possible input for the field.
     *
     * @param
     *            mixed Input
     * @return string Path to the file relative to 'upload_path'
     */
    function clean($value)
    {
        parent::clean($value);
        if (is_null($value) and ! $this->required) {
            return ''; // no file
        } elseif (is_null($value) and $this->required) {
            throw new FormInvalidException('No files were uploaded. Please try to send the file again.');
        }
        switch ($value['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
                throw new FormInvalidException(sprintf(__('The uploaded file is too large. Reduce the size of the file to %s and send it again.'), Utils::prettySize(ini_get('upload_max_filesize'))));
                break;
            case UPLOAD_ERR_FORM_SIZE:
                throw new FormInvalidException(sprintf('The uploaded file is too large. Reduce the size of the file to %s and send it again.'), Utils::prettySize($_REQUEST['MAX_FILE_SIZE']));
                break;
            case UPLOAD_ERR_PARTIAL:
                throw new FormInvalidException('The upload did not complete. Please try to send the file again.');
                break;
            case UPLOAD_ERR_NO_FILE:
                if ($this->required) {
                    throw new FormInvalidException('No files were uploaded. Please try to send the file again.');
                } else {
                    return ''; // no file
                }
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
            case UPLOAD_ERR_CANT_WRITE:
                throw new FormInvalidException('The server has no temporary folder correctly configured to store the uploaded file.');
                break;
            case UPLOAD_ERR_EXTENSION:
                throw new FormInvalidException('The uploaded file has been stopped by an extension.');
                break;
            default:
                throw new FormInvalidException('An error occured when upload the file. Please try to send the file again.');
        }
        if ($value['size'] > $this->max_size) {
            throw new FormInvalidException(sprintf('The uploaded file is to big (%1$s). Reduce the size to less than %2$s and try again.'), Utils::prettySize($value['size']), Utils::prettySize($this->max_size));
        }
        // Should throw a Pluf_Form_Invalid exception if error or the
        // value to be stored in the database.
        return call_user_func($this->move_function, $value, $this->move_function_params);
    }
}