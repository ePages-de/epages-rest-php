<?php
require_once("library/epages-rest-php.phar");

// Set the logger to the wished log level.
// Default is: NOTIFICATION, so all messages will be print.
ep6\Logger::setLogLevel("ERROR");

// Print an error message.
ep6\Logger::notify("Notify this message");

// To print an error message without being restricted by the log level you can use following code.
ep6\Logger::force("This message will be printed every time!");
?>