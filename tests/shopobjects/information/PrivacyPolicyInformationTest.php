<?php

namespace ep6;

class PrivacyPolicyInformationTest extends \PHPUnit_Framework_TestCase {

	public $shop;
	
	protected function setUp()
    {
		Logger::setLogLevel(LogLevel::NONE);
		$this->shop = new Shop("sandbox.epages.com", "EpagesDevD20150929T075829R63", "icgToyl45PKhmkz6E2PUQOriaCoE5Wzq", true);
    }

	/**
	 * @group shopobjects
	 */
    function testShopPrivacyPolicyInformation()
    {
		// THEN
        $privacyPolicyInformation = $this->shop->getPrivacyPolicyInformation();
		$this->assertEquals("Privacy policy", $privacyPolicyInformation->getName());
		$this->assertEquals("Privacy policy", $privacyPolicyInformation->getNavigationCaption());
    }
	
	function cleanUp() {
		unset($this->shop);
	}
    
}

?>