<?php
namespace Pluf\Test\Db\db;

use Pluf\Db\Connection;
use Pluf\Db\Expression;
use Pluf\Test\PlufTestCase;

class dbConnectionTest extends PlufTestCase
{

    public function testSQLite()
    {
        $c = Connection::connect($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);

        return (string) $c->expr("SELECT date('now')")->getOne();
    }

    public function testGenerator()
    {
        $c = new HelloWorldConnection();
        $test = 0;
        foreach ($c->expr('abrakadabra') as $row) {
            $test ++;
        }
        $this->assertEquals(10, $test);
    }
}

// @codingStandardsIgnoreStart
class HelloWorldConnection extends Connection
{

    public function execute(Expression $e)
    {
        for ($x = 0; $x < 10; $x ++) {
            yield $x => [
                'greeting' => 'Hello World'
            ];
        }
    }

    // @codingStandardsIgnoreEnd
}
