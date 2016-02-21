<?php
/**
 * This file represents the locales class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
/**
 * This is the static class for the localization.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @since 0.1.0 Add a timestamp to save the next allowed REST call.
 * @since 0.1.0 Add configured used Locale.
 * @since 0.1.1 Now the object is echoable.
 * @package ep6
 * @subpackage Shopobjects
 * @example examples\handleWithLocales.php Handle with locales.
 */
class Locales {

	/** @var String The REST path for localizations. */
	const RESTPATH = "locales";

	/** @var String|null Space to save the default locales. */
	private static $DEFAULT = null;

	/** @var String[] Space to save the possible locales. */
	private static $ITEMS = array();

	/** @var String|null Configured Locale. */
	private static $USED = null;

	/** @var int Timestamp in ms when the next request needs to be done. */
	private static $NEXT_REQUEST_TIMESTAMP = 0;

	/**
	 * Gets the default and possible locales of the shop.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use HTTPRequestMethod enum.
	 * @since 0.1.0 Save timestamp of the last request.
 	 * @since 0.1.0 Add configured used Locale.
	 * @api
	 */
	private static function load() {

		// if request method is blocked
		if (!RESTClient::setRequestMethod(HTTPRequestMethod::GET)) {
			return;
		}

		$content = RESTClient::send(self::RESTPATH);

		// if respond is empty or there are no default AND items element
		if (InputValidator::isEmptyArrayKey($content, "default") ||
			InputValidator::isEmptyArrayKey($content, "items")) {
		    Logger::error("Respond for " . self::RESTPATH . " can not be interpreted.");
			return;
		}

		// reset values
		self::resetValues();

		// save the default localization
		self::$DEFAULT = $content["default"];

		// parse the possible localizations
		self::$ITEMS = $content["items"];

		// set the configured shop Locale if it is empty.
		if (InputValidator::isEmpty(self::$USED)) {
			self::$USED = $content["default"];
		}

		// update timestamp when make the next request
		$timestamp = (int) (microtime(true) * 1000);
		self::$NEXT_REQUEST_TIMESTAMP = $timestamp + RESTClient::$NEXT_RESPONSE_WAIT_TIME;
	}

	/**
	 * This function resets all locales values.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
 	 * @since 0.1.0 Reset used Locale.
	 * @api
	 */
	public static function resetValues() {

		self::$DEFAULT = null;
		self::$ITEMS = array();
		self::$USED = null;
	}

	/**
	 * This function checks whether a reload is needed.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 */
	private static function reload() {

		$timestamp = (int) (microtime(true) * 1000);

		// if the value is empty
		if (!InputValidator::isEmpty(self::$DEFAULT) &&
			!InputValidator::isEmpty(self::$ITEMS) &&
			self::$NEXT_REQUEST_TIMESTAMP > $timestamp) {
			return;
		}

		self::load();
	}

	/**
	 * Gets the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @api
	 * @return The default localization of the shop.
	 */
	public static function getDefault() {

		self::reload();
		return self::$DEFAULT;
	}

	/**
	 * Gets the activated localizations.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @api
	 * @return The possible localizations of the shop.
	 */
	public static function getItems() {

		self::reload();
		return self::$ITEMS;
	}

	/**
	 * Gets the configured Locale.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @return The configured Locale which is used in REST calls.
	 */
	public static function getLocale() {

		self::reload();
		return self::$USED;
	}

	/**
	 * Sets the configured Locale.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @param String $locale The new used Locale.
	 * @return boolean True if set the Locale works, false if not.
	 */
	public static function setLocale($locale) {

		self::reload();
		if (array_key_exists($locale, self::$ITEMS)) {
			self::$USED = $locale;
			return true;
		}
		return false;
	}

}
?>