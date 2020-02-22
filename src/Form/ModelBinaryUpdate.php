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
namespace Pluf\Form;

use Pluf;
use Pluf\Tenant;
use Pluf\FileUtil;
use Pluf\Form\Field\File;
use Pluf\ModelUtils;

/**
 * updates a binary model
 *
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *        
 */
class ModelBinaryUpdate extends Model
{

    public function initFields($extra = array())
    {
        parent::initFields($extra);

        $this->fields['file'] = new File(array(
            'required' => false,
            'max_size' => Pluf::f('upload_max_size', 2097152),
            'move_function_params' => array(
                'upload_path' => Tenant::storagePath() . '/' . strtolower(ModelUtils::skipeName($this->model->_a['model'])),
                'file_name' => $this->model->id,
                'upload_path_create' => true,
                'upload_overwrite' => true
            )
        ));
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Pluf\Form\Model::save()
     */
    function save($commit = true)
    {
        $model = parent::save(false);
        // update the content
        if (array_key_exists('file', $this->cleaned_data)) {
            // Extract information of file
            $myFile = $this->data['file'];
            $model->file_name = $myFile['name'];
            // set mime type if not defined
            $mimeType = FileUtil::getMimeType($model->file_name);
            if (is_array($mimeType)) {
                $mimeType = $mimeType[0];
            }
            if (! array_key_exists('mime_type', $this->data)) {
                $model->mime_type = $mimeType;
            }
            if (! array_key_exists('media_type', $this->data)) {
                $mediaType = substr($mimeType, 0, strpos($mimeType, '/'));
                $model->media_type = $mediaType;
            }
        }
        if ($commit) {
            $model->update();
        }
        return $model;
    }
}
