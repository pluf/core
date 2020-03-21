<?php
namespace PlufTest\Logger;

use PHPUnit\Framework\TestCase;
use Pluf\LoggerAppender;
use Pluf;

class LoggerAppenderFileTest extends TestCase
{

    /**
     *
     * @test
     */
    public function createInstance()
    {
        \Pluf::start(__DIR__ . '/../conf/config.php');

        $loggerHandler = new LoggerAppender\File();
        $message = 'hi';
        $loggerHandler->write($message);
        $this->assertNotNull($loggerHandler);
    }
}

