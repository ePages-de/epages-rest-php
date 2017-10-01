<?php
/**
 * You can handle different localizations or currencies with every ShopObject in a different way.
 *
 * The examples are made with Product, bur also works with other ShopObjects.
 */

use EpSDK\Constants;
use EpSDK\ShopObject\Product;

$product = new Product();
$product->addObjectParameter(Constants::OBJECT_PARAMETER_LOCALIZATION, 'de_DE');
$product->addObjectParameter(Constants::OBJECT_PARAMETER_CURRENCY, 'USD');
