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

    protected string $class;

    protected bool $multi;

    protected string $name = "entities";

    public function __construct(string $class, bool $multi = true, string $name = "entities")
    {
        $this->class = $class;
        $this->multi = $multi;
        $this->name = $name;
    }

    public function __invoke(ModelDescriptionRepository $modelDescriptionRepository, ServerRequestInterface $request, UnitTrackerInterface $unitTracker)
    {
        $this->assertEquals("POST", $request->getMethod(), "Unsupported method {{method}}", [
            "method" => $request->getMethod()
        ]);

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

        $res = [];
        $res[$this->name] = $this->generateEntities($type, $modelDescriptionRepository, $data);
        return $unitTracker->next($res);
    }

    /**
     * Converts data based on type and model description
     *
     * @param unknown $type
     * @param unknown $modelDescriptionRepository
     * @param unknown $data
     * @return unknown
     */
    public function generateEntities($type, $modelDescriptionRepository, $data)
    {
        $builder = new ObjectMapperBuilder();
        $objectMapper = $builder->addType($type)
            ->setModelDescriptionRepository($modelDescriptionRepository)
            ->supportList($this->multi)
            ->build();

        return $objectMapper->readValue($data, $this->class, $this->multi);
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

