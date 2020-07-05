<?php
namespace Pluf\HTTP;

use Pluf\ObjectMapper;

class RequestMapper extends ObjectMapper
{

    var ?Request $request;

    public function __construct(Request $request, string $objectName)
    {
        parent::__construct($objectName);
        $this->request = $request;
    }

    public function mapNext(): Object
    {}

    public function hasMore(): bool
    {}
}

