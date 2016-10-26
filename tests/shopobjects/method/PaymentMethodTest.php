<?php
namespace ep6;

class PaymentMethodTest extends \PHPUnit_Framework_TestCase {
	
	protected function setUp() {
		Logger::setLogLevel(LogLevel::NONE);
    }

	/**
	 * @group shopobjects
	 */
    function testRejectCreatePaymentMethodWithNoString() {
        // GIVEN
        $invalidParameter = "invalidParameter";
        
        // WHEN
        $paymentMethod = new PaymentMethod($invalidParameter);
        
        // THEN
		$this->assertTrue($paymentMethod->error());
		$this->assertEquals($paymentMethod->errorNumber(), "PM-1");
    }

	/**
	 * @group shopobjects
	 */
    function testCreateCompletePaymentMethod() {
        // GIVEN
        $paymentMethodParameter = $this->givenCompletePaymentMethod();
        
        // WHEN
        $paymentMethod = new PaymentMethod($paymentMethodParameter);
        
        // THEN
        echo $paymentMethod->errorNumber();
		$this->assertFalse($paymentMethod->error());
		$this->assertEquals($paymentMethod->getName(), "Paypal");
		$this->assertEquals($paymentMethod->getID(), "123456789");
    }

	/**
	 * @group shopobjects
	 */
    function testCreatePaymentMethodOnlyID() {
        // GIVEN
        $paymentMethodParameter = $this->givenPaymentMethodOnlyID();
        
        // WHEN
        $paymentMethod = new PaymentMethod($paymentMethodParameter);
        
        // THEN
		$this->assertFalse($paymentMethod->error());
		$this->assertNull($paymentMethod->getName());
		$this->assertEquals($paymentMethod->getID(), "123456789");
    }

	/**
	 * @group shopobjects
	 */
    function testCreatePaymentMethodOnlyName() {
        // GIVEN
        $paymentMethodParameter = $this->givenPaymentMethodOnlyName();
        
        // WHEN
        $paymentMethod = new PaymentMethod($paymentMethodParameter);
        
        // THEN
		$this->assertFalse($paymentMethod->error());
		$this->assertEquals($paymentMethod->getName(), "Paypal");
		$this->assertNull($paymentMethod->getID());
    }
    
    function givenCompletePaymentMethod() {
        return array(
            "id" => "123456789",
            "name" => "Paypal"
        );
    }
    
    function givenPaymentMethodOnlyID() {
        return array(
            "id" => "123456789"
        );
    }
    
    function givenPaymentMethodOnlyName() {
        return array(
            "name" => "Paypal"
        );
    }
    
}

?>