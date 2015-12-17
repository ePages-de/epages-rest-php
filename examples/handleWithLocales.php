<?php
require_once("library/epages-rest-php.phar");

// Connect to the shop.
$shop = new ep6\Shop("my.shop.domain", "ShopName", "AUTHTOKEN", true);

// Gets the default lcale in String.
echo $shop->getDefaultLocales();

// Gets all in the shop available locales in a array.
$lcales = $shop->getLocales();
?>