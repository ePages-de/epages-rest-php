<?php
namespace EpSDK\Configuration;

use EpSDK\Exception\JSONDecodingException;
use EpSDK\Utility\Logger\Logger;

/**
 * Class Configuration
 *
 * @package EpSDK\Configuration
 * @author  David Pauli <contact@dbawdy.de>
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 * @since   0.4.0
 */
class Configuration
{
    /** @var array*/
    private static $configuration = [];

    /**
     * Adds configuration from a specific file.
     *
     * Configurations from the same module will be overridden.
     *
     * @param   string  $pathToFile
     * @return  bool
     * @throws  JSONDecodingException   Thrown if the file cannot be found or is no valid JSON.
     * @since   0.4.0
     */
    public static function addConfigurationFromFile(string $pathToFile): bool
    {
        $configurationArray = \json_decode(\file_get_contents($pathToFile), true);
        if (null === $configurationArray) {
            Logger::error('Cannot load configuration file.');
            throw new JSONDecodingException('Cannot load configuration from configuration file ' . $pathToFile);
        }
        /** @var array $configurationArray */
        foreach ($configurationArray as $module => $configuration) {
            self::extendConfiguration($configuration, $module);
        }
        return true;
    }

    /**
     * Extend a special module configuration without override everything else.
     *
     * @param   array   $configuration
     * @param   string  $module
     * @return  bool
     * @since   0.4.0
     */
    public static function extendConfiguration(array $configuration, string $module): bool
    {
        if (isset(self::$configuration[$module])) {
            self::$configuration[$module] = \array_merge(self::$configuration[$module], $configuration);
        } else {
            self::setConfiguration($configuration, $module);
        }
        return true;
    }

    /**
     * Get the complete configuration or a special one.
     *
     * @param   string  $module
     * @return  array
     * @since   0.4.0
     */
    public static function getConfiguration(string $module = null): array
    {
        if (null === $module) {
            return self::$configuration;
        }
        return self::$configuration[$module] ?? [];
    }

    /**
     * Try to save the complete configuration, or a specific module, to a file.
     *
     * @param   string  $fileName
     * @param   string  $module
     * @return  bool
     * @since   0.4.0
     */
    public static function saveToFile(string $fileName, string $module = null): bool
    {
        $writeStatus = false;
        if (null === $module) {
            $writeStatus = \file_put_contents($fileName, \json_encode(self::$configuration));
        } elseif (isset(self::$configuration[$module])) {
            $writeStatus = \file_put_contents($fileName, \json_encode(self::$configuration[$module]));
        }
        return \is_int($writeStatus);
    }

    /**
     * Set the complete configuration or a specific module.
     *
     * @param   array   $configuration
     * @param   string  $module
     * @return  bool
     * @since   0.4.0
     */
    public static function setConfiguration(array $configuration, string $module = null): bool
    {
        if (null === $module) {
            self::$configuration = $configuration;
        } else {
            self::$configuration[$module] = $configuration;
        }
        return true;
    }

    /**
     * Reset the configuration.
     *
     * @return  bool
     * @since   0.4.0
     */
    public static function resetConfiguration(string $module = null): bool
    {
        if (null !== $module) {
            unset(self::$configuration[$module]);
        } else {
            self::$configuration = [];
        }
        return true;
    }
}
