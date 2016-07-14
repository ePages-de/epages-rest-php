<?php

namespace ep6;

class ConfigLoaderTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @group utility
	 */
    function testInputFile()
    {
        $this->assertFalse(ConfigLoader::autoload("tests/files/thisFileDoesNot.exists"));
        $this->assertFalse(ConfigLoader::autoload("tests/files/empty.file"));
        $this->assertFalse(ConfigLoader::autoload("tests/files/noJSON.file"));
        $this->assertTrue(ConfigLoader::autoload("tests/files/configuration.json"));
    }
}