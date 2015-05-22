<?php
namespace Mett\Coder;

interface CoderInterface
{
    public function encode($data);
    public function decode($data);
}