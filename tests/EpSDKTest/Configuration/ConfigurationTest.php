<?php
declare(strict_types=1);

namespace EpSDKTest\Configuration;

use EpSDK\Configuration\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigurationTest
 *
 * @package EpSDK\Configuration
 */
class ConfigurationTest extends TestCase
{
    public function setUp()
    {
        Configuration::reset();
    }

    public function testSetConfigurationFromFile()
    {
        // GIVEN / WHEN
        Configuration::addFromFile(__DIR__ . '/testConfig.json');

        // THEN
        self::assertEquals('value', Configuration::get('SomeModule')['var']);
        self::assertEmpty(Configuration::get('NotExist'));
    }

    public function testSetEmptyConfiguration()
    {
        // GIVEN / WHEN
        Configuration::addFromFile(__DIR__ . '/emptyConfig.json');

        // THEN
        self::assertEmpty(Configuration::get('SomeModule'));
        self::assertEmpty(Configuration::get('NotExist'));
    }

    public function testExtendConfiguration()
    {
        // GIVEN
        Configuration::set(['var' => 'value'], 'SomeModule');

        // WHEN
        Configuration::extend(['var2' => 'value2'], 'SomeModule');

        // THEN
        self::assertEquals('value', Configuration::get('SomeModule')['var']);
        self::assertEquals('value2', Configuration::get('SomeModule')['var2']);
        self::assertEmpty(Configuration::get('NotExist'));
    }

    public function testGetConfiguration()
    {
        // GIVEN / WHEN
        Configuration::extend(['var' => 'value'], 'SomeModule');

        // THEN
        self::assertArrayHasKey('SomeModule', Configuration::get());
        self::assertArrayHasKey('var', Configuration::get('SomeModule'));
    }

    public function testSetConfiguration()
    {
        // GIVEN
        Configuration::set(['var' => 'value'], 'SomeModule');

        // WHEN
        Configuration::set(['var2' => 'value2'], 'SomeModule');

        // THEN
        self::assertArrayNotHasKey('var', Configuration::get('SomeModule'));
        self::assertEquals('value2', Configuration::get('SomeModule')['var2']);
        self::assertEmpty(Configuration::get('NotExist'));
    }

    public function testResetModuleConfiguration()
    {
        // GIVEN
        Configuration::set(['var' => 'value'], 'SomeModule');
        Configuration::set(['var' => 'value'], 'SomeModule2');

        // WHEN
        Configuration::reset('SomeModule');

        // THEN
        self::assertArrayNotHasKey('SomeModule', Configuration::get());
        self::assertArrayHasKey('SomeModule2', Configuration::get());
    }

    public function testResetConfiguration()
    {
        // GIVEN
        Configuration::set(['var' => 'value'], 'SomeModule');
        Configuration::set(['var' => 'value'], 'SomeModule2');

        // WHEN
        Configuration::reset();

        // THEN
        self::assertArrayNotHasKey('SomeModule', Configuration::get());
        self::assertArrayNotHasKey('SomeModule2', Configuration::get());
    }
}
