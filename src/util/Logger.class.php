<?php
/**
 * This file represents the logger class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @since 0.0.1 Add LogLevel and LogOutput classes.
 */
namespace ep6;
/**
 * This is a static object to log messages while executing.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @since 0.0.1 Use LogLevel and LogOutput
 * @package ep6
 * @subpackage Util
 * @example examples\logMessages.php Use the Logger to log messages.
 */
class Logger {

	/** @var LogLevel The log level describes which error should be logged. */
	private static $LOGLEVEL = LogLevel::NONE;

	/** @var LogOutput The output value is set to configure where logging message is made. */
	private static $OUT = LogOutput::SCREEN;

	/**
	 * This function prints notifications.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use LogLevel
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
		self::printMessage($message, LogLevel::NOTIFICATION);
	}

	/**
	 * This function prints warnings.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use LogLevel
	 * @api
	 * @param String $message The message to print.
	 */
	public static function warning($message) {
		
		if (InputValidator::isEmpty($message) ||
			self::$LOGLEVEL == LogLevel::ERROR ||
			self::$LOGLEVEL == LogLevel::NONE) {
			return;
		}
		self::printMessage($message, LogLevel::WARNING);
	}

	/**
	 * This function prints errors.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use LogLevel
	 * @api
	 * @param String $message The message to print.
	 */
	public static function error($message) {
		
		if (InputValidator::isEmpty($message) ||
			self::$LOGLEVEL == LogLevel::NONE) {
			return;
		}
		self::printMessage($message, LogLevel::ERROR);
	}

	/**
	 * This function definitly prints the message.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use LogLevel
	 * @api
	 * @param String $message The message to print.
	 */
	public static function force($message) {
		
		if (InputValidator::isEmpty($message)) {
			return;
		}
		self::printMessage($message, LogLevel::FORCE);
	}

	/**
	 * This function finally prints the message.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Restructor the output message.
	 * @param String $message The message to print.
	 * @param LogLevel $level The message level.
	 */
	private static function printMessage($message, $level) {

		switch (self::$OUT) {
			case LogOutput::SCREEN:
				echo "<strong>*************** " . strtoupper($level) . " ***************</strong><pre>";

				if (is_array($message)) {
					var_dump($message);
				}
				else {
					echo $message;
				}
				echo "</pre>";

				if ($level == LogLevel::ERROR || $level == LogLevel::WARNING) {
					self::printStacktrace();
				}
				break;
		}
	}

	/**
	 * This function prints the stacktrace.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Format a little bit.
	 */
	private static function printStacktrace() {
		$stack = debug_backtrace();
		$messageNumber = 0;

		foreach ($stack as $stackentry) {
			// dont show the first 3 messages, because this are Logger functions
			if ($messageNumber < 3) {
				$messageNumber++;
				continue;
			}
			echo "<pre>Function <strong>" . $stackentry['function'] . "</strong>( ";
			var_dump($stackentry['args']);
			echo " ) called at <strong>" . $stackentry["file"] . "</strong> line " . $stackentry["line"] . "</pre>";
		}
	}

	/**
	 * This function sets the log level.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use LogLevel enum.
	 * @since 0.0.3 Set php error reporting automatically in developing systems.
	 * @api
	 * @param LogLevel $level The log level to set.
	 */
	public static function setLogLevel($level) {
		if (!InputValidator::isLogLevel($level)) {
			return;
		}

		// set PHP error reporting
		switch ($level) {
			case LogLevel::ERROR:
				error_reporting(E_ERROR);
				ini_set("display_errors", 1);
				break;
			case LogLevel::NOTIFICATION:
				error_reporting(E_ALL);
				ini_set("display_errors", 1);
				break;
			case LogLevel::WARNING:
				error_reporting(E_WARNING);
				ini_set("display_errors", 1);
				break;
			default:
				ini_set("display_errors", 0);
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
}

/**
 * The log level 'enum'.
 *
 * Use this to define which log messages should be printed.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.1
 * @package ep6
 * @subpackage Util\Logger
 */
abstract class LogLevel {
	/** @var String Use this to print all messages. **/
	const NOTIFICATION = "NOTIFICATION";
	/** @var String Use this to print only warnings and errors. **/
	const WARNING = "WARNING";
	/** @var String Use this to print only errors. **/
	const ERROR = "ERROR";
	/** @var String Use this to print no log messages. **/
	const NONE = "NONE";
	/** @var String This is only used for intern reasons. **/
	const FORCE = "FORCE";
}

/**
 * The log output 'enum'.
 *
 * Use this to define where the log messages should be printed.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.1
 * @package ep6
 * @subpackage Util\Logger
 */
abstract class LogOutput {
	/** @var String Use this for print something on the screen. **/
	const SCREEN = "SCREEN";
}

?>