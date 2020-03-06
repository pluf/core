<?php
namespace PlufTest\Logger;

require_once 'Pluf.php';

use PHPUnit\Framework\TestCase;
use Pluf\LoggerHandler;
use Pluf;

class LoggerHandlerFileTest extends TestCase
{

    /**
     *
     * @test
     */
    public function createInstance()
    {
        \Pluf::start(__DIR__ . '/../conf/config.php');

        $loggerHandler = new LoggerHandler\File();
        $stack = array();
        $loggerHandler->write($stack);

        $this->assertNotNull($loggerHandler);
    }
}

