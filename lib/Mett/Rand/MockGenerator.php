<?php
namespace Mett\Rand;

use Mett\Rand\GeneratorInterface;

/**
 * Class MockGenerator
 *
 * @package Mett\Rand
 */
class MockGenerator implements GeneratorInterface
{
    /**
     * Mock Method returns always $max - $min. This means default return value is 0x7FFFFFFF.
     *
     * @param int $min
     * @param int $max
     *
     * @return int
     * @throws \RuntimeException
     */
    function rand($min = 0, $max = 0x7FFFFFFF)
    {
        $diff = $max - $min;

        if ($diff < 0 || $diff > 0x7FFFFFFF) {
            throw new \RuntimeException("Bad range");
        }

        return $diff;
    }
}