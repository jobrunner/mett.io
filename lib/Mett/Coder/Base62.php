<?php
namespace Mett\Coder;

use \Mett\Coder\CoderInterface;

/**
 * Class Base62
 *
 * @package Mett\Coder
 */
class Base62 implements CoderInterface
{
    /**
     * Base62
     *
     * @var int     Base of number system
     */
    private static $base           = 62;

    /**
     * Alphabet of generated Ids as string vector.
     *
     * @var string
     */
    private static $alphabetVector = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    /**
     * Convert an 32 bit integer to base62.
     *
     * @param $integer  Integer to convert
     *
     * @return string   Base62 representation of $integer.
     *
     * @see http://de.wikipedia.org/wiki/Base62
     * @see http://codepad.org/Lj9qRd2n
     * @see http://www.andyhuang.net/blog/2009/08/url-shortener-and-base-62-encoder-decoder
     */
    public function encode($base10Number)
    {
        $resultQueue = [];

        do {
            $remainder    = bcmod($base10Number, self::$base);
            $base10Number = bcdiv(bcsub($base10Number, $remainder), self::$base);

            array_unshift($resultQueue, self::$alphabetVector[$remainder]);

        } while (bccomp($base10Number, "0") != 0);

        return implode('', $resultQueue);
    }

    /**
     * Decodes a base62 string to decimal representation.
     *
     * @param $base62Number
     *
     * @return string   Decimal integer as String to avoid overflows on 32 Bit Systems
     */
    public function decode($base62Number)
    {
        $result       = "0";
        $base62Number = (string)$base62Number;
        $length       = strlen($base62Number);

        for ($i = 0; $i < $length; $i++) {
            $result = bcadd($result, bcmul(strpos(self::$alphabetVector, $base62Number[$i]),
                                           bcpow(self::$base, ($length - ($i + 1)))));
        }

        return $result;
    }
}