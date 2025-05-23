<?php

require_once 'Product.php';

class PhysicalProduct extends Product
{
    public function __construct(
        string $name,
        float $price,
        int $quantity,
        protected float $weight
    ) {
        parent::__construct($name, $price, $quantity);
    }

    public function calculatePrice(): float
    {
        return $this->price * $this->quantity * $this->weight;
    }
}
