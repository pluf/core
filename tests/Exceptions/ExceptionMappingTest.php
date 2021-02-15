<?php
namespace Pluf\Tests\Exceptions;

use PHPUnit\Framework\TestCase;
use Pluf\Orm\ObjectMapperBuilder;
use Pluf\Core\Exception;

class ExceptionMappingTest extends TestCase
{

    public function getExampleExceptions()
    {
        return [
            [
                new Exception('test', 1, null, 2, [], []),
                'test',
                1,
                null,
                2,
                [],
                []
            ]
        ];
    }

    /**
     *
     * @test
     * @dataProvider getExampleExceptions
     *
     */
    public function testEncoding($ex, $messge, $code, $pre, $status, $params, $solutions)
    {
        $builder = new ObjectMapperBuilder();
        $mapper = $builder->setType('josn')->build();

        $json = $mapper->writeValueAsString($ex);
        $reverse = json_decode($json);
        $this->assertNotNull($reverse);
        
        $this->assertStringContainsString($messge, $json);
        $this->assertStringContainsString("params", $json);
        $this->assertStringContainsString("solutions", $json);
    }
}

