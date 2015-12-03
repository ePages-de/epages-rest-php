<?php
/**
 * This file represents the locales class.
 */
namespace ep6;
/**
 * This is the static class for the localization.
 *
 * Use it like the following code:
 *   Locales::getDefault();
 *   Locales::getItems();
 */
class Locales {
	
	/**
	 * The REST path for localizations.
	 */
	const RESTPATH = "locales";
	
	/**
	 * Space to save the default locales.
	 */
	private static $DEFAULT = null;
	
	/**
	 * Space to save the possible locales.
	 */
	private static $ITEMS = array();
	
	/**
	 * Gets the default and possible locales of the shop.
	 */
	private static function load() {

		// if request method is blocked
		if (!RESTClient::setRequestMethod("GET")) {
			return;
		}

		$content = RESTClient::send(self::RESTPATH);
		
		// if respond is empty
		if (InputValidator::isEmpty($content)) {
			return;
		}
		
		// if there is no default AND items element
		if (!array_key_exists("default", $content) || !array_key_exists("items", $content)) {
		    Logger::error("Respond for " . self::RESTPATH . " can not be interpreted.");
			return;
		}
		
		// reset values
		self::resetValues();
		
		// save the default localization
		self::$DEFAULT = $content["default"];
		
		// parse the possible localizations
		self::$ITEMS = $content["items"];
	}

	/**
	 * This function resets all locales values.
	 */
	public static function resetValues() {

		self::$DEFAULT = null;
		self::$ITEMS = array();
	}
	
	/**
	 * Gets the default localization.
	 *
	 * @return The default localization of the shop.
	 */
	public static function getDefault() {
		
		if (self::$DEFAULT == null) {
			self::load();
		}
		return (self::$DEFAULT == null) ? null : self::$DEFAULT;
	}
	
	/**
	 * Gets the activated localizations.
	 *
	 * @return The possible localizations of the shop.
	 */
	public static function getItems() {
		
		if (empty(self::$ITEMS)) {
			self::load();
		}
		return (empty(self::$ITEMS)) ? null : self::$ITEMS;
	}

}
?>