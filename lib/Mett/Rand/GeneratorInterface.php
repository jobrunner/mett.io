<?php
namespace Mett\Rand;

interface GeneratorInterface
{
    public function rand($min = 0, $max = 0x7FFFFFFF);
}