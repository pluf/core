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
use Psr\Http\Message\RequestInterface;

class IfPathAndMethodIs
{

    private string $regex;

    private array $methods;

    private bool $removePrefix = true;

    public function __construct(string $regex, array $methods = [
        'GET',
        'POST',
        'DELETE'
    ], bool $removePrefix = true)
    {
        $this->regex = $regex;
        $this->methods = $methods;
        $this->removePrefix = $removePrefix;
    }

    public function __invoke(RequestInterface $request, UnitTrackerInterface $unitTracker)
    {
        $uri = $request->getUri();
        $requestPath = $uri->getPath();
        $method = $request->getMethod();
        $match = [];
        if (! in_array($method, $this->methods) || ! preg_match($this->regex, $requestPath, $match)) {
            return $unitTracker->jump();
        }
        if ($this->removePrefix) {
            $match = array_merge($match, [
                'request' => $request->withUri($uri->withPath(substr($requestPath, strlen($match[0]))))
            ]);
        }
        return $unitTracker->next($match);
    }
}

