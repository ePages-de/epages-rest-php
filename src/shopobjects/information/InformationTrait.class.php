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
 * @since 0.1.0 Add a timestamp to save the next allowed REST call.
 * @since 0.1.0 Use a default Locale.
 * @package ep6
 * @subpackage Shopobjects\Information
 */
trait InformationTrait {

	/** @var String|null The names of the shop, language dependend. */
	private static $NAME = null;

	/** @var String|null The navigation caption of the shop, language dependend. */
	private static $NAVIGATIONCAPTION = null;

	/** @var String|null The description of the shop, language dependend. */
	private static $DESCRIPTION = null;

	/**
	 * Reload the REST information.
	 * This is only a empty placeholder. The child class can override it.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use HTTPRequestMethod enum.
	 * @since 0.1.0 Use a default Locale.
	 */
	private static function load() {

		// if the REST path empty -> this is the not the implementation or can't get something else
		if (InputValidator::isEmpty(self::$RESTPATH) ||
			!RESTClient::setRequestMethod(HTTPRequestMethod::GET)) {
			return;
		}

		$content = RESTClient::sendWithLocalization(self::$RESTPATH, Locales::getLocale());

		// if respond is empty
		if (InputValidator::isEmpty($content)) {
			return;
		}

		// reset values
		self::resetValues();

		if (!InputValidator::isEmptyArrayKey($content, "name")) {
			self::$NAME = $content["name"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "navigationCaption")) {
			self::$NAVIGATIONCAPTION = $content["navigationCaption"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "description")) {
			self::$DESCRIPTION = $content["description"];
		}

		// update timestamp when make the next request
		$timestamp = (int) (microtime(true) * 1000);
		self::$NEXT_REQUEST_TIMESTAMP = $timestamp + RESTClient::NEXT_RESPONSE_WAIT_TIME;
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
		if (!InputValidator::isEmpty(self::$NAME) &&
			!InputValidator::isEmpty(self::$NAVIGATIONCAPTION) &&
			!InputValidator::isEmpty(self::$DESCRIPTION) &&
			self::$NEXT_REQUEST_TIMESTAMP > $timestamp) {
			return;
		}

		self::load();
	}

	/**
	 * This function resets all values.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use the default Locale.
	 */
	private static function resetValues() {

		self::$NAME = null;
		self::$NAVIGATIONCAPTION = null;
		self::$DESCRIPTION = null;
	}

	/**
	 * Gets the name in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Deprecated because the Locale is everytime the configured Locale.
	 * @api
	 * @deprecated
	 * @return String|null The name in the default localization or null if the default name is unset.
	 */
	public function getDefaultName() {

		return self::getName();
	}

	/**
	 * Gets the name.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @api
	 * @return String|null The name or null if the name is unset.
	 */
	 public function getName() {

		self::reload();
		return InputValidator::isEmpty(self::$NAME) ? null : self::$NAME;
	}

	/**
	 * Gets the navigation caption in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Deprecated because the Locale is everytime the configured Locale.
	 * @api
	 * @deprecated
	 * @return String|null The navigation caption in the default localization or null if the default navigation caption is unset.
	 */
	public function getDefaultNavigationCaption() {

		return self::getNavigationCaption();
	}

	/**
	 * Gets the navigation caption.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @api
	 * @return String|null The navigation caption or null if the navigation caption is unset.
	 */
	 public function getNavigationCaption() {

		self::reload($locale);
		return InputValidator::isEmpty(self::$NAVIGATIONCAPTION) ? null : self::$NAVIGATIONCAPTION;
	}

	/**
	 * Gets the description in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Deprecated because the Locale is everytime the configured Locale.
	 * @api
	 * @deprecated
	 * @return String|null The description in the default localization or null if the default description is unset.
	 */
	public function getDefaultDescription() {

		return self::getDescription();
	}

	/**
	 * Gets the description.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @api
	 * @return String|null The localized description or null if the description is unset.
	 */
	 public function getDescription() {

		self::reload($locale);
		return InputValidator::isEmpty(self::$DESCRIPTION) ? null : self::$DESCRIPTION;
	}
}
?>