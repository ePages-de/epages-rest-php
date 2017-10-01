<?php
/**
 * To search many shop elements (like Products) you can use the ShopList objects.
 *
 * This examples are made with Products, but are also relevant for other ShopObjects.
 */

use EpSDK\Constants;
use EpSDK\ShopObjectList\ProductList;

/**
 * The easiest way is to just search (with default settings) Products. After calling fetchObjects() you have an array
 * with Products.
 */
$productList = new ProductList();
$products = $productList->fetchObjects();

/**
 * Fetch a bigger / lesser amount of products.
 *
 * You can fetch a specific number of Products or all in the shop.
 *
 * Pay attention: Fetching ALL products will make a lots of REST call! Try to avoid this or filter (see below).
 */
$productList = new ProductList();
$products = $productList->fetchObjects(123);    // fetches 123 Products
$allProducts = $productList->fetchObjects(Constants::ALL_ELEMENTS); // fetching all

/**
 * You can filter the amount of Products by adding query parameter.
 */
$productList = new ProductList();
$productList
    ->addQueryParameter(Constants::FILTER_CURRENCY, 'EUR')
    ->addQueryParameter(Constants::FILTER_SEARCH_INVISIBLE, 'true');
$products = $productList->fetchObjects();
