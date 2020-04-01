<?php
namespace Pluf\Test\Cache;

require_once 'Pluf.php';

use PHPUnit\Framework\TestCase;
use Pluf;

class BasicTest extends TestCase
{

    /**
     *
     * @test
     */
    public function gettingDefaultCache()
    {
        Pluf::start([]);
        $this->assertNotNull(Pluf::$cache);
        $this->assertTrue(Pluf::$cache instanceof Pluf\Cache);
    }
}

