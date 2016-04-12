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
	 * @param String $date The date to set.
	 * @since 0.1.3
	 */
	public function __construct($date) {

		// if parameter is no string
		if (!InputValidator::isString($date)) {

			$this->errorSet("D-1");
			Logger::error("ep6\Date\nThe parameter date " . $date . " is no string.");
			return;
		}

		// try to convert to a timestamp
		$timestamp = strtotime($date);

		if (!$timestamp) {

			$this->errorSet("D-2");
			Logger::error("ep6\Date\nThe parameter date " . $date . " is no valid date format.");
			return;
		}

		$this->timestamp = $timestamp;
	}

	/**
	 * Returns the date es timestamp.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return int The timestamp.
	 * @since 0.1.3
	 */
	public function getTimestamp() {

		$this->errorReset();
		return $this->timestamp;
	}
}
?>