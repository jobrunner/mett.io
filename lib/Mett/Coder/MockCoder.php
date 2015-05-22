<?php
namespace Mett;

use \Mett\Coder\CoderInterface;

class MockCoder implements CoderInterface
{
    public function encode($data)
    {
        return $data;
    }

    public function decode($data)
    {
        return $data;
    }
}