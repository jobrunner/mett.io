<?php
class Base62Test extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->map = [
            ['b10' =>  0, 'b62' =>  "0"],
            ['b10' =>  1, 'b62' =>  "1"],
            ['b10' =>  9, 'b62' =>  "9"],
            ['b10' => 10, 'b62' =>  "A"],
            ['b10' => 22, 'b62' =>  "M"],
            ['b10' => 35, 'b62' =>  "Z"],
            ['b10' => 36, 'b62' =>  "a"],
            ['b10' => 48, 'b62' =>  "m"],
            ['b10' => 61, 'b62' =>  "z"],
            ['b10' => 62, 'b62' => "10"],
        ];
    }

    public function testAgainstEdgesEncode()
    {
        $coder  = new Mett\Base62();

        foreach ($this->map as $edge) {
            $this->assertSame($coder->encode($edge['b10']), $edge['b62']);
        }
    }

    public function testAgainstEdgesDecode()
    {
        $coder  = new Mett\Base62();

        foreach ($this->map as $edge) {
            $this->assertSame($coder->decode($edge['b62']), $edge['b10']);
        }
    }

    public function testAgainstSingleValue()
    {
        $coder   = new Mett\Base62();

        $integer  = 4324323453534;
        $shouldbe = "1E8BwWCM";

        $base62   = $coder->encode($integer);
        $base10   = $coder->decode($shouldbe);

        $this->assertSame($shouldbe, $base62);
        $this->assertSame($integer, $base10);
    }

    public function testAgainstEdgesEncodeDecode()
    {
        $coder   = new Mett\Base62();

        for ($b10 = 0; $b10 < 1000000; $b10 += 7) {
            $b62 = $coder->encode($b10);
            $this->assertSame($coder->decode($b62), $b10);
        }
    }
}