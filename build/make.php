<?php
error_reporting(E_ALL);
ini_set("display_errors", "on");
require_once("../src/configuration/config.php");

$EOL_PRINT = "<br/>\n";
echo "***********************************" . $EOL_PRINT;
echo "* <strong>Creating a phar file</strong>" . $EOL_PRINT;
echo "*                                  " . $EOL_PRINT;
echo "* This script builds the .phar file" . $EOL_PRINT;
echo "* for using the framework in a     " . $EOL_PRINT;
echo "* archive.                         " . $EOL_PRINT;
echo "***********************************" . $EOL_PRINT;
echo "* <strong>BUILD_SCRIPT_VERSION</strong> " . $BUILD_SCRIPT_VERSION . $EOL_PRINT;
echo "* <strong>FRAMEWORK_VERSION</strong> " . $FRAMEWORK_VERSION . $EOL_PRINT;
echo "***********************************" . $EOL_PRINT . $EOL_PRINT;

$filename = "eP6RESTclient-" . $FRAMEWORK_VERSION . ".phar";

if (file_exists($filename)) {
	echo "<strong>Version already exists. Delete this version of increase the version.</strong>";
}
else {
	echo "<ul>";
	echo "<li><strong>Creating the .phar</strong></li>";
	$phar = new Phar($filename);
	
	echo "<li><strong>Adding all files</strong></li>";
	$phar->buildFromDirectory('../src');
	
	echo "<li><strong>Create and set default stub</strong></li>";
	$phar->setStub($phar->createDefaultStub('Shop.class.php'));
	
	echo "</ul>";
}
?>