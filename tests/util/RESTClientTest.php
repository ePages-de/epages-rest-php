<?php

namespace ep6;

class RESTClientTest extends \PHPUnit_Framework_TestCase {
	
	protected function setUp()
    {
		Logger::setLogLevel(LogLevel::NONE);
    }

	/**
	 * @group utility
	 */
    function testConnection()
    {
		// GIVEN / WHEN / THEN
        $this->assertTrue(RESTClient::connect("www.google.de", "Shopname"));
        $this->assertTrue(RESTClient::connect("www.google.de", "Shopname", "AuthToken"));
        $this->assertFalse(RESTClient::connect("ThisIsNODomain", "Shopname", "AuthToken"));
        $this->assertTrue(RESTClient::connect("www.google.de", "Shopname", "AuthToken", true));
        $this->assertTrue(RESTClient::connect("www.google.de", "Shopname", "AuthToken", false));
		$this->assertTrue(RESTClient::disconnect());
    }
	
	/**
	 * @group utility
	 */
	function testGetContent() {
		// GIVEN
		RESTClient::connect("sandbox.epages.com", "EpagesDevD20150929T075829R63");
		
		// WHEN
		RESTClient::send("legal");
		
		// THEN
		$this->assertTrue(RESTClient::isResponseOk());
		$this->assertNotNull(RESTClient::getContent());
		$this->assertNotNull(RESTClient::getJSONContent());
	}
	
	/**
	 * @group utility
	 */
	function testGetContentWithLocalization() {
		// GIVEN
		RESTClient::connect("sandbox.epages.com", "EpagesDevD20150929T075829R63");
		
		// WHEN
		RESTClient::sendWithLocalization("legal", "de_DE");
		
		// THEN
		$this->assertTrue(RESTClient::isResponseOk());
		$this->assertNotNull(RESTClient::getContent());
		$this->assertNotNull(RESTClient::getJSONContent());
	}
	
	/**
	 * @group utility
	 */
	function testGetCookie() {
		// GIVEN
		RESTClient::connect("sandbox.epages.com", "EpagesDevD20150929T075829R63");
		
		// WHEN
		RESTClient::setCookie("testKey", "testValue");
		
		// THEN
		$this->assertNotEmpty(RESTClient::getCookies());
		$this->assertEquals(RESTClient::getCookie("testKey"), "testValue");
	}
	
	/**
	 * @group utility
	 */
	function testGetHeader() {
		// GIVEN
		RESTClient::connect("sandbox.epages.com", "EpagesDevD20150929T075829R63");
		
		// WHEN
		RESTClient::send("legal");
		
		// THEN
		$this->assertNotNull(RESTClient::getHeader("Date"));
	}

	/**
	 * @group utility
	 */
    function testSetRequestMethod()
    {
        $this->assertFalse(RESTClient::setRequestMethod("NOVALIDMETHOD"));
		$this->assertTrue(RESTClient::error());
		$this->assertEquals("RESTC-9", RESTClient::errorNumber());
        $this->assertTrue(RESTClient::setRequestMethod("GET"));
        $this->assertTrue(RESTClient::setRequestMethod("PUT"));
        $this->assertTrue(RESTClient::setRequestMethod("POST"));
        $this->assertTrue(RESTClient::setRequestMethod("DELETE"));
        $this->assertTrue(RESTClient::setRequestMethod("PATCH"));
    }

	/**
	 * @group utility
	 */
    function testSend()
    {
        RESTClient::connect("sandbox.epages.com", "EpagesDevD20150929T075829R63", "icgToyl45PKhmkz6E2PUQOriaCoE5Wzq", true);
        RESTClient::setRequestMethod("GET");
        RESTClient::send("locales", "NoArray");
		$this->assertTrue(RESTClient::error());
		$this->assertEquals("RESTC-5", RESTClient::errorNumber());

		RESTClient::disconnect();
		RESTClient::send("locales");
		$this->assertTrue(RESTClient::error());
		$this->assertEquals("RESTC-6", RESTClient::errorNumber());

		RESTClient::connect("sandbox.epages.com", "EpagesDevD20150929T075829R63", "icgToyl45PKhmkz6E2PUQOriaCoE5Wzq", true);
        RESTClient::setRequestMethod("GET");
        RESTClient::send("NoValidRessource");
		$this->assertFalse(RESTClient::isResponseOk());
		$this->assertFalse(RESTClient::isResponseFound());

        RESTClient::send();
		$this->assertTrue(RESTClient::isResponseOk());
    }

	/**
	 * @group utility
	 */
    function testSendWithLocalization()
    {
        RESTClient::connect("sandbox.epages.com", "EpagesDevD20150929T075829R63", "icgToyl45PKhmkz6E2PUQOriaCoE5Wzq", true);
        RESTClient::setRequestMethod("GET");
        RESTClient::sendWithLocalization("locale", "NoLocale");
		$this->assertTrue(RESTClient::error());
		$this->assertEquals("RESTC-3", RESTClient::errorNumber());

        RESTClient::sendWithLocalization("locales", "NoLocale", "NoArray");
		$this->assertTrue(RESTClient::error());
		$this->assertEquals("RESTC-3", RESTClient::errorNumber());

        RESTClient::sendWithLocalization("locales", "en_GB", "NoArray");
		$this->assertTrue(RESTClient::error());
		$this->assertEquals("RESTC-5", RESTClient::errorNumber());

		RESTClient::disconnect();
		RESTClient::sendWithLocalization("locales", "NoLocale");
		$this->assertTrue(RESTClient::error());
		$this->assertEquals("RESTC-3", RESTClient::errorNumber());

		RESTClient::disconnect();
		RESTClient::sendWithLocalization("locales", "en_GB");
		$this->assertTrue(RESTClient::error());
		$this->assertEquals("RESTC-6", RESTClient::errorNumber());

		RESTClient::connect("sandbox.epages.com", "EpagesDevD20150929T075829R63", "icgToyl45PKhmkz6E2PUQOriaCoE5Wzq", true);
        RESTClient::setRequestMethod("GET");
        RESTClient::sendWithLocalization("NoValidRessource", "en_GB");
		$this->assertFalse(RESTClient::isResponseOk());
		$this->assertFalse(RESTClient::isResponseFound());

        RESTClient::sendWithLocalization("NoValidRessource", "NoLocale");
		$this->assertTrue(RESTClient::error());
		$this->assertEquals("RESTC-3", RESTClient::errorNumber());

        RESTClient::sendWithLocalization("locales", "de_DE", "noArray");
		$this->assertTrue(RESTClient::error());
		$this->assertEquals("RESTC-5", RESTClient::errorNumber());

        RESTClient::sendWithLocalization("locales", "de_DE");
		$this->assertFalse(RESTClient::error());
		$this->assertNull(RESTClient::errorNumber());
    }

	/**
	 * @group utility
	 */
    function testSetRequestTime()
    {
        $this->assertFalse(RESTClient::setRequestWaitTime("NoInt"));
		$this->assertTrue(RESTClient::error());
		$this->assertEquals("RESTC-10", RESTClient::errorNumber());

        $this->assertFalse(RESTClient::setRequestWaitTime(-1));
		$this->assertTrue(RESTClient::error());
		$this->assertEquals("RESTC-10", RESTClient::errorNumber());

        $this->assertTrue(RESTClient::setRequestWaitTime(600));
		$this->assertFalse(RESTClient::error());
		$this->assertNull(RESTClient::errorNumber());
    }
}