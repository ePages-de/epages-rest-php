<?php

namespace ep6;

class InputValidatorTest extends \PHPUnit_Framework_TestCase {
	
	protected function setUp()
    {
		Logger::setLogLevel(LogLevel::NONE);
    }

	/**
	 * @group utility
	 */
    function testIsArray()
    {
        $this->assertFalse(InputValidator::isArray(42));
        $this->assertFalse(InputValidator::isArray("String"));
        $this->assertTrue(InputValidator::isArray(array()));
        $this->assertTrue(InputValidator::isArray(array(1, 2, 3)));
    }

	/**
	 * @group utility
	 */
    function testIsAuthToken()
    {
        $this->assertFalse(InputValidator::isAuthToken(null));
        $this->assertFalse(InputValidator::isAuthToken(""));
        $this->assertTrue(InputValidator::isAuthToken("SomeString"));
    }

	/**
	 * @group utility
	 */
    function testIsCurrency()
    {
        $this->assertFalse(InputValidator::isCurrency(""));
        $this->assertFalse(InputValidator::isCurrency(123));
        $this->assertFalse(InputValidator::isCurrency("notvalid"));
        $this->assertFalse(InputValidator::isCurrency("eur"));
        $this->assertFalse(InputValidator::isCurrency("NotValid"));
        $this->assertFalse(InputValidator::isCurrency("nv"));
        $this->assertTrue(InputValidator::isCurrency("EUR"));
    }

	/**
	 * @group utility
	 */
    function testIsEmpty()
    {
        $this->assertFalse(InputValidator::isEmpty("String"));
        $this->assertFalse(InputValidator::isEmpty(123));
        $this->assertTrue(InputValidator::isEmpty(""));
        $this->assertTrue(InputValidator::isEmpty(null));
    }

	/**
	 * @group utility
	 */
    function testIsEmptyArray()
    {
        $this->assertFalse(InputValidator::isEmptyArray("String"));
        $this->assertFalse(InputValidator::isEmptyArray(array(1, 2, 3)));
        $this->assertTrue(InputValidator::isEmptyArray(array()));
    }

	/**
	 * @group utility
	 */
    function testIsEmptyArrayKey()
    {
        $this->assertTrue(InputValidator::isEmptyArrayKey(array(), "Key"));
        $this->assertTrue(InputValidator::isEmptyArrayKey(array("String" => "Value"), "Key"));
        $this->assertTrue(InputValidator::isEmptyArrayKey(array("String" => 123), "Key"));
        $this->assertTrue(InputValidator::isEmptyArrayKey(array(123), "Key"));
        $this->assertTrue(InputValidator::isEmptyArrayKey(array("String"), "Key"));
        $this->assertTrue(InputValidator::isEmptyArrayKey(array("String", 123), "Key"));
        $this->assertFalse(InputValidator::isEmptyArrayKey(array("Key" => "Value"), "Key"));
        $this->assertFalse(InputValidator::isEmptyArrayKey(array("Key" => 123), "Key"));
        $this->assertTrue(InputValidator::isEmptyArrayKey(array("Key" => null), "Key"));
        $this->assertFalse(InputValidator::isEmptyArrayKey(array("Key" => ""), "Key"));
    }

	/**
	 * @group utility
	 */
    function testIsFloat()
    {
        $this->assertFalse(InputValidator::isFloat("String"));
        $this->assertFalse(InputValidator::isFloat(array()));
        $this->assertFalse(InputValidator::isFloat(array(1.2)));
        $this->assertFalse(InputValidator::isFloat(3));
        $this->assertFalse(InputValidator::isFloat(null));
        $this->assertTrue(InputValidator::isFloat(1.2));
        $this->assertTrue(InputValidator::isFloat(-1.2));
    }

	/**
	 * @group utility
	 */
    function testIsFormatterType()
    {
        $this->assertFalse(InputValidator::isFormatterType("NotAFormatterType"));
        $this->assertTrue(InputValidator::isFormatterType("IMAGE"));
    }

	/**
	 * @group utility
	 */
    function testIsHost()
    {
        $this->assertFalse(InputValidator::isHost("Not a host"));
        $this->assertFalse(InputValidator::isHost(3));
        $this->assertFalse(InputValidator::isHost(null));
        $this->assertFalse(InputValidator::isHost(1.2));
        $this->assertTrue(InputValidator::isHost("www.google.com"));
        $this->assertTrue(InputValidator::isHost("some.sub.domain-things.com"));
    }

	/**
	 * @group utility
	 */
    function testIsInt()
    {
        $this->assertFalse(InputValidator::isInt("String"));
        $this->assertFalse(InputValidator::isInt(array()));
        $this->assertFalse(InputValidator::isInt(array(12)));
        $this->assertTrue(InputValidator::isInt(3));
        $this->assertFalse(InputValidator::isInt(null));
        $this->assertFalse(InputValidator::isInt(1.2));
        $this->assertTrue(InputValidator::isInt(-3));
    }

	/**
	 * @group utility
	 */
    function testIsJSON()
    {
        $this->assertFalse(InputValidator::isJSON("Some String"));
        $this->assertTrue(InputValidator::isJSON(3));
        $this->assertFalse(InputValidator::isJSON(null));
        $this->assertTrue(InputValidator::isJSON(1.2));
        $this->assertTrue(InputValidator::isJSON("{}"));
    }

	/**
	 * @group utility
	 */
    function testIsLocale()
    {
        $this->assertFalse(InputValidator::isLocale("Some String"));
        $this->assertFalse(InputValidator::isLocale(3));
        $this->assertFalse(InputValidator::isLocale(null));
        $this->assertFalse(InputValidator::isLocale(1.2));
        $this->assertFalse(InputValidator::isLocale("a_b"));
        $this->assertFalse(InputValidator::isLocale("AB_de"));
        $this->assertFalse(InputValidator::isLocale("dedede_DEDEDE"));
        $this->assertTrue(InputValidator::isLocale("de_DE"));
        $this->assertTrue(InputValidator::isLocale("abc_DE"));
    }

