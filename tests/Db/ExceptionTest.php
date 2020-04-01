<?php
namespace Pluf\Db\tests;

use Pluf\Db\Expression;
use PHPUnit\Framework\TestCase;
use Pluf\Db\Exception;

class ExceptionTest extends TestCase
{

    /**
     * Test constructor.
     *
     * @expectedException Pluf\Exception
     */
    public function testException1()
    {
        throw new Exception();
    }

    /**
     *
     * @expectedException \Pluf\Db\Exception
     */
    public function testException2()
    {
        $e = new Expression('hello, [world]');
        $e->render();
    }

//     public function testException3()
//     {
//         try {
//             $e = new Expression('hello, [world]');
//             $e->render();
//         } catch (Exception $e) {
//             $this->assertEquals('Expression could not render tag', $e->getMessage());
//             $this->assertEquals('world', $e->getParams()['tag']);
//         }
//     }
}
