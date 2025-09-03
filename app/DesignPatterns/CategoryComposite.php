<?php

namespace App\DesignPatterns;

class CategoryComposite extends CategoryComponent
{
    protected $categories = [];

    public function add(CategoryComponent $category)
    {
        $this->categories[] = $category;
    }

    public function display()
    {
        $results = [];
        foreach ($this->categories as $category) {
            $results[] = $category->display();
        }
        return $results;
    }
}
