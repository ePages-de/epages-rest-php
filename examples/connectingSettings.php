<?php
/**
 * This is the simple way to configure shop settings.
 *
 * The static-to-use class Configuration is responsible for saving REST configuration settings. You can set him on
 * different ways.
 *
 * Missing configuration will throw following Exceptions on try to get ShopObjects:
 * - ConfigurationNotFoundException:    If the Client Configuration is completely emopty.
 * - ConfigurationIncompleteException:  If some required value is not set (host / shop).
 */

use EpSDK\Configuration\Configuration;

/**
 * Solution 1: Load a JSON file.
 *
 * This can throw a JSONDecodingException. If everything works correct this function will return the value true.
 */
Configuration::addConfigurationFromFile('/path/to/a/JSON/file');

/**
 * Solution 2.1: Define it via array.
 *
 * To connect to more different shops in one script call (e.g. having Crons) there is also the possibility to load
 * Configuration via array.
 */
Configuration::setConfiguration([
    'Client'    =>  [
        'host'      =>  'www.example.com',
        'shop'      =>  'MyCoolShop',
        'isSSL'     =>  true,
        'userAgent' =>  'someUserAgent',    // optional
        'token'     =>  'someToken'         // optional
    ]
]);

/**
 * Solution 2.2: Define array for module.
 *
 * There is also the possibility to only right a specific module with adding the Configuration with array.
 */
Configuration::setConfiguration(
    [
        'host'      =>  'www.example.com',
        'shop'      =>  'MyCoolShop',
        'isSSL'     =>  true
    ],
    'Client'
);

/**
 * Solution 3: Extend a configuration.
 *
 * If you want to change some Client configuration, like host, but not change the other Client Configurations you can
 * extend the Configuration.
 */
Configuration::extendConfiguration(
    [
        'host'      =>  'www.example.org'
    ],
    'Client'
);

/**
 * Solution 4: Disconnect
 *
 * To delete the static Configuration settings just use the reset function.
 */
Configuration::resetConfiguration();            // Reset complete Configuration
Configuration::resetConfiguration('Client');    // Only reset the Client configuration
