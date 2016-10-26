<?php

namespace ep6;

class ShippingInformationTest extends \PHPUnit_Framework_TestCase {

	public $shop;
	
	protected function setUp()
    {
		Logger::setLogLevel(LogLevel::NONE);
		$this->shop = new Shop("sandbox.epages.com", "EpagesDevD20150929T075829R63", "icgToyl45PKhmkz6E2PUQOriaCoE5Wzq", true);
    }

	/**
	 * @group shopobjects
	 */
    function testShopShippingInformation()
    {	
		// THEN
        $shippingInformation = $this->shop->getShippingInformation();
		$this->assertEquals("Shipping terms", $shippingInformation->getName());
		$this->assertEquals("Delivery", $shippingInformation->getNavigationCaption());
    }
	
	function cleanUp() {
		unset($this->shop);
	}
    
}

?>