<?php

require_once 'Models/DigitalProduct.php';
require_once 'Models/PhysicalProduct.php';
require_once 'Models/SubscriptionProduct.php';
require_once 'Order.php';

$digital      = new DigitalProduct('Stock Photo', 20.00, 2, 'https://download.com/stock-photo');
$physical     = new PhysicalProduct('Casio Watch', 50.00, 2, 0.5);
$subscription = new SubscriptionProduct('Medium', 9.99, 1, 12);

$order = new Order();
$order->addProduct($digital);
$order->addProduct($physical);
$order->addProduct($subscription);

echo "Total: $" . $order->calculateTotal();
