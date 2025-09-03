<?php

namespace App\DesignPatterns\Decorator;

use Illuminate\Http\Request;
use App\Models\Product;

interface ProductServiceInterface
{
    public function store(Request $request): Product;
    public function update(Request $request, Product $product): Product;
    public function delete(Product $product): bool;
}
