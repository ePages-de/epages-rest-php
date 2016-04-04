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

$filename = "epages-rest-php-" . $FRAMEWORK_VERSION . ".phar";
$latestFilename = "epages-rest-php.phar";

if (file_exists($filename)) {
	echo "<strong>Version already exists. Delete this version of increase the version.</strong>";
}
else {
	echo "<ul>";
	echo "<li><strong>Creating the temp.phar</strong></li>";
	$phar = new Phar("temp.phar");

	echo "<li><strong>Adding all files</strong></li>";
	$phar->buildFromDirectory('../src');

	echo "<li><strong>Create and set default stub</strong></li>";
	$phar->setStub($phar->createDefaultStub('Shop.class.php'));

	echo "<li><strong>Compress to temp.gz</strong></li>";
	$phar->compress(Phar::GZ);

	echo "<li><strong>Compress to temp.bz2</strong></li>";
	$phar->compress(Phar::BZ2);

	echo "<li><strong>Copy 'temp.phar' to 'epages-rest-php-" . $FRAMEWORK_VERSION . ".phar'</strong></li>";
	copy("temp.phar", $filename);

	echo "<li><strong>Delete the 'temp.phar' file</strong></li>";
	unlink("temp.phar");

	echo "<li><strong>Copy 'temp.phar.gz' to 'epages-rest-php-" . $FRAMEWORK_VERSION . ".phar.gz'</strong></li>";
	copy("temp.phar.gz", $filename . ".gz");

	echo "<li><strong>Delete the 'temp.phar.gz' file</strong></li>";
	unlink("temp.phar.gz");

	echo "<li><strong>Copy 'temp.phar.bz2' to 'epages-rest-php-" . $FRAMEWORK_VERSION . ".phar.bz2'</strong></li>";
	copy("temp.phar.bz2", $filename . ".bz2");

	echo "<li><strong>Delete the 'temp.phar.bz2' file</strong></li>";
	unlink("temp.phar.bz2");

	echo "<li><strong>Delete the latest 'epages-rest-php.phar' file</strong></li>";
	unlink($latestFilename);

	echo "<li><strong>Copy 'epages-rest-php-" . $FRAMEWORK_VERSION . ".phar' to 'epages-rest-php.phar</strong></li>";
	copy($filename, $latestFilename);

	echo "<li><strong>Copy 'epages-rest-php-" . $FRAMEWORK_VERSION . ".phar.gz' to 'epages-rest-php.phar.gz</strong></li>";
	copy($filename . ".gz", $latestFilename . ".gz");

	echo "<li><strong>Copy 'epages-rest-php-" . $FRAMEWORK_VERSION . ".phar.bz2' to 'epages-rest-php.phar.bz2</strong></li>";
	copy($filename . ".bz2", $latestFilename . ".bz2");

	echo "</ul>";
}
?>