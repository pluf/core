<?php
namespace Pluf\Tests\Process\Http;

use PHPUnit\Framework\TestCase;
use Pluf\Core\Process\Http\FileToResponse;
use Pluf\Http\ResponseFactory;
use Pluf\Http\ServerRequestFactory;
use Pluf\Scion\UnitTrackerInterface;

class FileToResponseTest extends TestCase
{

    public $requestFactory;

    /**
     *
     * @before
     */
    public function initTest()
    {
        $this->requestFactory = new ServerRequestFactory();
        $this->responseFactory = new ResponseFactory();
    }

    /**
     *
     * @test
     */
    public function existedOriginTest()
    {
        $origin = __DIR__ . '/assets/sample-1.jpeg';

        // process tracker mock
        $processTracker = $this->createMock(UnitTrackerInterface::class);
        $processTracker->expects($this->once())
            ->method('next')
            ->willReturn($origin);

        $request = $this->requestFactory->createServerRequest('GET', '/download/file');
        $response = $this->responseFactory->createResponse(500, 'no result');
        $process = new FileToResponse();
        $res = $process($request, $response, $processTracker);
        $this->assertNotNull($res);
    }
}


