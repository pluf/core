<?php
namespace Pluf\Core\Process\Entity;

use Pluf\Core\Exception;
use Pluf\Orm\AssertionTrait;
use Pluf\Orm\EntityManager;
use Pluf\Orm\EntityQuery;
use Throwable;

class ReadEntity
{
    use AssertionTrait;

    private string $class;

    private string $name = "itemId";

    public function __construct(string $class, string $name = "itemId")
    {
        $this->class = $class;
        $this->name = $name;
    }

    /**
     * Store list of cusomers into the repositoer
     *
     *
     * @param EntityQuery $entityQuery
     *            to perform on entities
     * @return array of results
     */
    public function __invoke(EntityManager $entityManager, $itemId)
    {
        $result = $entityManager->find($this->class, $itemId);
        $this->assertNotEmpty($result, 'Entity type {{type}} not found with ID {{itemId}', [
            'type' => $this->class,
            'itemId' => $itemId
        ]);
        return $result;
    }

    /**
     * Creates new exception
     *
     * @param string $message
     * @param int $code
     * @param Throwable $previous
     * @param array $params
     * @param array $solutions
     * @return Throwable
     */
    protected function generateException($message = '', ?int $code = null, ?Throwable $previous = null, ?array $params = [], ?array $solutions = []): Throwable
    {
        return new Exception($message, $code, $previous, 404, $params, $solutions);
    }
}

