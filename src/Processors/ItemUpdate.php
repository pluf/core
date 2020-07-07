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
namespace Pluf\Processors;

use Pluf\ObjectMapper;
use Pluf\ObjectValidator;
use Pluf\ProcessorAdaptor;
use Pluf\HTTP\Error403;
use Pluf\HTTP\Request;
use Pluf;

class ItemUpdate extends ProcessorAdaptor
{

    public function request(Request $request)
    {
        $item = $request->item;
        $modelName = get_class($item);
        $mapper = ObjectMapper::getInstance($request);
        if (! $mapper->hasMore()) {
            throw new Error403('No item in request to update');
        }
        $newItem = $mapper->mapNext($modelName);
        $newItem->id = $item->id;
        ObjectValidator::getInstance()->check($newItem);
        $item = Pluf::getDataRepository(get_class($item))->update($newItem);
        $request->item = $item;
    }
}

