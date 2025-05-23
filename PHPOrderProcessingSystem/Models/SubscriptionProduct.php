<?php

require_once 'Product.php';

class SubscriptionProduct extends Product
{
    public function __construct(
        string $name,
        float $price,
        int $quantity,
        protected int $subscriptionDuration
    ) {
        parent::__construct($name, $price, $quantity);
    }
}
