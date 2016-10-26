<?php

namespace ep6;

class TermsAndConditionInformationTest extends \PHPUnit_Framework_TestCase {

	public $shop;
	
	protected function setUp()
    {
		Logger::setLogLevel(LogLevel::NONE);
		$this->shop = new Shop("sandbox.epages.com", "EpagesDevD20150929T075829R63", "icgToyl45PKhmkz6E2PUQOriaCoE5Wzq", true);
    }

	/**
	 * @group shopobjects
	 */
    function testShopTermsAndConditionInformation()
    {
		// THEN
        $termsAndConditionInformation = $this->shop->getTermsAndConditionInformation();
		$this->assertEquals("Terms and Conditions", $termsAndConditionInformation->getName());
		$this->assertEquals("Terms and Conditions", $termsAndConditionInformation->getNavigationCaption());
		$this->assertEquals("You adapt this text via the preview or data sheet view under the \"Content/Categories\" menu item of your Administration.", $termsAndConditionInformation->getDescription());
    }
	
	function cleanUp() {
		unset($this->shop);
	}
    
}

?>