<?php
namespace ep6;

class DateTest extends \PHPUnit_Framework_TestCase {
	
	protected function setUp() {
		Logger::setLogLevel(LogLevel::NONE);
    }

	/**
	 * @group shopobjects
	 */
    function testCreateDateAsTimestamp() {
        // GIVEN
        $validTimestamp = 1477407378;
        
        // WHEN
        $date = new Date($validTimestamp);
        
        // THEN
		$this->assertFalse($date->error());
		$this->assertEquals($date->getTimestamp(), 1477407378);
		$this->assertEquals($date->asReadable(), "2016-10-25T14:56:18.000Z");
    }

	/**
	 * @group shopobjects
	 */
    function testCreateDateAsString() {
        // GIVEN
        $validString = "2016-10-25T14:56:18.000Z";
        
        // WHEN
        $date = new Date($validString);
        
        // THEN
		$this->assertFalse($date->error());
		$this->assertEquals($date->getTimestamp(), 1477407378);
		$this->assertEquals($date->asReadable(), "2016-10-25T14:56:18.000Z");
    }

	/**
	 * @group shopobjects
	 */
    function testRejectCreateDateNoString() {
        // GIVEN
        $invalidTimestamp = null;
        
        // WHEN
        $date = new Date($invalidTimestamp);
        
        // THEN
		$this->assertTrue($date->error());
		$this->assertEquals($date->errorNumber(), "D-1");
    }
    
}

?>