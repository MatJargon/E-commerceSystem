<?php

namespace App\DesignPatterns\Decorator;

use Illuminate\Http\Request;
use App\Models\Product;

abstract class ProductDecorator implements ProductServiceInterface
{
    protected ProductServiceInterface $service;

    public function __construct(ProductServiceInterface $service)
    {
        $this->service = $service;
    }

    public function store(Request $request): Product
    {
        return $this->service->store($request);
    }

    public function update(Request $request, Product $product): Product
    {
        return $this->service->update($request, $product);
    }

    public function delete(Product $product): bool
    {
        return $this->service->delete($product);
    }
}
