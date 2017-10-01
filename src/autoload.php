<?php
declare(strict_types=1);

use EpSDK\Configuration\Configuration;
use EpSDK\Connector\ProductConnector;
use EpSDK\ShopObject\Product;

// The auto loader
spl_autoload_register(function ($class) {
    require __DIR__ . '/' . str_replace('\\', '/', $class). '.php';
});

Configuration::setConfiguration(
    [
        Product::class  =>  new ProductConnector()
    ],
    'Connector'
);
