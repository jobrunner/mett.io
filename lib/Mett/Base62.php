<?php
/**
 * Base62 coder
 */

namespace Mett;


class Base62
{
    /**
     * Base62
     */
    const BASE = 62;

    /**
     * Alphabet of generated Ids
     */
    const BASE_ALPHABET = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    /**
     * Convert an 32 bit integer to base62.
     *
     * @param $integer  Integer to convert
     *
     * @return string   Base62 representation of $integer.
     *
     * @see http://de.wikipedia.org/wiki/Base62
     */
    public function encode($integer)
    {
        $baseVector = self::BASE_ALPHABET;
        $r          = $integer  % self::BASE;
        $res        = $baseVector[$r];
        $q          = floor($integer / self::BASE);

        while ($q) {
            $r   = $q % self::BASE;
            $q   = floor($q / self::BASE);
            $res = $baseVector[$r] . $res;
        }
        return $res;
    }

    /**
     * Decodes a base62 string to integer
     *
     * @param $base62Number
     *
     * @return int
     */
    public function decode($base62Number)
    {
        $baseVector = self::BASE_ALPHABET;
        $result     = 0;

        for($i = 0; $i < strlen($base62Number); $i++) {
            $result = self::BASE * $result + strpos($baseVector, $base62Number[$i]);
        }

        return $result;
    }
}