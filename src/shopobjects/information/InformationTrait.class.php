<?php
/**
 * This file represents the information trait.
 */
namespace ep6;
/**
 * This is the interface for all information objects.
 */
trait InformationTrait {
	
	/**
	 * The names of the shop, language dependend.
	 */
	private static $NAME = array();
	
	/**
	 * The navigation caption of the shop, language dependend.
	 */
	private static $NAVIGATIONCAPTION = array();
	
	/**
	 * The description of the shop, language dependend.
	 */
	private static $DESCRIPTION = array();

	/**
	 * Reload the REST information.
	 * This is only a empty placeholder. The child class can override it.
	 *
	 * @param String 	$locale The localization to load the information.
	 */
	private static function load($locale) {

		// if the REST path empty -> this is the not the implementation
		if (InputValidator::isEmpty(self::$RESTPATH)) {
			return;
		}

		// if request method is blocked
		if (!RESTClient::setRequestMethod("GET")) {
			return;
		}
	 	
		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return;
		}
		
		$content = RESTClient::sendWithLocalization(self::$RESTPATH, $locale);
		
		// if respond is empty
		if (InputValidator::isEmpty($content)) {
			return;
		}
		
		// reset values
		self::resetValues();
		
		if (array_key_exists("name", $content)) {
			self::$NAME[$locale] = $content["name"];
		}
		if (array_key_exists("navigationCaption", $content)) {
			self::$NAVIGATIONCAPTION[$locale] = $content["navigationCaption"];
		}
		if (array_key_exists("description", $content)) {
			self::$DESCRIPTION[$locale] = $content["description"];
		}
	}

	/**
	 * Set a value via REST.
	 *
	 * @param String	$parameter The key which should change.
	 * @param String	$value The string to set.
	 * @param String	$locale The localization String.
	 * @param boolean	True, if set the value works, false if not.
	 */
	private static function put($parameter, $value, $locale) {

		// if the REST path empty -> this is the not the implementation
		if (InputValidator::isEmpty(self::$RESTPATH)) {
			return false;
		}

		// if request method is blocked
		if (!RESTClient::setRequestMethod("PUT")) {
			return false;
		}
	 	
		// if the value is empty
		if (InputValidator::isEmpty($value)) {
			return false;
		}
	 	
		// if the parameter is empty
		if (InputValidator::isEmpty($parameter)) {
			return false;
		}
	 	
		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return false;
		}
		
		$postfields = array($parameter => $value);
		
		$content = RESTClient::sendWithLocalization(self::$RESTPATH, $locale, $postfields);
		
		// if respond is empty
		if (InputValidator::isEmpty($content)) {
			return;
		}
		
		// reset values
		self::resetValues();
		
		if (array_key_exists("name", $content)) {
			self::$NAME[$locale] = $content["name"];
		}
		if (array_key_exists("navigationCaption", $content)) {
			self::$NAVIGATIONCAPTION[$locale] = $content["navigationCaption"];
		}
		if (array_key_exists("description", $content)) {
			self::$DESCRIPTION[$locale] = $content["description"];
		}
	}

	/**
	 * This function resets all locales values.
	 */
	private static function resetValues() {

		self::$NAME = array();
		self::$NAVIGATIONCAPTION = array();
		self::$DESCRIPTION = array();
	}
	
	/**
	 * Gets the name in the default localization.
	 *
	 * @return String	The name in the default localization.
	 */
	public function getDefaultName() {
		
		// if no default language is visible
		if (empty(Locales::getDefault())) {
			return null;
		}
		
		return self::getName(Locales::getDefault());
	}
	
	/**
	 * Gets the name depended on the localization.
	 *
	 * @param String	$locale The locale String.
	 * @return String	The localized name.
	 */
	 public function getName($locale) {
	 	
		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}
		
		// if the localiation name is not set
		if (empty(self::$NAME) || !array_key_exists($locale, self::$NAME)) {
			self::load($locale);
			// after reload the REST ressource it is empty again.
			if (empty(self::$NAME) || !array_key_exists($locale, self::$NAME)) {
				return null;
			}
		}
		
		return self::$NAME[$locale];
	}
	
	/**
	 * Gets the navigation caption in the default localization.
	 *
	 * @return String	The navigation caption in the default localization.
	 */
	public function getDefaultNavigationCaption() {
		
		// if no default language is visible
		if (empty(Locales::getDefault())) {
			return null;
		}
		
		return self::getNavigationCaption(Locales::getDefault());
	}
	
	/**
	 * Gets the navigation caption depended on the localization.
	 *
	 * @param String	$locale The locale String.
	 * @return String	The localized navigation caption.
	 */
	 public function getNavigationCaption($locale) {
	 	
		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}
		
		// if the localiation name is not set
		if (empty(self::$NAVIGATIONCAPTION) || !array_key_exists($locale, self::$NAVIGATIONCAPTION)) {
			self::load($locale);
			// after reload the REST ressource it is empty again.
			if (empty(self::$NAVIGATIONCAPTION) || !array_key_exists($locale, self::$NAVIGATIONCAPTION)) {
				return null;
			}
		}
		
		return self::$NAVIGATIONCAPTION[$locale];
	}
	
	/**
	 * Gets the description in the default localization.
	 *
	 * @return String	The description in the default localization.
	 */
	public function getDefaultDescription() {
		
		// if no default language is visible
		if (empty(Locales::getDefault())) {
			return null;
		}
		
		return self::getDescription(Locales::getDefault());
	}
	
	/**
	 * Gets the description depended on the localization.
	 *
	 * @param String	$locale The locale String.
	 * @return String	The localized description.
	 */
	 public function getDescription($locale) {
	 	
		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}
		
		// if the localiation name is not set
		if (empty(self::$DESCRIPTION) || !array_key_exists($locale, self::$DESCRIPTION)) {
			self::load($locale);
			// after reload the REST ressource it is empty again.
			if (empty(self::$DESCRIPTION) || !array_key_exists($locale, self::$DESCRIPTION)) {
				return null;
			}
		}
		
		return self::$DESCRIPTION[$locale];
	}
	
	/**
	 * Sets the name depended on the localization.
	 *
	 * @param String	$value The string to set.
	 * @param String	$locale The localization String.
	 * @return boolean	True if the name is set, false if not.
	 */
	 public function setName($value, $locale) {

		// if the value is empty
		if (InputValidator::isEmpty($value)) {
			return false;
		}

		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return false;
		}
		
		return self::put("name", $value, $locale);
	}
	
	/**
	 * Sets the name depended on the localization.
	 *
	 * @param String	$value The string to set.
	 * @param String	$locale The localization String.
	 * @return boolean	True if the name is set, false if not.
	 */
	 public function setDefaultName($value) {

		self::setName($value, Locales::getDefault());
	}
}
?>