<?php
/**
 * This file represents the input validator class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
/**
 * This class, used by a static way, checks whether a value is valid.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @package ep6
 * @since 0.0.0
 * @since 0.1.0 Add function to add float values.
 * @since 0.1.3 Remove isRESTCommand function.
 * @subpackage Util
 */
class InputValidator {

	/**
	 * Checks whether a parameter is an array.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param array $parameter Array to check.
	 * @return boolean True if the parameter is an array, false if not.
	 * @since 0.0.0
	 */
	public static function isArray($parameter) {

		return is_array($parameter) && !self::isEmpty($parameter);
	}

	/**
	 * Checks whether a parameter is a OAuth authentification token.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $parameter String to check.
	 * @return boolean True if the string is a OAuth authentification token, false if not.
	 * @since 0.0.0
	 */
	public static function isAuthToken($parameter) {

		return !self::isEmpty($parameter);
	}

	/**
	 * Checks whether a parameter is a currency string.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $parameter String to check.
	 * @return boolean True if the string is a currency string, false if not.
	 * @since 0.0.0
	 */
	public static function isCurrency($parameter) {

		return self::isMatchRegex($parameter, "/^[A-Z]{3}$/", "Currency")
			&& !self::isEmpty($parameter);
	}

	/**
	 * Checks whether a paramter is empty or null.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $parameter String to check.
	 * @return boolean True if the parameter is empty or null, false if not.
	 * @since 0.0.0
	 * @since 0.1.0 Test if the parameter is also the correct type.
	 */
	public static function isEmpty($parameter) {

		return (is_null($parameter) || ($parameter === ""));
	}

	/**
	 * Checks whether an array is empty or null.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param mixed[] $parameter Array to check.
	 * @return boolean True if the array is empty or null, false if not.
	 * @since 0.1.0
	 */
	public static function isEmptyArray($parameter) {

		return (is_null($parameter) || empty($parameter));
	}

	/**
	 * Checks whether an array key is unset or null.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param mixed[] $array Array to check.
	 * @param String $key Key to exists and should not be null or empty.
	 * @return boolean True if the array key is unset or null, false if not.
	 * @since 0.1.0
	 * @since 0.2.0 Create new function to check whether a key exists but can be null or empty.
	 */
	public static function isEmptyArrayKey($array, $key) {

		return self::isExistsArrayKey($array, $key) || is_null($array[$key]);
	}

	/**
	 * Checks whether an array key exists. It can be unset or null.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param mixed[] $array Array to check.
	 * @param String $key Key to exists.
	 * @return boolean True if the array key exists, false if not.
	 * @since 0.2.0
	 */
	public static function isExistsArrayKey($array, $key) {

		return self::isEmptyArray($array) || !array_key_exists($key, $array);
	}

	/**
	 * Checks whether the parameter is an existing file.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param float $parameter File with path to check.
	 * @return boolean True if the parameter is an existing file, false if not.
	 * @since 0.2.0
	 */
	public static function isExistingFile($parameter) {

		return !self::isEmpty($parameter) && file_exists($parameter);
	}

	/**
	 * Checks whether a parameter is a float.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param float $parameter Float to check.
	 * @return boolean True if the parameter is a float, false if not.
	 * @since 0.1.0
	 */
	public static function isFloat($parameter) {

		return is_float($parameter) && !self::isEmpty($parameter);
	}

	/**
	 * Checks whether a parameter is a host.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $parameter String to check.
	 * @return boolean True if the string is a host, false if not.
	 * @since 0.0.0
	 */
	public static function isHost($parameter) {

		return self::isMatchRegex($parameter, "/^([a-zA-Z0-9]([a-zA-Z0-9\\-]{0,61}[a-zA-Z0-9])?\\.)+[a-zA-Z]{2,6}/", "host")
			&& !self::isEmpty($parameter);
	}

	/**
	 * Checks whether a parameter is an int.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param int $parameter Int to check.
	 * @return boolean True if the parameter is an int, false if not.
	 * @since 0.0.0
	 */
	public static function isInt($parameter) {

		return is_int($parameter) && !self::isEmpty($parameter);
	}

	/**
	 * Checks whether a parameter is a JSON string.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $parameter String to check.
	 * @return boolean True if the string is a JSON string, false if not.
	 * @since 0.0.0
	 */
	public static function isJSON($parameter) {
		return !is_null(json_decode($parameter))
			&& !self::isEmpty($parameter);
	}

	/**
	 * Checks whether a parameter is a localization string.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $parameter String to check.
	 * @return boolean True if the string is a localization string, false if not.
	 * @since 0.0.0
	 */
	public static function isLocale($parameter) {

		return self::isMatchRegex($parameter, "/^[a-z]{2,4}_[A-Z]{2,3}$/", "Locale")
			&& !self::isEmpty($parameter);
	}

