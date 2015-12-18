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
 * @package ep6
 * @subpackage Shopobjects
 * @example examples\handleWithCurrencies.php Handle with currencies.
 */
class Currencies {

	/** @var String The REST path for currencies. */
	const RESTPATH = "currencies";

	/** @var String|null Space to save the default currencies. */
	private static $DEFAULT = null;

	/** @var String[] Space to save the possible currencies. */
	private static $ITEMS = array();

	/** @var int Timestamp in ms when the next request needs to be done. */
	private static $NEXT_REQUEST_TIMESTAMP = 0;

	/**
	 * Gets the default and possible currencies of the shop.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use HTTPRequestMethod enum
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

		// save the default currency
		self::$DEFAULT = $content["default"];

		// parse the possible currencies
		self::$ITEMS = $content["items"];

		// update timestamp when make the next request
		$timestamp = (int) (microtime(true) * 1000);
		self::$NEXT_REQUEST_TIMESTAMP = $timestamp + RESTClient::NEXT_RESPONSE_WAIT_TIME;
	}

	/**
	 * This function resets all curencies values.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 */
	public static function resetValues() {

		self::$DEFAULT = null;
		self::$ITEMS = array();
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
	 * @api
	 * @return The default currencies of the shop.
	 */
	public static function getDefault() {

		self::reload();
		return self::$DEFAULT;
	}

	/**
	 * Gets the activated currencies.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @api
	 * @return The possible currencies of the shop.
	 */
	public static function getItems() {

		self::reload();
		return self::$ITEMS;
	}

}
?>