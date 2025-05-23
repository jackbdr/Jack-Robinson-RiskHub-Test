<?php

require_once 'Product.php';

class DigitalProduct extends Product
{
    public function __construct(
        string $name,
        float $price,
        int $quantity,
        protected string $downloadLink
    ) {
        parent::__construct($name, $price, $quantity);
    }
}