	/**
	 * Checks whether a parameter is a log level.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $parameter String to check.
	 * @return boolean True if the string is a log level, false if not.
	 * @since 0.0.0
	 * @since 0.1.0 Use LogLevel enum.
	 */
	public static function isLogLevel($parameter) {

		return self::isMatchRegex($parameter, "/^(NOTIFICATION|WARNING|ERROR|NONE)/", "log level")
			&& !self::isEmpty($parameter);
	}

	/**
	 * Checks whether a parameter is a product sort direction.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $parameter String to check.
	 * @return boolean True if the parameter is a product sort direction, false if not.
	 * @since 0.0.0
	 */
	public static function isProductDirection($parameter) {

		return self::isMatchRegex($parameter, "/^(asc|desc)$/", "products sort direction")
			&& !self::isEmpty($parameter);
	}

	/**
	 * Checks whether a parameter is a valid product id.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $parameter String to check.
	 * @return boolean True if the parameter is a valid product id, false if not.
	 * @since 0.0.0
	 */
	public static function isProductId($parameter) {

		return !self::isEmpty($parameter);
	}

	/**
	 * Checks whether a parameter is a product sort parameter.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $parameter String to check.
	 * @return boolean True if the parameter is a product sort parameter, false if not.
	 * @since 0.0.0
	 */
	public static function isProductSort($parameter) {

		return self::isMatchRegex($parameter, "/^(name|price)$/", "products sort parameter")
			&& !self::isEmpty($parameter);
	}

	/**
	 * Checks whether a parameter is an output ressource.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $parameter String to check.
	 * @return boolean True if the string is an output ressource, false if not.
	 * @since 0.0.0
	 * @since 0.1.0 Use LogOutput enum.
	 * @since 0.1.2 Add file output ressource.
	 */
	public static function isOutputRessource($parameter) {

		return self::isMatchRegex($parameter, "/^(SCREEN|FILE)/", "output ressource")
			&& !self::isEmpty($parameter);
	}

	/**
	 * Checks whether a parameter is a float with a range.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param float $parameter Float to check.
	 * @param float|null $minimum The minimum allowed number, null if there is no minimum.
	 * @param float|null $maximum The maximum allowed number, null if there is no maximum.
	 * @return boolean True if the parameter is an int, false if not.
	 * @since 0.1.0
	 */
	public static function isRangedFloat($parameter, $minimum = null, $maximum = null) {

		return self::isFloat($parameter)
			&& (self::isFloat($minimum) ? $parameter >= $minimum : true)
			&& (self::isFloat($maximum) ? $parameter <= $maximum : true);
	}

	/**
	 * Checks whether a parameter is an int with a range.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param int $parameter Int to check.
	 * @param int|null $minimum The minimum allowed number, null if there is no minimum.
	 * @param int|null $maximum The maximum allowed number, null if there is no maximum.
	 * @return boolean True if the parameter is an int, false if not.
	 * @since 0.0.0
	 */
	public static function isRangedInt($parameter, $minimum = null, $maximum = null) {

		return self::isInt($parameter)
			&& (self::isInt($minimum) ? $parameter >= $minimum : true)
			&& (self::isInt($maximum) ? $parameter <= $maximum : true);
	}

	/**
	 * Checks whether a parameter is a HTTP request method.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $parameter String to check.
	 * @return boolean True if the string is a HTTP request method, false if not.
	 * @since 0.0.0
	 * @since 0.1.0 Use HTTPRequestMethod enum.
	 */
	public static function isRequestMethod($parameter) {

		return self::isMatchRegex($parameter, "/^(GET|POST|PUT|DELETE|PATCH)/", "HTTP request method")
			&& !self::isEmpty($parameter);
	}

	/**
	 * Checks whether a parameter is a shop.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $parameter String to check.
	 * @return boolean True if the string is a shop, false if not.
	 * @since 0.0.0
	 */
	public static function isShop($parameter) {

		return !self::isEmpty($parameter);
	}

	/**
	 * Checks whether a parameter is a string.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $parameter Parameter to check.
	 * @return boolean True if the parameter is a string, false if not.
	 * @since 0.1.2
	 */
	public static function isString($parameter) {

		return is_string($parameter) && !self::isEmpty($parameter);
	}

	/**
	 * Checks whether a parameter match a regex.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $parameter String to check.
	 * @param String $regex	 The regex to check.
	 * @param String $type The type which is validated.
	 * @return boolean True if the string validates, false if not.
	 * @since 0.0.0
	 * @since 0.1.2 Don't throw a logging message.
	 */
	private static function isMatchRegex($parameter, $regex, $type) {

		return preg_match($regex, $parameter);
	}

}
?>