<?php
namespace Pluf\Tests\Process\Http;

use PHPUnit\Framework\TestCase;
use Pluf\Core\Process;
use Pluf\Http\ResponseFactory;
use Pluf\Http\ServerRequestFactory;
use Pluf\Http\StreamFactory;
use Pluf\Http\UriFactory;
use Pluf\Log\Logger;
use Pluf\Scion\UnitTracker;
use Pluf\Tests\Process\Http\Mock\ReturnRequestParsedBody;
use Pluf\Orm\ObjectMapperBuilder;

class ResponseBodyEncoderTest extends TestCase
{

    public ?ServerRequestFactory $requestFactory;

    /**
     *
     * @before
     */
    public function createServices()
    {
        $this->requestFactory = new ServerRequestFactory();
    }

    public function generateRawData()
    {
        return [
            [
                200,
                ReturnRequestParsedBody::class,
                "POST", // methos
                [
                    1,
                    2.3,
                    "example string"
                ]
            ],
            [
                404,
                Process\Http\ResourceNotFound::class,
                "POST", // methos
                [
                    1,
                    2.3,
                    "example string"
                ]
            ]
        ];
    }

    public function generateValidStreamJSON()
    {
        $data = $this->generateRawData();
        $streamBuilder = new StreamFactory();
        for ($i = 0; $i < count($data); $i ++) {
            $data[$i][] = "application/json";
            $data[$i][] = $streamBuilder->createStream(json_encode($data[$i][3]));
        }
        return $data;
    }

    /**
     *
     * @dataProvider generateValidStreamJSON
     * @test
     */
    public function testResponseCodeForError($statusCode, $finalProcess, $requestMethod, $sourceBody, $contentType, $stream)
    {
        $uriFactory = new UriFactory();
        $requestFactory = new ServerRequestFactory();

        $builder = new ObjectMapperBuilder();
        $objectMapper = $builder->build();

        $request = $requestFactory->createServerRequest($requestMethod, $uriFactory->createUri("http://test.com/api"));
        $request = $request->withMethod($requestMethod)
            ->withBody($stream)
            ->withAddedHeader("Content-Type", $contentType)
            ->withAddedHeader("Accept", "application/json");

        // Mocking request
        $responseFactory = new ResponseFactory();
        $response = $responseFactory->createResponse(501);

        // Mocking unit tracker
        $unitTracker = new UnitTracker([
            Process\Http\RequestBodyParser::class,
            Process\Http\ResponseBodyEncoder::class,
            $finalProcess
        ]);
        $logger = Logger::getLogger("test");

        $result = $unitTracker->doProcess([
            "request" => $request,
            "response" => $response,
            "streamFactory" => new StreamFactory(),
            "logger" => $logger,
            "objectMapperJson" => $objectMapper
        ]);
        $this->assertNotNull($result);
        $this->assertEquals($statusCode, $result->getStatusCode());
    }
}

