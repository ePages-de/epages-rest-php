<?php
/**
 * This file represents the JSON handler class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
/**
 * This is a small simple handler to convert JSON into an array and otherwise.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @package ep6
 * @subpackage Util
 */
class JSONHandler {
	/**
	 * Call this function with the JSON in parameter.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @param String $JSON The JSON string to parse.
	 * @return mixed[] The array of the JSON element or null if there is an error.
	 */
	public static function parseJSON($JSON) {

		if (!InputValidator::isJSON($JSON)) {
			return array();
		}

		$result = json_decode($JSON, true);

		if (!InputValidator::isArray($result)) {
			Logger::warning("There is an error with parsing the follwing JSON: <strong>" . json_last_error() . ": " . json_last_error_msg() . "</strong><br/>\n"
						. "<pre>" . $JSON . "</pre>");
			return array();
		}

		return $result;
	}

	/**
	 * Call this function to create a JSON string from a array.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.2 Extend the encoding with avoid encode slashes.
	 * @param mixed[] $array The array to make a JSON.
	 * @return String The JSON string.
	 */
	public static function createJSON($array) {

		if (!InputValidator::isArray($array)) {
			return null;
		}

		$result = json_encode($array, JSON_UNESCAPED_SLASHES);

		if (!InputValidator::isJSON($result)) {
			Logger::warning("There is an error with creating a JSON with the following array: <strong>" . json_last_error() . ": " . json_last_error_msg() . "</strong><br/>\n"
						. "<pre>" . $array . "</pre>");
			return null;
		}

		return $result;
	}
}
?>