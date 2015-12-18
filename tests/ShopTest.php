<?php

namespace ep6;

class ShopTest extends \PHPUnit_Framework_TestCase {

	public $shop;

	/**
	 * @group shopobjects
	 */
    function testShopLocales()
    {
        $this->assertEquals("en_GB", $this->shop->getDefaultLocales());
        $this->assertContains("de_DE", $this->shop->getLocales());
    }

	/**
	 * @group shopobjects
	 */
    function testShopCurrencies()
    {
        $this->assertEquals("GBP", $this->shop->getDefaultCurrencies());
        $this->assertContains("EUR", $this->shop->getCurrencies());
    }

	/**
	 * @group shopobjects
	 */
    function testShopContactInformation()
    {
        $contactInformation = $this->shop->getContactInformation();
		$this->assertEquals("Contact information", $contactInformation->getDefaultName());
		$this->assertEquals("Impressum", $contactInformation->getName("de_DE"));
		$this->assertEquals("Contact information", $contactInformation->getDefaultNavigationCaption());
		$this->assertEquals("Impressum", $contactInformation->getNavigationCaption("de_DE"));
		$this->assertEquals("David David", $contactInformation->getDefaultContactPerson());
		$this->assertEquals("David David", $contactInformation->getContactPerson("de_DE"));
		$this->assertEquals("000000", $contactInformation->getDefaultPhone());
		$this->assertEquals("000000", $contactInformation->getPhone("de_DE"));
		$this->assertEquals("bepeppered@gmail.com", $contactInformation->getDefaultEmail());
		$this->assertEquals("bepeppered@gmail.com", $contactInformation->getEmail("de_DE"));
    }

	/**
	 * @group shopobjects
	 */
    function testShopPrivacyPolicyInformation()
    {
        $privacyPolicyInformation = $this->shop->getPrivacyPolicyInformation();
		$this->assertEquals("Privacy policy", $privacyPolicyInformation->getDefaultName());
		$this->assertEquals("Datenschutzerklärung", $privacyPolicyInformation->getName("de_DE"));
		$this->assertEquals("Privacy policy", $privacyPolicyInformation->getDefaultNavigationCaption());
		$this->assertEquals("Datenschutz", $privacyPolicyInformation->getNavigationCaption("de_DE"));
    }

	/**
	 * @group shopobjects
	 */
    function testShopRightsOfWithdrawalInformation()
    {
        $rightsOfWithdrawalInformation = $this->shop->getRightsOfWithdrawalInformation();
		$this->assertEquals("Right of withdrawal", $rightsOfWithdrawalInformation->getDefaultName());
		$this->assertEquals("Widerrufsrecht", $rightsOfWithdrawalInformation->getName("de_DE"));
		$this->assertEquals("Right of withdrawal", $rightsOfWithdrawalInformation->getDefaultNavigationCaption());
		$this->assertEquals("Widerrufsrecht", $rightsOfWithdrawalInformation->getNavigationCaption("de_DE"));
    }

	/**
	 * @group shopobjects
	 */
    function testShopShippingInformation()
    {
        $shippingInformation = $this->shop->getShippingInformation();
		$this->assertEquals("Shipping terms", $shippingInformation->getDefaultName());
		$this->assertEquals("Versandbedingungen", $shippingInformation->getName("de_DE"));
		$this->assertEquals("Delivery", $shippingInformation->getDefaultNavigationCaption());
		$this->assertEquals("Versand", $shippingInformation->getNavigationCaption("de_DE"));
    }

	/**
	 * @group shopobjects
	 */
    function testShopTermsAndConditionInformation()
    {
        $termsAndConditionInformation = $this->shop->getTermsAndConditionInformation();
		$this->assertEquals("Terms and Conditions", $termsAndConditionInformation->getDefaultName());
		$this->assertEquals("Allgemeine Geschäftsbedingungen", $termsAndConditionInformation->getName("de_DE"));
		$this->assertEquals("Terms and Conditions", $termsAndConditionInformation->getDefaultNavigationCaption());
		$this->assertEquals("AGB", $termsAndConditionInformation->getNavigationCaption("de_DE"));
		$this->assertEquals("You adapt this text via the preview or data sheet view under the \"Content/Categories\" menu item of your Administration.", $termsAndConditionInformation->getDefaultDescription());
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