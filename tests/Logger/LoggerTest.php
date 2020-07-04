<?php
namespace Pluf\Test\Logger;

require_once 'Pluf.php';

use PHPUnit\Framework\TestCase;
use Pluf\Logger;
use Pluf;

class LoggerTest extends TestCase
{

    /**
     *
     * @test
     */
    public function callBasicFunctions()
    {
        Pluf::start(__DIR__ . '/../conf/config.php');

        Logger::debug('hi');
        Logger::error('error');
        Logger::event('event');
        Logger::fatal('fatal');
        Logger::info('info');
        Logger::log('log');
        Logger::perf('perf');
        Logger::warn('warn');

        Logger::flush();

        $this->assertTrue(true);
    }
}

