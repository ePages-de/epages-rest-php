<?php
require_once("library/epages-rest-php.phar");

// This new shop object will connect to my.shop.domain/ShopName with https
$shop = new ep6\Shop("my.shop.domain", "ShopName", "AUTHTOKEN", true);

// The connection exists until the shop variable will be destroyes
unset($shop);
?>