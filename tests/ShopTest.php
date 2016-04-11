<?php

namespace ep6;

class ShopTest extends \PHPUnit_Framework_TestCase {

	public $shop;

	/**
	 * @group shopobjects
	 */
    function testShopLocales()
    {
        $this->assertEquals("en_GB", $this->shop->getDefaultLocale());
        $this->assertContains("de_DE", $this->shop->getLocales());
    }

	/**
	 * @group shopobjects
	 */
    function testShopCurrencies()
    {
        $this->assertEquals("GBP", $this->shop->getDefaultCurrency());
        $this->assertContains("EUR", $this->shop->getCurrencies());
    }

	/**
	 * @group shopobjects
	 */
    function testShopContactInformation()
    {
        $contactInformation = $this->shop->getContactInformation();
		$this->assertEquals("Contact information", $contactInformation->getName());
		$this->assertEquals("Contact information", $contactInformation->getNavigationCaption());
		$this->assertEquals("David David", $contactInformation->getContactPerson());
		$this->assertEquals("000000", $contactInformation->getPhone());
		$this->assertEquals("bepeppered@gmail.com", $contactInformation->getEmail());
    }

	/**
	 * @group shopobjects
	 */
    function testShopPrivacyPolicyInformation()
    {
        $privacyPolicyInformation = $this->shop->getPrivacyPolicyInformation();
		$this->assertEquals("Privacy policy", $privacyPolicyInformation->getName());
		$this->assertEquals("Privacy policy", $privacyPolicyInformation->getNavigationCaption());
    }

	/**
	 * @group shopobjects
	 */
    function testShopRightsOfWithdrawalInformation()
    {
        $rightsOfWithdrawalInformation = $this->shop->getRightsOfWithdrawalInformation();
		$this->assertEquals("Right of withdrawal", $rightsOfWithdrawalInformation->getName());
		$this->assertEquals("Right of withdrawal", $rightsOfWithdrawalInformation->getNavigationCaption());
    }

	/**
	 * @group shopobjects
	 */
    function testShopShippingInformation()
    {
        $shippingInformation = $this->shop->getShippingInformation();
		$this->assertEquals("Shipping terms", $shippingInformation->getName());
		$this->assertEquals("Delivery", $shippingInformation->getNavigationCaption());
    }

	/**
	 * @group shopobjects
	 */
    function testShopTermsAndConditionInformation()
    {
        $termsAndConditionInformation = $this->shop->getTermsAndConditionInformation();
		$this->assertEquals("Terms and Conditions", $termsAndConditionInformation->getName());
		$this->assertEquals("Terms and Conditions", $termsAndConditionInformation->getNavigationCaption());
		$this->assertEquals("You adapt this text via the preview or data sheet view under the \"Content/Categories\" menu item of your Administration.", $termsAndConditionInformation->getDescription());
    }

	/**
	 * @beforeClass
	 */
	function setUp() {
		Logger::setLogLevel(LogLevel::NONE);
		$this->shop = new Shop("sandbox.epages.com", "EpagesDevD20150929T075829R63", "icgToyl45PKhmkz6E2PUQOriaCoE5Wzq", true);
	}

	/**
	 * @afterClass
	 */
	function cleanUp() {
		unset($this->shop);
	}

}

?>