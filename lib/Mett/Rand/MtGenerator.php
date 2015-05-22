<?php
namespace Mett;

use Mett\Rand\GeneratorInterface;

class MtGenerator implements GeneratorInterface
{
    public function rand($min = 0, $max = 0x7FFFFFFF)
    {
        return mt_rand($min, $max);
    }
}