<?php

namespace ep6;

class RESTClientTest extends \UnitTestCase {

    function testConnection()
    {
        $this->assertFalse(@RESTClient::connect());
        $this->assertFalse(@RESTClient::connect("www.google.de"));
        $this->assertFalse(@RESTClient::connect("www.google.de", "Shopname"));
        $this->assertTrue(@RESTClient::connect("www.google.de", "Shopname", "AuthToken"));
        $this->assertFalse(@RESTClient::connect("ThisIsNODomain", "Shopname", "AuthToken"));
        $this->assertTrue(@RESTClient::connect("www.google.de", "Shopname", "AuthToken", true));
    }

    function testDisconnect()
    {
        $this->assertTrue(@RESTClient::disconnect());
    }

    function testSetRequestMethod()
    {
        $this->assertFalse(@RESTClient::setRequestMethod());
        $this->assertFalse(@RESTClient::setRequestMethod("NOVALIDMETHOD"));
        $this->assertTrue(@RESTClient::setRequestMethod("GET"));
        $this->assertTrue(@RESTClient::setRequestMethod("PUT"));
        $this->assertTrue(@RESTClient::setRequestMethod("POST"));
        $this->assertTrue(@RESTClient::setRequestMethod("DELETE"));
    }

    function testSend()
    {
        @RESTClient::setRequestMethod();
        @RESTClient::connect("www.google.de", "Shopname", "AuthToken");
        $this->assertNull(@RESTClient::send("locale"));

        @RESTClient::setRequestMethod("NOTVALID");
        $this->assertNull(@RESTClient::send("locale"));

        @RESTClient::disconnect();
        @RESTClient::setRequestMethod("GET");
        $this->assertFalse(@RESTClient::send("locale"));

        @RESTClient::setRequestMethod("NOTVALID");
        $this->assertFalse(@RESTClient::send("locale"));

    }

}