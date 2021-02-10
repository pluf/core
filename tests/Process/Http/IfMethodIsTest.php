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
use Pluf\Scion\UnitTrackerInterface;
use Psr\Http\Message\RequestInterface;

use Pluf\Core\Process\Http\IfMethodIsDelete;
use Pluf\Core\Process\Http\IfMethodIsGet;
use Pluf\Core\Process\Http\IfMethodIsPost;
use Pluf\Core\Process\Http\IfMethodIsPut;
use Pluf\Core\Process\Http\IfMethodIs;

/**
 * IfMethodIsDelete test case.
 */
class IfMethodIsTest extends TestCase
{

    public function getMethodTestData(): array
    {
        return [
            // ---------------------------IfMethodIsPost----------------------------
            [
                "GET",
                "jump",
                rand(),
                new IfMethodIsPost()
            ],
            [
                "POST",
                "next",
                rand(),
                new IfMethodIsPost()
            ],
            [
                "PUT",
                "jump",
                rand(),
                new IfMethodIsPost()
            ],
            [
                "HEAD",
                "jump",
                rand(),
                new IfMethodIsPost()
            ],
            [
                "DELETE",
                "jump",
                rand(),
                new IfMethodIsPost()
            ],
            // ---------------------------IfMethodIsGet----------------------------
            [
                "GET",
                "next",
                rand(),
                new IfMethodIsGet()
            ],
            [
                "POST",
                "jump",
                rand(),
                new IfMethodIsGet()
            ],
            [
                "PUT",
                "jump",
                rand(),
                new IfMethodIsGet()
            ],
            [
                "HEAD",
                "jump",
                rand(),
                new IfMethodIsGet()
            ],
            [
                "DELETE",
                "jump",
                rand(),
                new IfMethodIsGet()
            ],
            // ---------------------------IfMethodIsPut----------------------------
            [
                "GET",
                "jump",
                rand(),
                new IfMethodIsPut()
            ],
            [
                "POST",
                "jump",
                rand(),
                new IfMethodIsPut()
            ],
            [
                "PUT",
                "next",
                rand(),
                new IfMethodIsPut()
            ],
            [
                "HEAD",
                "jump",
                rand(),
                new IfMethodIsPut()
            ],
            [
                "DELETE",
                "jump",
                rand(),
                new IfMethodIsPut()
            ],
            // ---------------------------IfMethodIsDelet----------------------------
            [
                "GET",
                "jump",
                rand(),
                new IfMethodIsDelete()
            ],
            [
                "POST",
                "jump",
                rand(),
                new IfMethodIsDelete()
            ],
            [
                "PUT",
                "jump",
                rand(),
                new IfMethodIsDelete()
            ],
            [
                "HEAD",
                "jump",
                rand(),
                new IfMethodIsDelete()
            ],
            [
                "DELETE",
                "next",
                rand(),
                new IfMethodIsDelete()
            ],
            // ---------------------------IfMethodIs----------------------------
            [
                "GET",
                "next",
                rand(),
                new IfMethodIs("GET")
            ],
            [
                "POST",
                "next",
                rand(),
                new IfMethodIs("POST")
            ],
            [
                "DELETE",
                "next",
                rand(),
                new IfMethodIs("DELETE")
            ],
            [
                "PUT",
                "next",
                rand(),
                new IfMethodIs("PUT")
            ]
        ];
    }

    /**
     *
     * @dataProvider getMethodTestData
     * @test
     */
    public function testIfMethodIs($requestMethod, $jumbOrNext, $result, $process)
    {
        // Mocking request
        $requestMock = $this->createMock(RequestInterface::class);
        $requestMock->expects($this->once())
            ->method("getMethod")
            ->willReturn($requestMethod);

        // Mocking unit tracker
        $unitTrackerMock = $this->createMock(UnitTrackerInterface::class);
        $unitTrackerMock->expects($this->once())
            ->method($jumbOrNext)
            ->willReturn($result);

        $actual = $process($requestMock, $unitTrackerMock);
        $this->assertEquals($result, $actual, "Result value is not match with unit return value");
    }
}

