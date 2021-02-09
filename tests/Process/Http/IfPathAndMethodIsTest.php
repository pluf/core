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
namespace Pluf\Tests\Process\Http;

use PHPUnit\Framework\TestCase;
use Pluf\Http\UriFactory;
use Pluf\Scion\UnitTrackerInterface;
use Pluf\Scion\Process\Http\IfPathAndMethodIs;
use Psr\Http\Message\RequestInterface;

/**
 * IfMethodIsDelete test case.
 */
class IfPathAndMethodIsTest extends TestCase
{

    public ?UriFactory $uriFactory = null;

    /**
     *
     * @before
     */
    public function initServices()
    {
        $this->uriFactory = new UriFactory();
    }

    public function getPathAndMethodData(): array
    {
        return [
            [
                new IfPathAndMethodIs('#^/test$#', [
                    "GET",
                    "POST",
                    "DELETE",
                    "PUT"
                ], true),
                '/test',
                'HEAD',
                rand(),
                'jump'
            ],
            [
                new IfPathAndMethodIs('#^/test$#', [
                    "GET",
                    "POST",
                    "DELETE",
                    "PUT"
                ], true),
                '/1test',
                'PUT',
                rand(),
                'jump'
            ],
            [
                new IfPathAndMethodIs('#^/test$#', [
                    "POST",
                    "GET"
                ], true),
                '/test',
                'GET',
                rand(),
                'next'
            ],
            [
                new IfPathAndMethodIs('#^/test$#', [
                    "POST",
                    "GET"
                ], false),
                '/test',
                'GET',
                rand(),
                'next'
            ]
        ];
    }

    /**
     *
     * @dataProvider getPathAndMethodData
     * @test
     */
    public function testIfMethodIs($process, $requestPath, $requestMethod, $result, $jumbOrNext)
    {
        // Mocking request
        $requestMock = $this->createMock(RequestInterface::class);
        // get method
        $requestMock->expects($this->once())
            ->method("getMethod")
            ->willReturn($requestMethod);
        // get url and path
        $requestMock->expects($this->once())
            ->method("getUri")
            ->willReturn($this->uriFactory->createUri('http://test.com' . $requestPath));

        // Mocking unit tracker
        $unitTrackerMock = $this->createMock(UnitTrackerInterface::class);
        $unitTrackerMock->expects($this->once())
            ->method($jumbOrNext)
            ->willReturn($result);

        $actual = $process($requestMock, $unitTrackerMock);
        $this->assertEquals($result, $actual, "Result value is not match with unit return value");
    }
}

