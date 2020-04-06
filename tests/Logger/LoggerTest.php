<?php
namespace Pluf\Test\Logger;

require_once 'Pluf.php';

use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{

    /**
     *
     * @test
     */
    public function callBasicFunctions()
    {
        \Pluf::start(__DIR__ . '/../conf/config.php');

        \Pluf_Log::debug('hi');
        \Pluf_Log::error('error');
        \Pluf_Log::event('event');
        \Pluf_Log::fatal('fatal');
        \Pluf_Log::info('info');
        \Pluf_Log::log('log');
        \Pluf_Log::perf('perf');
        \Pluf_Log::warn('warn');

        \Pluf_Log::flush();

        $this->assertTrue(true);
    }
}

