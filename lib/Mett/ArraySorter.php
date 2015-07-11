<?php
namespace Mett;

/**
 * Array Sorter for using sortable data sets.
 *
 *
 * <code>
 * $labels = [
 *      0 => ['id' => 302, 'label' => 'Label 302'],
 *      1 => ['id' => 323, 'label' => 'Label 323'],
 *      2 => ['id' => 322, 'label' => 'Label 322'],
 *      3 => ['id' => 320, 'label' => 'Label 320'],
 *      2 => ['id' => 301, 'label' => 'Label 301']
 * ];
 *
 * \Mett\ArraySorter::sort($labels, 'id', [320, 302, 322, 301, 323]);
 * print_r($templates);
 * </code>
 *
 * Result is:
 *
 * <code>
 * Array
 * (
 *     [0] => Array
 *         (
 *             [id] => 320
 *             [label] => Label 320
 *         )
 *
 *     [1] => Array
 *         (
 *             [id] => 302
 *             [label] => Label 302
 *         )
 *
 *     [2] => Array
 *         (
 *             [id] => 322
 *             [label] => Label 322
 *         )
 *
 *     [3] => Array
 *         (
 *             [id] => 301
 *             [label] => Label 301
 *         )
 *
 *     [4] => Array
 *         (
 *             [id] => 323
 *             [label] => Label 323
 *         )
 *
 * )
 * </code>
 */
class ArraySorter
{
    public function sort(array &$array, $key, array $sortMap = null)
    {
        if (empty($sortMap)) {

            return;
        }

        $sortMap = array_flip($sortMap);

        usort($array, function($a, $b) use ($key, $sortMap) {

            $aElem = is_object($a) ? $a->{$key} : $a[$key];
            $bElem = is_object($b) ? $b->{$key} : $b[$key];

            $aSort = isset($sortMap[$aElem]) ? $sortMap[$aElem] : $aElem;
            $bSort = isset($sortMap[$bElem]) ? $sortMap[$bElem] : $bElem;

            if ($aSort == $bSort) {
                return 0;
            }

            return ($aSort < $bSort) ? -1 : 1;
        });
    }
}
