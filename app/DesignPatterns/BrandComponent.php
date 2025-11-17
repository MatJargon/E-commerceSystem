<?php

namespace App\DesignPatterns;

abstract class BrandComponent
{
    public function add(BrandComponent $component)
    {
        throw new \BadMethodCallException('cannot add to a brand');
    }

    public function remove(BrandComponent $component)
    {
        throw new \BadMethodCallException('cannot remove from a brand');
    }

    abstract public function display();
}
