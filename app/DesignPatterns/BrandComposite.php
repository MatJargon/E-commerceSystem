<?php

namespace App\DesignPatterns;

class BrandComposite extends BrandComponent
{
    private $children = [];

    public function add(BrandComponent $component)
    {
        $this->children[] = $component;
    }

    public function remove(BrandComponent $component)
    {
        $this->children = array_filter($this->children, function($child) use ($component) {
            return $child !== $component;
        });
    }

    public function display()
    {
        $output = [];
        foreach ($this->children as $child) {
            $output[] = $child->display();
        }
        return $output;
    }
}
