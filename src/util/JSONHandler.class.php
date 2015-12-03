<?php
/**
 * This file represents the json handler class.
 */
namespace ep6;
/**
 * This is a small simple handler to convert JSON into an array and otherwise.
 *
 * Call this class as a static object like:
 *   JSONHandler::parseJSON(JSON);
 *   JSONHandler::createJSON(ARRAY);
 */
class JSONHandler {
	/**
	 * Call this function with the JSON in parameter.
	 *
	 * @param String	$JSON	The JSON string to parse.
	 * @return array	The array of the JSON element or null if there is an error.
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
	 * @param array 	$array	The array to make a JSON.
	 * @return String	The JSON string.
	 */
	public static function createJSON($array) {

		if (!InputValidator::isArray($array)) {
			return null;
		}

		$result = json_encode($array);

		if (!InputValidator::isJSON($result)) {
			Logger::warning("There is an error with creating a JSON with the following array: <strong>" . json_last_error() . ": " . json_last_error_msg() . "</strong><br/>\n"
						. "<pre>" . $array . "</pre>");
			return null;
		}

		return $result;
	}
}
?>