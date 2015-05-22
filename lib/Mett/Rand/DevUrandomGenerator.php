<?php
namespace Mett\Rand;

use Mett\Rand\GeneratorInterface;

/**
 * Class DevUrandomGenerator
 *
 * @see https://codeascraft.com/2012/07/19/better-random-numbers-in-php-using-devurandom/
 *
 * @package Mett\Rand
 */
class DevUrandomGenerator implements GeneratorInterface
{
    function rand($min = 0, $max = 0x7FFFFFFF)
    {
        $diff = $max - $min;

        if ($diff < 0 || $diff > 0x7FFFFFFF) {
            throw new \RuntimeException("Bad range");
        }

        $bytes = mcrypt_create_iv(4, MCRYPT_DEV_URANDOM);

        if ($bytes === false || strlen($bytes) != 4) {
            throw new \RuntimeException("Unable to get 4 bytes");
        }

        $ary = unpack("Nint", $bytes);
        $val = $ary['int'] & 0x7FFFFFFF;    // 32-bit safe
        $fp  = (float) $val / 2147483647.0; // convert to [0,1]

        return (int)round($fp * $diff) + $min;
    }
}