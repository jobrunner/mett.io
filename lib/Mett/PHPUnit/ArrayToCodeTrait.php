<?php

namespace Mett\PHPunit;

trait ArrayToCodeTrait
{
    /**
     * Builds a source code representation of an array.
     *
     * @param   array   $array      The array to build in source code
     * @param   string  $left       left spacing (blanks)
     * @param   string  $varname    Variable name
     *
     * @return  string              php code
     */
    public static function arrayToCode($array, $left = '        ', $varname = 'expected')
    {
        return "{$left}\${$varname} = [\n"
               . self::_array_to_code($array, 0, self::_maxkey($array), $left)
               . "{$left}];\n";
    }

    /* none public methods */

    /**
     * Returns the maximum length of all array keys
     * per level.
     *
     * @param   array   $array      array to investigate
     * @param   int     $level      start level
     *
     * @return  array
     */
    protected static function _maxkey($array, $level = 0, $max = array())
    {
        foreach ($array as $key => $val) {
            $max[$level] = max(strlen($key) + (is_int($key) ? 0 : 2), @$max[$level]);
            if (is_array($val)) {
                $max = self::_maxkey($val, $level + 1, $max);
            }
        }

        return $max;
    }

    /**
     * Builds a source code representation of an array.
     *
     * @param   array   $array      The array to build in source code
     * @param   int     $level      level of the array.
     * @param   array   $max        array with max width information from ::_maxkey
     * @param   string  $left       left spacing (blanks)
     *
     * @return  string              php source code array
     */
    protected static function _array_to_code($array, $level = 0, $max = null, $left = '        ')
    {
        $out = '';
        foreach ($array as $key => $val) {
            $pad = $left . str_pad('', ($level + 1) * 4, ' ');
            $key = is_int($key) ? $key : "'{$key}'";
            $key = $pad . $key . str_pad('', $max[$level] - strlen($key), ' ');

            if (is_array($val)) {
                $out = $out . $key . " => [\n";
                $out = $out . self::_array_to_code($val, $level + 1, $max, $left);
                $out = $out . $pad ."],\n";
            } else {
                if ($val === null) {
                    $out = sprintf("%s%s => null,\n", $out, $key);
                } elseif ($val === false) {
                    $out = sprintf("%s%s => false,\n", $out, $key);
                } elseif ($val === true) {
                    $out = sprintf("%s%s => true,\n", $out, $key);
                } elseif (is_int($val)) {
                    $out = sprintf("%s%s => %d,\n", $out, $key, $val);
                } else {
                    $out = sprintf("%s%s => '%s',\n", $out, $key, addslashes($val));
                }
            }
        }

        return $out;
    }
}