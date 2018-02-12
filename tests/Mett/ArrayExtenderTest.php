<?php
use \Mett\ArrayExtender;
use \Mett\array_extend;

class ArrayExtenderTest extends PHPUnit_Framework_TestCase
{
    public function testStringsWithNumericKeys()
    {
        $first    = ['AA', 'AB', 'AC'];
        $second   = ['AD', 'AE'];
        $result   = ArrayExtender::extend($first, $second);
        $expected = ['AA', 'AB', 'AC', 'AD', 'AE'];

        for ($i = 0; $i < count($expected); $i++) {
            $this->assertTrue($result[$i] === $expected[$i]);
        }
    }

    public function testFunctionWrapper()
    {
        $first    = ['AA', 'AB', 'AC'];
        $second   = ['AD', 'AE'];
        $result   = \Mett\array_extend($first, $second);
        $expected = ['AA', 'AB', 'AC', 'AD', 'AE'];

        for ($i = 0; $i < count($expected); $i++) {
            $this->assertTrue($result[$i] === $expected[$i]);
        }
    }
}
