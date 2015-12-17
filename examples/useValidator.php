<?php
require_once("library/epages-rest-php.phar");

// Check if the uri is a host
ep6\InputValidator::isHost("meineurl.de");

// Check if number is between 4 and 7.
$number = 6;
ep6\InputValidator::isRandedInt($number, 4, 7);
?>