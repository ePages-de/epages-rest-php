<?php

namespace ep6;

class RESTClientTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @group utility
	 */
    function testConnection()
    {
        $this->assertFalse(@RESTClient::connect());
        $this->assertFalse(@RESTClient::connect("www.google.de"));
        $this->assertFalse(@RESTClient::connect("www.google.de", "Shopname"));
        $this->assertTrue(@RESTClient::connect("www.google.de", "Shopname", "AuthToken"));
        $this->assertFalse(@RESTClient::connect("ThisIsNODomain", "Shopname", "AuthToken"));
        $this->assertTrue(@RESTClient::connect("www.google.de", "Shopname", "AuthToken", true));
    }

	/**
	 * @group utility
	 */
    function testSetRequestMethod()
    {
        $this->assertFalse(@RESTClient::setRequestMethod());
        $this->assertFalse(@RESTClient::setRequestMethod("NOVALIDMETHOD"));
        $this->assertTrue(@RESTClient::setRequestMethod("GET"));
        $this->assertTrue(@RESTClient::setRequestMethod("PUT"));
        $this->assertTrue(@RESTClient::setRequestMethod("POST"));
        $this->assertTrue(@RESTClient::setRequestMethod("DELETE"));
    }

	/**
	 * @group utility
	 */
    function testSend()
    {
        @RESTClient::setRequestMethod();
        @RESTClient::connect("www.google.de", "Shopname", "AuthToken");
        $this->assertNull(@RESTClient::send("locale"));

        @RESTClient::setRequestMethod("NOTVALID");
        $this->assertNull(@RESTClient::send("locale"));

        @RESTClient::disconnect();
        @RESTClient::setRequestMethod("GET");
        $this->assertNull(@RESTClient::send("locale"));

        @RESTClient::setRequestMethod("NOTVALID");
        $this->assertNull(@RESTClient::send("locale"));

    }

}