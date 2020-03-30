<?php
namespace Pluf\Test;

use Pluf\Options;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{

    /**
     * Checks if the default value is readable
     *
     * @test
     * @expectedException \Pluf\Exception
     */
    public function newInstanceWithBadDefaultValue()
    {
        new Options($this);
    }

    /**
     * Checks if the default value is readable
     *
     * @test
     */
    public function getDefaultValueTest()
    {
        $options = new Options(array(
            'a' => 'a',
            'b' => 'b'
        ));
        $this->assertEquals($options->a, 'a');
        $this->assertEquals($options->b, 'b');
    }

    /**
     * Checks if you can use an option as default vlaues
     *
     * @test
     */
    public function getDefaultValueFromOptionTest()
    {
        $options = new Options();
        $options->a = 'a';
        $options->b = 'b';
        $this->assertEquals($options->a, 'a');
        $this->assertEquals($options->b, 'b');

        $options2 = new Options($options);
        $this->assertEquals($options2->a, 'a');
        $this->assertEquals($options2->b, 'b');
    }

    /**
     * Check override of default value
     *
     * @test
     */
    public function getOverridedDefaultValue()
    {
        $options = new Options(array(
            'a' => 'a',
            'b' => 'b'
        ));
        $options->a = 'c';
        $this->assertEquals($options->a, 'c');
        $this->assertEquals($options->b, 'b');
    }

    /**
     *
     * @test
     */
    public function getOptionsStartWith()
    {
        $options = new Options(array(
            'a_a' => 'a',
            'a_b' => 'b'
        ));

        $pre = $options->startsWith('a_', true);
        $this->assertEquals($pre->a, 'a');
        $this->assertEquals($pre->b, 'b');

        $pre = $options->startsWith('a_', false);
        $this->assertEquals($pre->a_a, 'a');
        $this->assertEquals($pre->a_b, 'b');
    }

    /**
     *
     * @test
     */
    public function getOptionsStartWithChain()
    {
        $options = new Options(array(
            'a_b_a' => 'a',
            'a_b_b' => 'b'
        ));

        $pre = $options->startsWith('a_', true)->startsWith('b_', true);
        $this->assertEquals($pre->a, 'a');
        $this->assertEquals($pre->b, 'b');

        $pre = $options->startsWith('a_', true)->startsWith('b_', false);
        $this->assertEquals($pre->b_a, 'a');
        $this->assertEquals($pre->b_b, 'b');

        $pre = $options->startsWith('a_', false)->startsWith('a_b_', false);
        $this->assertEquals($pre->a_b_a, 'a');
        $this->assertEquals($pre->a_b_b, 'b');
    }
}

