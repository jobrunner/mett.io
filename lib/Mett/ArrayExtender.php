<?php
namespace Mett;

function array_extend()
{
    return call_user_func_array('\Mett\ArrayExtender::extend', func_get_args());
}

class ArrayExtender
{
    /**
     * @return array
     */
    public static function extend()
    {
        $argc = func_num_args();
        $argv = func_get_args();
        $extended = [];
        if ($argc > 0) {
            for ($i = 0; $i < $argc; $i++) {
                if (is_array($argv[$i])) {
                    foreach ($argv[$i] as $key => $value) {
                        $extended[] = $value;
                    }
                }
            }
        }

        return $extended;
    }
}