<?php

namespace App\DesignPatterns;

abstract class CategoryComponent
{
    public function add(CategoryComponent $component)
    {
        throw new \BadMethodCallException('cannot add to a category');
    }

    public function remove(CategoryComponent $component)
    {
        throw new \BadMethodCallException('cannot remove from a category');
    }

    abstract public function display();
}
