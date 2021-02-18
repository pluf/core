<?php
/*
 * <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) <year> <name of author>
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
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */
namespace Pluf\Core\Process\Http;

use Pluf\Core\Exception;
use Pluf\Scion\UnitTrackerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;
use Pluf\Orm\ObjectMapper;
use Throwable;

class ResponseBodyEncoder
{

    //
    public function __invoke(
        ServerRequestInterface $request, 
        ResponseInterface $response, 
        StreamFactoryInterface $streamFactory, 
        LoggerInterface $logger,
        ObjectMapper $objectMapperJson,
        UnitTrackerInterface $unitTracker)
    {
        $result = "";
        $status = 200;
        try {
            $result = $unitTracker->next();
        } catch (Throwable $t) {
            $result = $this->convertToSerializable($t);
            $status = $result->getStatus();
        }
        
        // TODO: maso, 2021: support objectMapper
        // $supportMime = $request->getHeader("Accepted");
        $contentType = 'application/json';
        $resultEncode = $objectMapperJson->writeValueAsString($result);

        return $response->withStatus($status)
            ->withHeader("Content-Type", $contentType)
            ->withBody($streamFactory->createStream($resultEncode));
    }
    
    public function convertToSerializable(Throwable $t){
        if ($t instanceof Exception) {
            return $t;
        }
        $message = $t->getMessage();
        $params = [];
        $solutions = [];
        $previous = $t;
        if($t instanceof \Pluf\Orm\Exception){
            $params = $t->getParams();
            $solutions = $t->getSolutions();
        } else if($t instanceof \atk4\core\Exception){
            $params = $t->getParams();
            $solutions = $t->getSolutions();
        }
        return new Exception\UnhandledException(
            status: 500,
            code: $t->getCode(),
            message: $t->getMessage(),
            params: $params,
            previous: $previous,
            solutions: $solutions);
    }
}

