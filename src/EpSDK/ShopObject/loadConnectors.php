<?php
declare(strict_types=1);

use EpSDK\Configuration\Configuration;
use EpSDK\Connector\ProductConnector;
use EpSDK\ShopObject\Product;

Configuration::set(
    [
        Product::class => new ProductConnector()
    ],
    'Connector'
);
