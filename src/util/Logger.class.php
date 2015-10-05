<?php
namespace ep6;
/**
 * This is a static object to log messages while executing.
 *
 * Use it with:
 *   $Logger::notify("Notify this message");
 *
 * You can set the log level with:
 *   $Logger::setLogLevel("ERROR");
 *
 * You can set the output source with:
 *   $Logger::setOutput("SCREEN");
 */
class Logger {

	/**
	 * The log level describes which error should be logged.
	 */
	private static $LOGLEVEL = "NOTIFICATION";

	/**
	 * The output value is set to configure where logging message is made.
	 */
	private static $OUT = "SCREEN";

	/**
	 * This function prints notifications.
	 *
	 * @param String message The message to print.
	 */
	public static function notify($message) {
		
		if (InputValidator::isEmpty($message)) {
			return;
		}
		if (self::$LOGLEVEL == "ERROR" || self::$LOGLEVEL == "WARNING") {
			return;
		}
		self::printMessage($message, "NOTIFICATION");
	}

	/**
	 * This function prints warnings.
	 *
	 * @param String message The message to print.
	 */
	public static function warning($message) {
		
		if (InputValidator::isEmpty($message)) {
			return;
		}
		if (self::$LOGLEVEL == "ERROR") {
			return;
		}
		self::printMessage($message, "WARNING");
		self::printStacktrace();
	}

	/**
	 * This function prints errors.
	 *
	 * @param String message The message to print.
	 */
	public static function error($message) {
		
		if (InputValidator::isEmpty($message)) {
			return;
		}
		self::printMessage($message, "ERROR");
		self::printStacktrace();
	}

	/**
	 * This function definitly prints the message.
	 *
	 * @param String message The message to print.
	 */
	public static function force($message) {
		
		if (InputValidator::isEmpty($message)) {
			return;
		}
		self::printMessage($message);
	}

	/**
	 * This function finally prints the message.
	 *
	 * @param String message The message to print.
	 * @param String level The message level.
	 */
	private static function printMessage($message, $level = "FORCE") {
		
		if (InputValidator::isEmpty($message) || self::$LOGLEVEL == "NONE") {
			return;
		}

		switch (self::$OUT) {
			case "SCREEN":
				echo "******************** " . $level . " ********************<br/>\n";
				if ($level == "ERROR") echo "<strong>AN ERROR OCCURED:</strong><br/>\n";
				if ($level == "WARNING") echo "<strong><u>WARNING:</u></strong> ";
				if (is_array($message)) {
					echo "<pre>\n";
					var_dump($message);
					echo "</pre><br/>\n";
				}
				else {
					echo $message . "<br/>\n";

				}
				break;
		}
	}

	/**
	 * This function prints the stacktrace.
	 */
	private static function printStacktrace() {
		$stack = debug_backtrace();

		foreach ($stack as $stackentry) {
			echo "Function <strong>" . $stackentry['function'] . "</strong> ";
			echo "(" . join(", ", $stackentry['args']) . ") ";
			echo "called at <strong>" . $stackentry["file"] . "</strong> line " . $stackentry["line"] . "</br>";
		}
	}

	/**
	 * This function sets the log level. The following elements are possible:
	 * <ul>
	 *   <li>NOTIFICATION</li>
	 *   <li>WARNING</li>
	 *   <li>ERROR</li>
	 *   <li>NONE</li>
	 * </ul>
	 *
	 * @param String $level	The log level to set.
	 */
	public static function setLogLevel($level) {
		if (!InputValidator::isLogLevel($level)) {
			return;
		}
		self::$LOGLEVEL = $level;
	}

	/**
	 * This function sets the output ressource. The following elements are possible.
	 * <ul>
	 *   <li>SCREEN</li>
	 * </ul>
	 *
	 * @param String output The resource to output.
	 */
	public static function setOutput($out) {
		if (!InputValidator::isOutputRessource($out)) {
			return;
		}
		self::$OUT = $out;
	}
}

?>