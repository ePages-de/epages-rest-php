<?php
namespace ep6;

class AddressTest extends \PHPUnit_Framework_TestCase {
	
	protected function setUp() {
		Logger::setLogLevel(LogLevel::NONE);
    }

	/**
	 * @group shopobjects
	 */
    function testRejectInvalidAddressParameter() {
        // GIVEN
        $invalidAddressParameter = $this->givenInvalidAddressParameter();
        
        // WHEN
        $address = new Address($invalidAddressParameter);
        
        // THEN
		$this->assertTrue($address->error());
		$this->assertEquals($address->errorNumber(), "A-1");
    }

	/**
	 * @group shopobjects
	 */
    function testAcceptCompleteAddressParameter() {
        // GIVEN
        $completeAddressParameter = $this->givenCompleteAddressParameter();
        
        // WHEN
        $address = new Address($completeAddressParameter);
        
        // THEN
		$this->assertFalse($address->error());
        $this->thenCompleteAddressInformationIsExisting($address);
    }

	/**
	 * @group shopobjects
	 */
    function testAcceptIncompleteAddressParameter() {
        // GIVEN
        $incompleteAddressParameter = $this->givenIncompleteAddressParameter();
        
        // WHEN
        $address = new Address($incompleteAddressParameter);
        
        // THEN
		$this->assertFalse($address->error());
        $this->assertNotNull($address->getBirthday());
        $this->assertNull($address->getCity());
    }

	/**
	 * @group shopobjects
	 */
    function testSetAddressParameters() {
        // GIVEN
        $incompleteAddressParameter = $this->givenIncompleteAddressParameter();
        
        // WHEN
        $address = new Address($incompleteAddressParameter);
        $address->setBirthday("01.02.1900");
        $address->setCity("Cologne");
        $address->setCompany("FakeCompany");
        $address->setCountry("England");
        $address->setEmailAddress("a@b.cd");
        $address->setFirstName("Max");
        $address->setLastName("Miller");
        $address->setSalutation("Mr.");
        $address->setState("NRW");
        $address->setStreet("First street 2");
        $address->setStreetDetails("c/o Mister Smith");
        $address->setTitle("Master");
        $address->setVatId("DE1234567890");
        $address->setZipCode("12345");
        
        // THEN
		$this->assertFalse($address->error());
        $this->thenCompleteAddressInformationIsExisting($address);
    }
    
    function givenInvalidAddressParameter() {
        return "SomeString";
    }
    
    function givenCompleteAddressParameter() {
        return array(
            "birthday" => "01.02.1900",
            "city" => "Cologne",
            "company" => "FakeCompany",
            "country" => "England",
            "emailAddress" => "a@b.cd",
            "firstName" => "Max",
            "lastName" => "Miller",
            "salutation" => "Mr.",
            "state" => "NRW",
            "street" => "First street 2",
            "streetDetails" => "c/o Mister Smith",
            "title" => "Master",
            "vatId" => "DE1234567890",
            "zipCode" => "12345"
        );
    }
    
    function givenIncompleteAddressParameter() {
        return array(
            "birthday" => "01.02.1900"
        );
    }
    
    function thenCompleteAddressInformationIsExisting($address) {
        $this->assertNotNull($address->getBirthday());
        $this->assertNotNull($address->getCity());
        $this->assertNotNull($address->getCompany());
        $this->assertNotNull($address->getCountry());
        $this->assertNotNull($address->getEmailAddress());
        $this->assertNotNull($address->getFirstName());
        $this->assertNotNull($address->getLastName());
        $this->assertNotNull($address->getSalutation());
        $this->assertNotNull($address->getState());
        $this->assertNotNull($address->getStreet());
        $this->assertNotNull($address->getStreetDetails());
        $this->assertNotNull($address->getTitle());
        $this->assertNotNull($address->getVatId());
        $this->assertNotNull($address->getZipCode());
    }
    
}

?>