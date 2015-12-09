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
	 * Gets the default currency.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use InputValidator to check values.
	 * @api
	 * @return The default currencies of the shop.
	 */
	public static function getDefault() {
		
		if (InputValidator::isEmpty(self::$DEFAULT)) {
			self::load();
		}
		return (InputValidator::isEmpty(self::$DEFAULT)) ? null : self::$DEFAULT;
	}
	
	/**
	 * Gets the activated currencies.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use InputValidator to check values.
	 * @api
	 * @return The possible currencies of the shop.
	 */
	public static function getItems() {
		
		if (InputValidator::isEmptyArray(self::$ITEMS)) {
			self::load();
		}
		return (InputValidator::isEmptyArray(self::$ITEMS)) ? null : self::$ITEMS;
	}

}
?>