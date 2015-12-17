<?php
require_once("library/epages-rest-php.phar");

// Connect to the shop.
$shop = new ep6\Shop("my.shop.domain", "ShopName", "AUTHTOKEN", true);

// Gets the default currency in String.
echo $shop->getDefaultCurrencies();

// Gets all in the shop available currencies in a array.
$currencies = $shop->getCurrencies();
?>