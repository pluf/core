<?php
namespace Pluf\Core\Process;

use Pluf\Orm\ModelDescriptionRepository;
use Pluf\Orm\ObjectMapperBuilder;
use Pluf\Scion\UnitTrackerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Pluf\Orm\AssertionTrait;

class HttpBodyToEntities
{
    use AssertionTrait;

    private string $class;
    private bool $multi;
    private string $name = "entities";

    public function __construct(string $class, bool $multi = true, string $name = "entities")
    {
        $this->class = $class;
        $this->multi = $multi;
        $this->name = $name;
    }

    public function __invoke(
        ModelDescriptionRepository $modelDescriptionRepository, 
        ServerRequestInterface $request, 
        UnitTrackerInterface $unitTracker)
    {
        $this->assertEquals("POST", $request->getMethod(), "Unsupported method {{method}}", ["method" => $request->getMethod()]);

        $type = $this->getContentType($request);
        $this->assertNotEmpty($type, "Content type is not specified.");
        switch ($type) {
            case "json":
                $data = $request->getBody();
                break;
            case "array":
            default:
                $data = $request->getParsedBody();
                break;
        }

        $builder = new ObjectMapperBuilder();
        $objectMapper = $builder->addType($type)
            ->setModelDescriptionRepository($modelDescriptionRepository)
            ->supportList($this->multi)
            ->build();
        
        $res = [];
        $res[$this->name] = $objectMapper->readValue($data, $this->class, $this->multi);
        return $unitTracker->next($res);
    }

    public function getContentType(ServerRequestInterface $request): string
    {
        $contentTypes = $request->getHeader("Content-Type") ?? [];

        $parsedContentType = 'application/json';
        foreach ($contentTypes as $contentType) {
            $fragments = explode(';', $contentType);
            $parsedContentType = current($fragments);
        }
        $contentTypesWithParsedBodies = [
            'application/json',
            'application/xml',
            'application/yml'
        ];

        $type = null;
        if (in_array($parsedContentType, $contentTypesWithParsedBodies)) {
            switch ($parsedContentType) {
                case 'application/json':
                    $type = "json";
                    break;
                case 'application/xml':
                case 'application/yml':
                default:
                    $type = null;
            }
        } else {
            $type = "array";
        }

        return $type;
    }
}