	/**
	 * @group utility
	 */
    function testIsLogLevel()
    {
        $this->assertFalse(InputValidator::isLogLevel("Some String"));
        $this->assertFalse(InputValidator::isLogLevel(3));
        $this->assertFalse(InputValidator::isLogLevel(null));
        $this->assertFalse(InputValidator::isLogLevel(1.2));
        $this->assertFalse(InputValidator::isLogLevel("notification"));
        $this->assertTrue(InputValidator::isLogLevel("NOTIFICATION"));
    }

	/**
	 * @group utility
	 */
    function testIsProductDirection()
    {
        $this->assertFalse(InputValidator::isProductDirection("Some String"));
        $this->assertFalse(InputValidator::isProductDirection(3));
        $this->assertFalse(InputValidator::isProductDirection(null));
        $this->assertFalse(InputValidator::isProductDirection(1.2));
        $this->assertFalse(InputValidator::isProductDirection("ASC"));
        $this->assertTrue(InputValidator::isProductDirection("asc"));
    }

	/**
	 * @group utility
	 */
    function testIsProductId()
    {
        $this->assertFalse(InputValidator::isProductId(null));
        $this->assertFalse(InputValidator::isProductId(""));
        $this->assertTrue(InputValidator::isProductId("SomeString"));
    }

	/**
	 * @group utility
	 */
    function testIsProductSort()
    {
        $this->assertFalse(InputValidator::isProductSort("Some String"));
        $this->assertFalse(InputValidator::isProductSort(3));
        $this->assertFalse(InputValidator::isProductSort(null));
        $this->assertFalse(InputValidator::isProductSort(1.2));
        $this->assertFalse(InputValidator::isProductSort("NAME"));
        $this->assertTrue(InputValidator::isProductSort("name"));
    }

	/**
	 * @group utility
	 */
    function testIsOutputRessource()
    {
        $this->assertFalse(InputValidator::isOutputRessource("Some String"));
        $this->assertFalse(InputValidator::isOutputRessource(3));
        $this->assertFalse(InputValidator::isOutputRessource(null));
        $this->assertFalse(InputValidator::isOutputRessource(1.2));
        $this->assertFalse(InputValidator::isOutputRessource("screen"));
        $this->assertTrue(InputValidator::isOutputRessource("SCREEN"));
    }

	/**
	 * @group utility
	 */
    function testIsRangedFloat()
    {
        $this->assertFalse(InputValidator::isRangedFloat("Some String"));
        $this->assertFalse(InputValidator::isRangedFloat(3));
        $this->assertFalse(InputValidator::isRangedFloat(null));
        $this->assertTrue(InputValidator::isRangedFloat(1.2));
        $this->assertTrue(InputValidator::isRangedFloat(1.2, 0.0));
        $this->assertFalse(InputValidator::isRangedFloat(1.2, 2.0));
        $this->assertTrue(InputValidator::isRangedFloat(1.2, 0.0, 12.0));
        $this->assertFalse(InputValidator::isRangedFloat(1.2, 2.0, 12.0));
        $this->assertTrue(InputValidator::isRangedFloat(1.2, null, 12.0));
        $this->assertFalse(InputValidator::isRangedFloat(1.2, null, -1.0));
    }

	/**
	 * @group utility
	 */
    function testIsRangedInt()
    {
        $this->assertFalse(InputValidator::isRangedInt("Some String"));
        $this->assertTrue(InputValidator::isRangedInt(3));
        $this->assertFalse(InputValidator::isRangedInt(null));
        $this->assertFalse(InputValidator::isRangedInt(1.2));
        $this->assertTrue(InputValidator::isRangedInt(1, 0));
        $this->assertFalse(InputValidator::isRangedInt(1, 2));
        $this->assertTrue(InputValidator::isRangedInt(1, 0, 12));
        $this->assertFalse(InputValidator::isRangedInt(1, 2, 12));
        $this->assertTrue(InputValidator::isRangedInt(1, null, 12));
        $this->assertFalse(InputValidator::isRangedInt(1, null, -1));
    }

	/**
	 * @group utility
	 */
    function testIsRequestMethod()
    {
        $this->assertFalse(InputValidator::isRequestMethod("Some String"));
        $this->assertFalse(InputValidator::isRequestMethod(3));
        $this->assertFalse(InputValidator::isRequestMethod(null));
        $this->assertFalse(InputValidator::isRequestMethod(1.2));
        $this->assertFalse(InputValidator::isRequestMethod("get"));
        $this->assertTrue(InputValidator::isRequestMethod("GET"));
    }

	/**
	 * @group utility
	 */
    function testIsShop()
    {
        $this->assertFalse(InputValidator::isShop(null));
        $this->assertFalse(InputValidator::isShop(""));
        $this->assertTrue(InputValidator::isShop("SomeString"));
    }

	/**
	 * @group utility
	 */
    function testIsString()
    {
        $this->assertTrue(InputValidator::isString("String"));
        $this->assertFalse(InputValidator::isString(array()));
        $this->assertFalse(InputValidator::isString(array(12)));
        $this->assertFalse(InputValidator::isString(3));
        $this->assertFalse(InputValidator::isString(null));
        $this->assertFalse(InputValidator::isString(1.2));
    }

	/**
	 * @group utility
	 */
    function testIsTimestamp()
    {
        $this->assertFalse(InputValidator::isTimestamp("NoTimestamp"));
        $this->assertTrue(InputValidator::isTimestamp(1234567890));
    }

}

?>