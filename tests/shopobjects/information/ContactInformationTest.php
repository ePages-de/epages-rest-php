<?php

namespace ep6;

class ContactInformationTest extends \PHPUnit_Framework_TestCase {

	public $contactInformation;
	
	public $shop;
	
	protected function setUp()
    {
		Logger::setLogLevel(LogLevel::NONE);
		$this->shop = new Shop("sandbox.epages.com", "EpagesDevD20150929T075829R63", "icgToyl45PKhmkz6E2PUQOriaCoE5Wzq", true);
		$this->contactInformation = $this->shop->getContactInformation();
    }

	/**
	 * @group shopobjects
	 */
    function testContactInformation()
    {
        // THEN
		$this->assertNotNull($this->contactInformation->getAddress());
		$this->assertNotNull($this->contactInformation->getDescription());
		$this->assertEquals("Contact information", $this->contactInformation->getName());
		$this->assertEquals("Contact information", $this->contactInformation->getNavigationCaption());
		$this->assertEquals("David David", $this->contactInformation->getContactPerson());
		$this->assertEquals("000000", $this->contactInformation->getPhone());
		$this->assertEquals("bepeppered@gmail.com", $this->contactInformation->getEmail());
    }
	
	function cleanUp() {
		unset($this->shop);
	}
    
}

?>