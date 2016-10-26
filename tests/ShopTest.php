<?php

namespace ep6;

class ShopTest extends \PHPUnit_Framework_TestCase {

	public $shop;
	
	protected function setUp()
    {
		Logger::setLogLevel(LogLevel::NONE);
    }

	/**
	 * @group shopobjects
	 */
    function testInvalidShop()
    {
		// GIVEN / WHEN
		$this->shop = new Shop("invalidDomain", "shopName");
		
		// THEN
		$this->assertTrue($this->shop->error());
		$this->assertEquals($this->shop->errorNumber(), "S-1");
    }

	/**
	 * @group shopobjects
	 */
    function testGetShopProperties()
    {
		// GIVEN / WHEN
		$this->givenShop();
		
		// THEN
		$this->assertNotNull($this->shop->getBackofficeURL());
		$this->assertNotNull($this->shop->getLogo());
		$this->assertNotNull($this->shop->getName());
		$this->assertNotNull($this->shop->getStorefrontURL());
    }

	/**
	 * @group shopobjects
	 */
    function testShopLocales()
    {
		// GIVEN / WHEN
		$this->givenShop();
		
		// THEN
        $this->assertEquals("en_GB", $this->shop->getDefaultLocale());
        $this->assertContains("de_DE", $this->shop->getLocales());
    }

	/**
	 * @group shopobjects
	 */
    function testShopCurrencies()
    {
		// GIVEN / WHEN
		$this->givenShop();
		
		// THEN
        $this->assertEquals("GBP", $this->shop->getDefaultCurrency());
        $this->assertContains("EUR", $this->shop->getCurrencies());
    }

	/**
	 * @group shopobjects
	 */
    function testUsedCurrency()
    {
		// GIVEN
		$this->givenShop();
		
		// WHEN
		$this->shop->setUsedCurrency("EUR");
		
		// THEN
        $this->assertEquals("EUR", $this->shop->getUsedCurrency());
    }

	/**
	 * @group shopobjects
	 */
    function testUsedLocale()
    {
		// GIVEN
		$this->givenShop();
		
		// WHEN
		$this->shop->setUsedLocale("en_GB");
		
		// THEN
        $this->assertEquals("en_GB", $this->shop->getUsedLocale());
    }
	
	function givenShop() {
		$this->shop = new Shop("sandbox.epages.com", "EpagesDevD20150929T075829R63", "icgToyl45PKhmkz6E2PUQOriaCoE5Wzq", true);
	}
	
	function cleanUp() {
		unset($this->shop);
	}

}

?>