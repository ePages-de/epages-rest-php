<?php
/**
 * This file represents the information trait.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
/**
 * This is the interface for all information objects.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @package ep6
 * @subpackage Shopobjects\Information
 */
trait InformationTrait {
	
	/** @var String[] The names of the shop, language dependend. */
	private static $NAME = array();
	
	/** @var String[] The navigation caption of the shop, language dependend. */
	private static $NAVIGATIONCAPTION = array();
	
	/** @var String[] The description of the shop, language dependend. */
	private static $DESCRIPTION = array();

	/**
	 * Reload the REST information.
	 * This is only a empty placeholder. The child class can override it.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use HTTPRequestMethod enum.
	 * @param String $locale The localization to load the information.
	 */
	private static function load($locale) {

		// if the REST path empty -> this is the not the implementation or can't get something else
		if (InputValidator::isEmpty(self::$RESTPATH) ||
			!RESTClient::setRequestMethod(HTTPRequestMethod::GET) ||
			!InputValidator::isLocale($locale)) {
			return;
		}

		$content = RESTClient::sendWithLocalization(self::$RESTPATH, $locale);
		
		// if respond is empty
		if (InputValidator::isEmpty($content)) {
			return;
		}
		
		// reset values
		self::resetValues();
		
		if (!InputValidator::isEmptyArrayKey($content, "name")) {
			self::$NAME[$locale] = $content["name"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "navigationCaption")) {
			self::$NAVIGATIONCAPTION[$locale] = $content["navigationCaption"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "description")) {
			self::$DESCRIPTION[$locale] = $content["description"];
		}
	}

	/**
	 * This function resets all locales values.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 */
	private static function resetValues() {

		self::$NAME = array();
		self::$NAVIGATIONCAPTION = array();
		self::$DESCRIPTION = array();
	}
	
	/**
	 * Gets the name in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return String|null The name in the default localization or null if the default name is unset.
	 */
	public function getDefaultName() {
		
		// if no default language is visible
		if (InputValidator::isEmpty(Locales::getDefault())) {
			return null;
		}
		
		return self::getName(Locales::getDefault());
	}
	
	/**
	 * Gets the name depended on the localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @param String $locale The locale String.
	 * @return String|null The localized name or null if the name is unset.
	 */
	 public function getName($locale) {
	 	
		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}
		
		// if the localiation name is not set
		if (InputValidator::isEmptyArrayKey(self::$NAME, $locale)) {
			self::load($locale);
			// after reload the REST ressource it is empty again.
			if (InputValidator::isEmptyArrayKey(self::$NAME, $locale)) {
				return null;
			}
		}
		
		return self::$NAME[$locale];
	}
	
	/**
	 * Gets the navigation caption in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return String|null The navigation caption in the default localization or null if the default navigation caption is unset.
	 */
	public function getDefaultNavigationCaption() {
		
		// if no default language is visible
		if (InputValidator::isEmpty(Locales::getDefault())) {
			return null;
		}
		
		return self::getNavigationCaption(Locales::getDefault());
	}
	
	/**
	 * Gets the navigation caption depended on the localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @param String $locale The locale String.
	 * @return String|null The localized navigation caption or null if the navigation caption is unset.
	 */
	 public function getNavigationCaption($locale) {
	 	
		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}
		
		// if the localiation name is not set
		if (InputValidator::isEmptyArrayKey(self::$NAVIGATIONCAPTION, $locale)) {
			self::load($locale);
			// after reload the REST ressource it is empty again.
			if (InputValidator::isEmptyArrayKey(self::$NAVIGATIONCAPTION, $locale)) {
				return null;
			}
		}
		
		return self::$NAVIGATIONCAPTION[$locale];
	}
	
	/**
	 * Gets the description in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return String|null The description in the default localization or null if the default description is unset.
	 */
	public function getDefaultDescription() {
		
		// if no default language is visible
		if (InputValidator::isEmpty(Locales::getDefault())) {
			return null;
		}
		
		return self::getDescription(Locales::getDefault());
	}
	
	/**
	 * Gets the description depended on the localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @param String $locale The locale String.
	 * @return String|null The localized description or null if the description is unset.
	 */
	 public function getDescription($locale) {
	 	
		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}
		
		// if the localiation name is not set
		if (InputValidator::isEmptyArrayKey(self::$DESCRIPTION, $locale)) {
			self::load($locale);
			// after reload the REST ressource it is empty again.
			if (InputValidator::isEmptyArrayKey(self::$DESCRIPTION, $locale)) {
				return null;
			}
		}
		
		return self::$DESCRIPTION[$locale];
	}
}
?>