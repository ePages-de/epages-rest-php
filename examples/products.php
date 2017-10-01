<?php
/**
 * All ShopObjects can be used in the same way.
 *
 * This examples show you the usage with Product Object.
 */

use EpSDK\ShopObject\Product;

/**
 * Retrieve with known ID.
 *
 * If you know the Product ID you can directly retrieve the product information.
 */
$product = new Product('55C45C9C-DC4D-2C24-6437-0A0C05E6F487');
echo $product->getName();

/**
 * Create new.
 *
 * New objects can be created. They will be 'commited' to the Shop with calling save().
 */
$product = new Product();
$product->setName('Some new fancy Name')
    ->setProductNumber(uniqid('', true))
    ->setMinStocklevel(10)
    ->save();

/**
 * Delete object.
 *
 * You can also delete shopObjects on server side. The client can use the Objects later, so save() etc is blocked.
 */
$product = (new Product())
    ->setName('ProductToDelete')
    ->setProductNumber(uniqid('', true));
$product->save();
$product->delete();

/**
 * Update a object partly / completely.
 *
 * You can update attributes of the shop objects with saving. With parameter true / false (default) you can control if
 * you want to completely override the Product.
 */
$product = new Product('55C45C9C-DC4D-2C24-6437-0A0C05E6F487');
$product->setMinStocklevel(10);
$product->save();   // Only write changed attributes to shop.

$product->save(true);   // Override all attributes.
