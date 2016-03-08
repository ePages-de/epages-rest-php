# ePages REST SDK

This is the PHP REST SDK to easily connect to an ePages shop.
You can use it as a developer to develop apps for ePages.
Just sign up for the [ePages Developer Program](http://www.epages.cloud/developer/) and create a free developer test shop.

## Requirements

To use this SDK, you'll need at least **PHP 5.4**.
Download or update the latest REST SDK via the command line.
```
wget https://raw.githubusercontent.com/ePages-de/epages-rest-php/master/build/epages-rest-php.phar -O epages-rest-php.phar
```
It is also possible to download the PHP file archive manually.
Check for the latest **.phar** release in the [releases area](https://github.com/ePages-de/epages-rest-php/releases).

## Installation

To use the SDK, you simply have to include it.
For reasons of development, it is useful to print all notifications and information on the screen.
**Warning:** It is not recommended to do this in live system!
```php
require_once("libraries/epages-rest-php.phar");
ep6\Logger::setLogLevel(ep6\LogLevel::NOTIFICATION);
```

## Version information

The following information are provided with the ePages REST SDK until now.

| Feature | GET | PUT | POST | DELETE | PATCH | information |
| --- | :---: | :---: | :---: | :---: | :---: | --- |
| carts | **✘** | **✘** | **✘** | **✘** | **?** |
| categories | **✘** | **✘** | **-** | **-** | **?** |
| currencies | **✔** | **-** | **-** | **-** | **?** |
| legal | **✘** | **-** | **-** | **-** | **?** | won't do, it's not needed |
| legal/contact-information | **✔** | **✘** | **-** | **-** | **?** |
| legal/privacy-policy | **✔** | **✘** | **-** | **-** | **?** |
| legal/rights-of-withdrawal | **✔** | **✘** | **-** | **-** | **?** |
| legal/shipping-information | **✔** | **✘** | **-** | **-** | **?** |
| legal/terms-and-condition | **✔** | **✘** | **-** | **-** | **?** |
| locales | **✔** | **-** | **-** | **-** | **?** |
| products | **✔** | **-** | **-** | **✔** | **?** |
| products/<id>/custom-attributes | **✔** | **-** | **-** | **.** | **?** |
| products/<id>/stock-level | **✔** | **✔** | **-** | **-** | **?** |
| products/export | **✘** | **-** | **-** | **-** | **?** |
| sales | **✘** | **✘** | **✘** | **✘** | **?** |
| search/product-suggest | **✘** | **-** | **-** | **-** | **?** |
| shipping-methods | **✘** | **-** | **-** | **-** | **?** |

## Code examples

For more code examples, see the [examples folder](https://github.com/ePages-de/epages-rest-php/tree/master/examples).

### Example 1

Using this example, you can retrieve 100 products with a German localisation and sort it with the **name** attribute:

```php
require_once("libraries/epages-rest-client.phar");
ep6\Logger::setLogLevel(ep6\LogLevel::NOTIFICATION);

// set connection constants
$HOST		= "www.meinshop.de";
$SHOP		= "DemoShop";
$AUTHTOKEN	= "xyzxyzxyzxyzxyzxyzxyzxyz";
$ISSSL		= true;

// connect to shop
$shop = new ep6\Shop($HOST, $SHOP, $AUTHTOKEN, $ISSSL);

// use a product filter to search for products
$productFilter = new ep6\ProductFilter();
$productFilter->setLocale("de_DE");
$productFilter->setCurrency("EUR");
$productFilter->setSort("name");
$productFilter->setResultsPerPage(100);
$products = $productFilter->getProducts();

// print the products
foreach ($products as $product) {

	echo "<h2>" . htmlentities($product->getName()) . "</h2>";
	echo "<p>";
	echo "<img style=\"float:left\" src=\"" . $product->getSmallImage()->getOriginURL() . "\"/>";
	echo "<strong>ProductID:</strong> " . $product->getID() . "<br/>";
	echo "<strong>Description:</strong> " . htmlentities($product->getDescription()) . "<br/><br/>";
	echo "<strong>This product is ";
	if (!$product->isForSale()) {
		echo "NOT ";
	}
	echo "for sale and is ";
	if ($product->isSpecialOffer()) {
		echo "<u>a</u> ";
	}
	else {
		echo "not a ";
	}
	echo "special offer.</strong>";
	echo "</p><hr style=\"clear:both\"/>";
}
```

### Example 2

Using this example, you can retrieve some shop information.

```php
require_once("libraries/epages-rest-php.phar");
ep6\Logger::setLogLevel(ep6\LogLevel::NOTIFICATION);

// set connection constants
$HOST		= "www.meinshop.de";
$SHOP		= "DemoShop";
$AUTHTOKEN	= "xyzxyzxyzxyzxyzxyzxyzxyz";
$ISSSL		= true;

// connect to shop
$shop = new ep6\Shop($HOST, $SHOP, $AUTHTOKEN, $ISSSL);

// prints the default currency and localization
echo $shop->getDefaultLocales();
echo $shop->getDefaultCurrencies();

// prints the name of the contact information in default language and in german
$contactInformation = $shop->getContactInformation();
echo $contactInformation->getDefaultName();
echo $contactInformation->getName();
```

## Utilities

### Logger

The library comes with a huge Logger called ```ep6\Logger```.
To use this (instead of the ```echo``` command) write
```php
ep6\Logger::force("Print this!");
```
The force printer also can print arrays in a simple structure.

By default all notification messages are printed. To change this use:
```php
ep6\Logger::setLogLevel(ep6\LogLevel::NOTIFICATION);	// shows all messages
ep6\Logger::setLogLevel(ep6\LogLevel::WARNING);			// shows warning and error messages
ep6\Logger::setLogLevel(ep6\LogLevel::ERROR);			// shows only error messages
ep6\Logger::setLogLevel(ep6\LogLevel::NONE);			// don't log anything
```
### InputValidator

To validate data and check the value of an object there is a InputValidator class:
```php
ep6\InputValidator::isHost("www.test.de");
ep6\InputValidator::isJSON("{}");
```
You can find all InputValidator functions in the [documentation](http://dbawdy.de/epages-rest-php/doc/class-ep6.InputValidator.html)

## Function reference

The complete reference is located [here](http://dbawdy.de/epages-rest-php/doc).

## License

The code is available under the terms of the MIT License.
