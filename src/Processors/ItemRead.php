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

use Pluf\ProcessorAdaptor;
use Pluf\Data\Query;
use Pluf\HTTP\Error404;
use Pluf\HTTP\Error500;
use Pluf\HTTP\Request;
use Pluf\HTTP\Response;
use Pluf;

class ItemRead extends ProcessorAdaptor
{

    /**
     * Reqd the item from model repositories and put it into the request.
     *
     * @param Request $request
     */
    public function request(Request $request)
    {
        // Set the default
        $modelName = $this->getModelName($request);
        $modelId = $this->getModelId($request);
        ;
        $item = $this->getObjectOr404($modelName, $modelId);
        $request->item = $item;
    }

    /**
     * If there is no body, the loaded item will be set as the response body.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function response(Request $request, Response $response): Response
    {
        // If the response is not OK we do anything
        if ($response->isOk()) {
            // If the body is not set we use the item as body
            if (! $response->isBodySet()) {
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

    /*
     * Gets model id from the request
     */
    protected function getModelId(Request $request)
    {
        return $request->match['modelId'];
    }

    /*
     * Finds item with the $modelId
     */
    protected function getObjectOr404(string $modelName, $modelId)
    {
        $items = Pluf::getDataRepository($modelName)->get(new Query([
            'filter' => [
                [
                    'id',
                    '=',
                    $modelId
                ]
            ]
        ]));
        if (sizeof($items) == 0) {
            throw new Error404('Request resource with ID:' . $modelId . ' not found');
        }
        return $items[0];
    }
}

