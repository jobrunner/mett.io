<?php
use \Mett\ArraySorter;

class ArraySorterTest extends PHPUnit_Framework_TestCase
{
    public function testArraysNoChangesWithArrayKeys()
    {
        $arrays = [
            0 => ['id' => 302, 'label' => 'Label 302'],
            1 => ['id' => 323, 'label' => 'Label 323'],
            2 => ['id' => 322, 'label' => 'Label 322'],
            3 => ['id' => 320, 'label' => 'Label 320'],
            4 => ['id' => 301, 'label' => 'Label 301']
        ];

        $sorter = new ArraySorter();
        $order  = [302, 323, 322, 320, 301];

        $sorter->sort($arrays, 'id', $order);

        for ($i = 0; $i <= 4; $i++) {
            $this->assertTrue($arrays[$i]['id'] === $order[$i]);
        }
    }

    public function testArraysChangesWithArrayKeys()
    {
        $arrays = [
            0 => ['id' => 302, 'label' => 'Label 302'],
            1 => ['id' => 323, 'label' => 'Label 323'],
            2 => ['id' => 322, 'label' => 'Label 322'],
            3 => ['id' => 320, 'label' => 'Label 320'],
            4 => ['id' => 301, 'label' => 'Label 301']
        ];

        $sorter = new ArraySorter();
        $order  = [323, 302, 301, 320, 322];

        $sorter->sort($arrays, 'id', $order);

        for ($i = 0; $i <= 4; $i++) {
            $this->assertTrue($arrays[$i]['id'] === $order[$i]);
        }
    }

    public function testArraysNoChangesWithProperties()
    {
        $objects = [
            0 => (object)['id' => 302, 'label' => 'Label 302'],
            1 => (object)['id' => 323, 'label' => 'Label 323'],
            2 => (object)['id' => 322, 'label' => 'Label 322'],
            3 => (object)['id' => 320, 'label' => 'Label 320'],
            4 => (object)['id' => 301, 'label' => 'Label 301']
        ];

        $sorter = new ArraySorter();
        $order  = [302, 323, 322, 320, 301];

        $sorter->sort($objects, 'id', $order);

        for ($i = 0; $i <= 4; $i++) {
            $this->assertTrue($objects[$i]->id === $order[$i]);
        }
    }

    public function testArraysChangesWithProperties()
    {
        $objects = [
            0 => (object)['id' => 302, 'label' => 'Label 302'],
            1 => (object)['id' => 323, 'label' => 'Label 323'],
            2 => (object)['id' => 322, 'label' => 'Label 322'],
            3 => (object)['id' => 320, 'label' => 'Label 320'],
            4 => (object)['id' => 301, 'label' => 'Label 301']
        ];

        $sorter = new ArraySorter();
        $order  = [323, 302, 301, 320, 322];

        $sorter->sort($objects, 'id', $order);

        for ($i = 0; $i <= 4; $i++) {
            $this->assertTrue($objects[$i]->id === $order[$i]);
        }
    }

    public function testArraysNoChangesWithPropertiesAndEmptySortingMap()
    {
        $objects = [
            0 => (object)['id' => 302, 'label' => 'Label 302'],
            1 => (object)['id' => 323, 'label' => 'Label 323'],
            2 => (object)['id' => 322, 'label' => 'Label 322'],
            3 => (object)['id' => 320, 'label' => 'Label 320'],
            4 => (object)['id' => 301, 'label' => 'Label 301']
        ];

        $sorter     = new ArraySorter();
        $order      = [302, 323, 322, 320, 301];
        $sortingMap = [];

        $sorter->sort($objects, 'id', $sortingMap);

        for ($i = 0; $i <= 4; $i++) {
            $this->assertTrue($objects[$i]->id === $order[$i]);
        }
    }

    public function testArraysNoChangesWithPropertiesAndNullSortingMap()
    {
        $objects = [
            0 => (object)['id' => 302, 'label' => 'Label 302'],
            1 => (object)['id' => 323, 'label' => 'Label 323'],
            2 => (object)['id' => 322, 'label' => 'Label 322'],
            3 => (object)['id' => 320, 'label' => 'Label 320'],
            4 => (object)['id' => 301, 'label' => 'Label 301']
        ];

        $sorter = new ArraySorter();
        $order  = [302, 323, 322, 320, 301];

        $sorter->sort($objects, 'id', []);

        for ($i = 0; $i <= 4; $i++) {
            $this->assertTrue($objects[$i]->id === $order[$i]);
        }
    }

    public function testArraysNoChangesWithPropertiesAndDefaultSortingMap()
    {
        $objects = [
            0 => (object)['id' => 302, 'label' => 'Label 302'],
            1 => (object)['id' => 323, 'label' => 'Label 323'],
            2 => (object)['id' => 322, 'label' => 'Label 322'],
            3 => (object)['id' => 320, 'label' => 'Label 320'],
            4 => (object)['id' => 301, 'label' => 'Label 301']
        ];

        $sorter = new ArraySorter();
        $order  = [302, 323, 322, 320, 301];

        $sorter->sort($objects, 'id');

        for ($i = 0; $i <= 4; $i++) {
            $this->assertTrue($objects[$i]->id === $order[$i]);
        }
    }

    public function testArraysChangesWithMixedArrayKeysAndProperties()
    {
        $mixed = [
            0 => (object)['id' => 302, 'label' => 'Label 302'],
            1 =>         ['id' => 323, 'label' => 'Label 323'],
            2 => (object)['id' => 322, 'label' => 'Label 322'],
            3 =>         ['id' => 320, 'label' => 'Label 320'],
            4 =>         ['id' => 301, 'label' => 'Label 301']
        ];

        $sorter = new ArraySorter();
        $order  = [323, 302, 301, 320, 322];

        $sorter->sort($mixed, 'id', $order);

        $this->assertTrue($mixed[0]['id'] === $order[0]);
        $this->assertTrue($mixed[1]->id   === $order[1]);
        $this->assertTrue($mixed[2]['id'] === $order[2]);
        $this->assertTrue($mixed[3]['id'] === $order[3]);
        $this->assertTrue($mixed[4]->id   === $order[4]);
    }
}
