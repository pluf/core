<?php
namespace Pluf\Tests\Process\Http;

use PHPUnit\Framework\TestCase;
use Pluf\Http\ServerRequestFactory;
use Pluf\Http\StreamFactory;
use Pluf\Http\UriFactory;
use Pluf\Scion\UnitTracker;
use Pluf\Core\Process\Http\RequestBodyParser;
use Pluf\Tests\Process\Http\Mock\ReturnRequestParsedBody;

class RequestBodyParserTest extends TestCase
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
                "POST", // methos
                (object) [
                    "id" => 1,
                    "float" => 2.3,
                    "name" => "example string",
                    "array" => [
                        1,
                        2.3,
                        "example string"
                    ],
                    "object" => (object) [
                        "id" => 1,
                        "float" => 2.3,
                        "name" => "example string"
                    ]
                ]
            ],
            [
                "POST", // methos
                [
                    1,
                    2.3,
                    "example string"
                ]
            ],
            [ // internal array
                "POST", // methos
                [
                    1,
                    2.3,
                    "example string",
                    [
                        1,
                        2.3,
                        "example string"
                    ]
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
            $data[$i][] = $streamBuilder->createStream(json_encode($data[$i][1]));
        }
        return $data;
    }

    /**
     *
     * @dataProvider generateValidStreamJSON
     * @test
     */
    public function checkParseBodyNotFail($requestMethod, $sourceBody, $contentType, $stream)
    {
        $uriFactory = new UriFactory();
        $requestFactory = new ServerRequestFactory();
        $request = $requestFactory->createServerRequest($requestMethod, $uriFactory->createUri("http://test.com/api"));
        $request = $request->withMethod($requestMethod)
            ->withBody($stream)
            ->withAddedHeader("Content-Type", $contentType);

        // Mocking unit tracker
        $unitTracker = new UnitTracker([
            RequestBodyParser::class,
            ReturnRequestParsedBody::class
        ]);

        $result = $unitTracker->doProcess([
            "request" => $request
        ]);
        $this->assertNotNull($result);
        $this->assertEquals($sourceBody, $result);
    }
}

