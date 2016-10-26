<?php
/**
 * This file represents the Date class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.1.3
 */
namespace ep6;
/**
 * This is a Date class to handle date formats.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @package ep6
 * @since 0.1.3
 * @subpackage Shopobjects\Date
 */
class Date {

	use ErrorReporting;

	/** @var String|null The date as timestamp. **/
	private $timestamp = null;

	/**
	 * This is the constructor.
	 *
	 * This function extracts the date.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $date The date to set (timestamp or strtotime allowed string).
	 * @since 0.1.3
	 * @since 0.2.1 Extend constructor to use also timestamps.
	 */
	public function __construct($date) {

		self::errorReset();

		// if parameter is no string
		if (InputValidator::isTimestamp($date)) {
			$timestamp = $date;
		}
		else if (InputValidator::isString($date)) {
			// try to convert to a timestamp
			$timestamp = strtotime($date);
		}
		else {
			$this->errorSet("D-1");
			Logger::error("ep6\Date\nThe parameter date " . $date . " is no string.");
			return;
		}

		if (!$timestamp) {

			$this->errorSet("D-2");
			Logger::error("ep6\Date\nThe parameter date " . $date . " is no valid date format.");
			return;
		}

		$this->timestamp = $timestamp;
	}

	/**
	 * Prints the Date object as a string.
	 *
	 * This function returns the setted values of the Date object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The Date as a string.
	 * @since 0.2.0
	 */
	public function __toString() {

		return "<strong>Timestamp:</strong> " . $this->timestamp . "<br/>" .
			"<strong>Readable:</strong> " . $this->asReadable() . "<br/>";
	}
	
	/**
	 * Gets the Date object in readable format for REST communication.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The readable format of the Date object.
	 * @since 0.2.0
	 * @since 0.2.1 Calculate readable always into GMT/UTC.
	 */
	public function asReadable() {
		return gmdate("Y-m-d\TH:i:s.000\Z", $this->timestamp);
	}

	/**
	 * Returns the date es timestamp.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return int The timestamp.
	 * @since 0.1.3
	 */
	public function getTimestamp() {

		self::errorReset();
		return $this->timestamp;
	}
}
?>