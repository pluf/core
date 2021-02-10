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

use Pluf\Scion\UnitTrackerInterface;
use Pluf\Scion\Process\Http\Exception\InvalidBodyContentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Pars request body and convert it into a PHP Object
 *
 *
 * @author maso (mostafa.barmshory@gmail.com)
 *        
 */
class RequestBodyParser
{

    /**
     * Read the content value and convert it into a parsed one.
     *
     * For example reads the application/json body and set a equivalent map to the
     * request.
     *
     * @param RequestInterface $request
     *            to pars
     * @param UnitTrackerInterface $unitTracker
     *            tracker to manage the processing flow
     * @throws InvalidBodyContentException if the body mistmach with the content type
     * @return mixed the result of chain
     */
    public function __invoke(ServerRequestInterface $request, UnitTrackerInterface $unitTracker)
    {
        // Decode body
        $method = $request->getMethod();
        if ($method === 'POST') {
            $contentTypes = $request->getHeader('Content-Type') ?? [];

            $parsedContentType = '';
            foreach ($contentTypes as $contentType) {
                $fragments = explode(';', $contentType);
                $parsedContentType = current($fragments);
            }

            $contentTypesWithParsedBodies = [
                'application/json',
                'application/xml',
                'application/yml'
            ];

            if (in_array($parsedContentType, $contentTypesWithParsedBodies)) {
                $bodyString = $request->getBody()->getContents();
                switch ($parsedContentType) {
                    case 'application/json':
                        $bodyJson = json_decode($bodyString);
                        if (empty($bodyJson)) {
                            throw new InvalidBodyContentException("Fail to decode body as " . $parsedContentType);
                        }
                        $request = $request->withParsedBody($bodyJson);
                        break;
                    case 'application/xml':
                    case 'application/yml':
                    default:
                        throw new InvalidBodyContentException("Not supported content type: " . $parsedContentType);
                }
            }
        }
        // run pipeline
        return $unitTracker->next([
            'request' => $request
        ]);
    }
}

