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
        $shouldBe = ['AA', 'AB', 'AC', 'AD', 'AE'];

        for ($i = 0; $i < count($shouldBe); $i++) {
            $this->assertTrue($result[$i] === $shouldBe[$i]);
        }
    }

    public function testFunctionWrapper()
    {
        $first    = ['AA', 'AB', 'AC'];
        $second   = ['AD', 'AE'];
        $result   = \Mett\array_extend($first, $second);
        $shouldBe = ['AA', 'AB', 'AC', 'AD', 'AE'];

        for ($i = 0; $i < count($shouldBe); $i++) {
            $this->assertTrue($result[$i] === $shouldBe[$i]);
        }
    }
}
