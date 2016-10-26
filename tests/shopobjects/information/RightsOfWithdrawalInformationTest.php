<?php

namespace ep6;

class RightsOfWithdrawalInformationTest extends \PHPUnit_Framework_TestCase {

	public $shop;
	
	protected function setUp()
    {
		Logger::setLogLevel(LogLevel::NONE);
		$this->shop = new Shop("sandbox.epages.com", "EpagesDevD20150929T075829R63", "icgToyl45PKhmkz6E2PUQOriaCoE5Wzq", true);
    }

	/**
	 * @group shopobjects
	 */
    function testShopRightsOfWithdrawalInformation()
    {
		// THEN
        $rightsOfWithdrawalInformation = $this->shop->getRightsOfWithdrawalInformation();
		$this->assertEquals("Right of withdrawal", $rightsOfWithdrawalInformation->getName());
		$this->assertEquals("Right of withdrawal", $rightsOfWithdrawalInformation->getNavigationCaption());
    }
	
	function cleanUp() {
		unset($this->shop);
	}
    
}

?>