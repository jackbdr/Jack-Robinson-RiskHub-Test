<?php

require_once 'Models/ProductInterface.php';

abstract class Product implements ProductInterface
{
    public function __construct(
        protected string $name,
        protected float $price,
        protected int $quantity
    ) {}

    public function calculatePrice(): float
    {
        return $this->price * $this->quantity;
    }
}