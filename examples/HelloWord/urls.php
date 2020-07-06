<?php
use Pluf\HelloWord\Processors\HelloWordProcessor;

return [
    [
        'regex' => '#^/HelloWord$#',
        'processors' => [
            HelloWordProcessor::class
        ]
    ]
];