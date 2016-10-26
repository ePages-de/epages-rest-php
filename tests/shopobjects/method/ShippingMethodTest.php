<?php
namespace ep6;

class ShippingMethodTest extends \PHPUnit_Framework_TestCase {
	
	protected function setUp() {
		Logger::setLogLevel(LogLevel::NONE);
    }

	/**
	 * @group shopobjects
	 */
    function testRejectCreateShippingMethodWithNoString() {
        // GIVEN
        $invalidParameter = "invalidParameter";
        
        // WHEN
        $shippingMethod = new ShippingMethod($invalidParameter);
        
        // THEN
		$this->assertTrue($shippingMethod->error());
		$this->assertEquals($shippingMethod->errorNumber(), "SM-1");
    }

	/**
	 * @group shopobjects
	 */
    function testCreateCompleteShippingMethod() {
        // GIVEN
        $shippingMethodParameter = $this->givenCompleteShippingMethod();
        
        // WHEN
        $shippingMethod = new ShippingMethod($shippingMethodParameter);
        
        // THEN
        echo $shippingMethod->errorNumber();
		$this->assertFalse($shippingMethod->error());
		$this->assertEquals($shippingMethod->getName(), "DHL");
		$this->assertEquals($shippingMethod->getID(), "123456789");
    }

	/**
	 * @group shopobjects
	 */
    function testCreateShippingMethodOnlyID() {
        // GIVEN
        $shippingMethodParameter = $this->givenShippingMethodOnlyID();
        
        // WHEN
        $shippingMethod = new PaymentMethod($shippingMethodParameter);
        
        // THEN
		$this->assertFalse($shippingMethod->error());
		$this->assertNull($shippingMethod->getName());
		$this->assertEquals($shippingMethod->getID(), "123456789");
    }

	/**
	 * @group shopobjects
	 */
    function testCreateShippingMethodOnlyName() {
        // GIVEN
        $shippingMethodParameter = $this->givenShippingMethodOnlyName();
        
        // WHEN
        $shippingMethod = new PaymentMethod($shippingMethodParameter);
        
        // THEN
		$this->assertFalse($shippingMethod->error());
		$this->assertEquals($shippingMethod->getName(), "DHL");
		$this->assertNull($shippingMethod->getID());
    }
    
    function givenCompleteShippingMethod() {
        return array(
            "id" => "123456789",
            "name" => "DHL"
        );
    }
    
    function givenShippingMethodOnlyID() {
        return array(
            "id" => "123456789"
        );
    }
    
    function givenShippingMethodOnlyName() {
        return array(
            "name" => "DHL"
        );
    }
    
}

?>