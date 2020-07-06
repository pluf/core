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
use Pluf\HTTP\Error500;
use Pluf\HTTP\Request;
use Pluf\HTTP\Response;
use Pluf;

/**
 * Creates new item
 *
 * @author maso
 *        
 */
class ItemCreate extends ProcessorAdaptor
{

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Processor::request()
     */
    public function request(Request $request)
    {
        $modelName = $this->getModelName($request);
        $mapper = ObjectMapper::getInstance($request);
        if (! $mapper->hasMore()) {
            throw new Error403('No item in request to update');
        }
        $item = $mapper->mapNext($modelName);
        ObjectValidator::getInstance()->check($item);
        $item = Pluf::getDataRepository($modelName)->create($item);
        $request->item = $item;
    }

    /**
     * Put created item into the request
     *
     * {@inheritdoc}
     * @see \Pluf\Processor::response()
     */
    public function response(Request $request, Response $response): Response
    {
        if ($response->isOk()) {
            if (! $response->hasBody()) {
                $response->setBody($request->item);
            }
        }
        return $response;
    }

    /*
     * Fetchs model name from inputs
     *
     * 1. from params
     */
    protected function getModelName(Request $request): string
    {
        if (isset($request->params['model'])) {
            return $request->params['model'];
        }
        // TODO: maso, 2020: search in match and request form model name
        throw new Error500('The model class was not provided in the parameters.');
    }
}

