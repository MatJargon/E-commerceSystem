<?php

namespace App\DesignPatterns;

interface BrandComponent
{
    public function add(BrandComponent $component);
    public function remove(BrandComponent $component);
    public function display();
}
