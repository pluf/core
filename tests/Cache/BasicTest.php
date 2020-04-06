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
        $this->assertNotNull(Pluf::getCache());
        $this->assertTrue(Pluf::getCache() instanceof Pluf\Cache);
    }
}

