<?php
namespace ep6;

# include shop objects
require_once("src/Shop.class.php");

# deactive log messages
Logger::setLogLevel("NONE");

# include test suite
require_once("test/simpletest/autorun.php");
require_once("test/tests/RESTClientTest.php");
?>