<?php

require_once 'Models/ProductInterface.php';

class Order
{
    /** @var ProductInterface[] */
    private array $products = [];

    public function addProduct(ProductInterface $product): void
    {
        $this->products[] = $product;
    }

    public function calculateTotal(): float
    {
        return array_reduce(
            $this->products,
            fn ($total, ProductInterface $product) => $total + $product->calculatePrice(),
            0.0
        );
    }
}
