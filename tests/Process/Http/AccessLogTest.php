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
use Pluf\Scion\Process\Http\AccessLog;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * AccessLog test case.
 */
class AccessLogTest extends TestCase
{

    private ?AccessLog $accessLog;

    /**
     *
     * @befor
     */
    public function setUpText()
    {
        parent::setUp();
        $this->accessLog = new AccessLog(/* parameters */);
    }

    /**
     *
     * @after
     */
    public function tearDownTest()
    {
        $this->accessLog = null;
        parent::tearDown();
    }

    /**
     *
     * @test
     */
    public function testInvokeByRequst()
    {
        // Mocking request
        $requestMock = $this->createMock(RequestInterface::class);

        // Mocking response
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        // Mocking unit tracker
        $unitTrackerMock = $this->createMock(UnitTrackerInterface::class);
        $unitTrackerMock->expects($this->once())
            ->method('next')
            ->willReturn($responseMock);

        // Mocking logs
        $loggerMock = $this->createMock(LoggerInterface::class);
        $loggerMock->expects($this->once())
            ->method('info');

        $accessLog = new AccessLog();
        $accessLog($requestMock, $unitTrackerMock, $loggerMock);
    }
}

