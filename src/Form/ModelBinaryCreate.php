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
 * Creats a binary model
 *
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *
 */
class Pluf_Form_ModelBinaryCreate extends Pluf_Form_Model
{

    function save($commit = true)
    {
        if (! $this->isValid()) {
            throw new Exception_Form('cannot save the ' . $this->model->_a['model'] . ' from an invalid form', $this);
        }
        // Create the content
        $item = $this->model;
        $item->setFromFormData($this->cleaned_data);
        $itemStoragePath = Pluf_Tenant::storagePath() . '/' . strtolower(ModelUtils::skipeName($this->model->_a['model']));
        if (! is_dir($itemStoragePath)) {
            if (false == @mkdir($itemStoragePath, 0777, true)) {
                throw new Pluf_Form_Invalid('An error occured when creating the upload path. Please try to send the file again.');
            }
        }
        if ($commit) {
            $item->create();
            $item->file_path = $itemStoragePath . '/' . $item->id;
            $item->update();
        }
        return $item;
    }
}
