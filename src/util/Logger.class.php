<?php
/**
 * This file represents the logger class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @since 0.0.1 Add LogLevel and LogOutput classes.
 * @since 0.1.2 Move LogLevel and LogOutput enum to own files.
 */
namespace ep6;
/**
 * This is a static object to log messages while executing.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @since 0.0.1 Use LogLevel and LogOutput
 * @since 0.1.2 Add functionality to print into files.
 * @package ep6
 * @subpackage Util
 * @example examples\logMessages.php Use the Logger to log messages.
 */
class Logger {

	/** @var LogLevel The log level describes which error should be logged. */
	private static $LOGLEVEL = LogLevel::NONE;

	/** @var LogOutput The output value is set to configure where logging message is made. */
	private static $OUT = LogOutput::SCREEN;

	/** @var String The default output file for printing log messages. */
	private static $OUTPUT_FILE;

	/**
	 * This function prints notifications.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use LogLevel
	 * @since 0.1.2 Call the printMessage function on another way.
	 * @api
	 * @param String $message The message to print.
	 */
	public static function notify($message) {

		if (InputValidator::isEmpty($message) ||
			self::$LOGLEVEL == LogLevel::ERROR ||
			self::$LOGLEVEL == LogLevel::WARNING ||
			self::$LOGLEVEL == LogLevel::NONE) {
			return;
		}
		self::printMessage($message);
	}

	/**
	 * This function prints warnings.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use LogLevel
	 * @since 0.1.2 Call the printMessage function on another way.
	 * @api
	 * @param String $message The message to print.
	 */
	public static function warning($message) {

		if (InputValidator::isEmpty($message) ||
			self::$LOGLEVEL == LogLevel::ERROR ||
			self::$LOGLEVEL == LogLevel::NONE) {
			return;
		}
		self::printMessage($message, true);
	}

	/**
	 * This function prints errors.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use LogLevel
	 * @since 0.1.2 Call the printMessage function on another way.
	 * @api
	 * @param String $message The message to print.
	 */
	public static function error($message) {

		if (InputValidator::isEmpty($message) ||
			self::$LOGLEVEL == LogLevel::NONE) {
			return;
		}
		self::printMessage($message, true);
	}

	/**
	 * This function definitly prints the message.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use LogLevel
	 * @since 0.1.2 Call the printMessage function on another way.
	 * @api
	 * @param String $message The message to print.
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
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Restructor the output message.
	 * @since 0.1.2 Restructure log message and print to file.
	 * @param String $message The message to print.
	 * @param boolean $showStacktrace = 'false' True if a stacktrace show be shown, false if not.
	 */
	private static function printMessage($message, $showStacktrace = false) {
		
		// build output
		$output = $_SERVER['REMOTE_ADDR'] . " - ";
		$output .= "[" . date("d/M/Y:H:i:s O") . "] ";
		// print message, if it is array or string
		if (is_array($message)) {
			$output .= "\n" . implode(",", $message);
		}
		else {
			$output .= "\n" . $message;
		}
		// print stacktrace if its needed
		if ($showStacktrace) {
			$output .= "\nStacktrace:\n" . self::getStacktrace();
		}

		switch (self::$OUT) {
			case LogOutput::SCREEN:
				echo "<pre>";
				echo $output;
				echo "</pre>";
				break;
			case LogOutput::FILE:
				$handle = fopen(self::$OUTPUT_FILE, "a");
				fwrite($handle, $output);
				fwrite($handle, "\n===\n\n");
				fclose($handle);
				break;
		}
	}

	/**
	 * This function returns the stacktrace.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.2
	 * @return String The Stacktrace.
	 */
	private static function getStacktrace() {
		$stack = debug_backtrace();
		$messageNumber = 0;
		$stacktrace = "";

		foreach ($stack as $stackentry) {
			// dont show the first 3 messages, because this are intern Logger functions
			if ($messageNumber < 3) {
				$messageNumber++;
				continue;
			}
			$stacktrace .= "function " . $stackentry['function'] . "(";
			$stacktrace .= implode(",", $stackentry['args']);
			$stacktrace .= ") called at " . $stackentry["file"] . " line " . $stackentry["line"];
			$stacktrace .= "\n";
		}

		return $stacktrace;
	}

	/**
	 * This function sets the log level.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use LogLevel enum.
	 * @since 0.0.3 Set php error reporting automatically in developing systems.
	 * @since 0.1.2 epages-rest-php log level will not take effect in PHP log level.
	 * @api
	 * @param LogLevel $level The log level to set.
	 */
	public static function setLogLevel($level) {
		if (!InputValidator::isLogLevel($level)) {
			return;
		}
		self::$LOGLEVEL = $level;
	}

	/**
	 * This function sets the output ressource.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use LogOutput enum.
	 * @api
	 * @param LogOutput $out The resource to output.
	 */
	public static function setOutput($out) {
		if (!InputValidator::isOutputRessource($out)) {
			return;
		}
		self::$OUT = $out;
	}

	/**
	 * This function sets the output file.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.2
	 * @api
	 * @param String $filename The filename of the new output file with path on the server.
	 */
	public static function setOutputFile($filename) {
		if (InputValidator::isEmpty($filename)) {
			return;
		}
		self::$OUTPUT_FILE = $filename;
	}

	/**
	 * Prints the Logger object as a string.
	 *
	 * This function returns the setted values of the Logger object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.1
	 * @return String The Logger as a string.
	 */
	public function __toString() {

		return "<strong>Log level:</strong> " . self::$LOGLEVEL . "<br/>" .
				"<strong>Output resource:</strong> " . self::$OUT . "<br/>";
	}
}
?>