<?php
/**
 * This file represents the currencies class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
/**
 * This is the static class for the currencies in the shop.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @since 0.1.0 Add a timestamp to save the next allowed REST call.
 * @since 0.1.0 Add configured used Currency.
 * @since 0.1.1 Now the object is echoable.
 * @since 0.1.2 Add error reporting.
 * @package ep6
 * @subpackage Shopobjects
 * @example examples\handleWithCurrencies.php Handle with currencies.
 */
class Currencies {
	
	use ErrorReporting;

	/** @var String The REST path for currencies. */
	const RESTPATH = "currencies";

	/** @var String|null Space to save the default currencies. */
	private static $DEFAULT = null;

	/** @var String[] Space to save the possible currencies. */
	private static $ITEMS = array();

	/** @var String|null Configured Locale. */
	private static $USED = null;

	/** @var int Timestamp in ms when the next request needs to be done. */
	private static $NEXT_REQUEST_TIMESTAMP = 0;

	/**
	 * Gets the default and possible currencies of the shop.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use HTTPRequestMethod enum
	 * @since 0.1.0 Save timestamp of the last request.
 	 * @since 0.1.0 Add configured used Currency.
	 * @since 0.1.2 Add error reporting.
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
			self::errorSet("C-1");
			return;
		}

		// reset values
		self::resetValues();

		// save the default currency
		self::$DEFAULT = $content["default"];

		// parse the possible currencies
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
	 * This function resets all curencies values.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
 	 * @since 0.1.0 Reset used Currency.
	 * @since 0.1.2 Add error reporting.
	 * @api
	 */
	public static function resetValues() {
		
		self::errorReset();

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
	 * Gets the default currency.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.2 Add error reporting.
	 * @api
	 * @return The default currencies of the shop.
	 */
	public static function getDefault() {

		self::errorReset();

		self::reload();
		return self::$DEFAULT;
	}

	/**
	 * Gets the activated currencies.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.2 Add error reporting.
	 * @api
	 * @return The possible currencies of the shop.
	 */
	public static function getItems() {

		self::errorReset();

		self::reload();
		return self::$ITEMS;
	}

	/**
	 * Gets the configured Currency.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @since 0.1.2 Add error reporting.
	 * @api
	 * @return The configured Currency which is used in REST calls.
	 */
	public static function getCurrency() {

		self::errorReset();

		self::reload();
		return self::$USED;
	}

	/**
	 * Sets the configured Currency.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @since 0.1.2 Add error reporting.
	 * @api
	 * @param String $currency The new used Locale.
	 * @return boolean True if set the Currency works, false if not.
	 */
	public static function setCurrency($currency) {

		self::errorReset();
		
		self::reload();
		if (array_key_exists($currency, self::$ITEMS)) {
			self::$USED = $currency;
			return true;
		}
	    Logger::error("Can't set currency " . $currency . ". It is not available in the shop.");
		self::errorSet("C-2");
		return false;
	}

}
?>