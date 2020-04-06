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
namespace Pluf\Data;

use JsonSerializable;
use Pluf;

class Model implements JsonSerializable
{
    
    
//     function __construct($pk = null, $values = array()){}
    
    
//     //-----------------------------------------------------------
//     // Old API
//     //-----------------------------------------------------------
//     public function init(): void {}
//     public function getRelationKeysToModel($model, $type): array {}
//     public function getData(): array {}
//     public function setAssoc(Model $model, ?string $assocName = null) {}
//     public function delAssoc(Model $model, ?string $assocName = null) {}
//     public function batchAssoc($model_name, $ids) {}
//     public function getOne($p = array()): ?Model {}
//     public function getList($p = array()) : array{}
//     public function getCount($p = array()) {}
//     public function getRelated($model, $method = null, $p = array()) {}
//     public function update($where = '') {}
//     public function create($raw = false) {}
//     public function delete() {}
//     public function setFromFormData($cleaned_values) {}
//     public function isAnonymous() {}
//     public function getSchema() {}
    
//     public function getView(string $name): array {}
//     public function setView(string $name, array $view): void {}
//     public function hasView(?string $name = null): bool {}
//     public function getIndexes(): array {}
    
    

    /**
     * Traditional JSON Encoding
     *
     * In Pluf V5 supports JsonSerializable for each model, This is a new implementation
     * to support old Data Model.
     *
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return ModelEncoder::getInstance(ModelEncoder::JSON)//
            ->setProperties(Pluf::getConfigurationPrifix('data_', true))
            ->setModel($this)
            ->encode($this);
    }
}

