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
 * @since 0.1.1 The information object can be echoed.
 * @since 0.1.1 Unstatic every attributes.
 * @package ep6
 * @subpackage Shopobjects\Information
 */
trait InformationTrait {

	/** @var String|null The names of the shop, language dependend. */
	private $NAME = null;

	/** @var String|null The navigation caption of the shop, language dependend. */
	private $NAVIGATIONCAPTION = null;

	/** @var String|null The description of the shop, language dependend. */
	private $DESCRIPTION = null;

	/**
	 * Reload the REST information.
	 * This is only a empty placeholder. The child class can override it.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use HTTPRequestMethod enum.
	 * @since 0.1.0 Use a default Locale.
	 * @since 0.1.1 Unstatic every attributes.
	 */
	private function load() {

		// if the REST path empty -> this is the not the implementation or can't get something else
		if (InputValidator::isEmpty(self::RESTPATH) ||
			!RESTClient::setRequestMethod(HTTPRequestMethod::GET)) {
			$error = InputValidator::isEmpty(self::RESTPATH) ? "TI-1" : "RESTC-9";
			self::errorSet($error);
			return;
		}

		$content = RESTClient::sendWithLocalization(self::RESTPATH, Locales::getLocale());

		// if respond is empty
		if (InputValidator::isEmpty($content)) {
			self::errorSet("TI-2");
			return;
		}

		// reset values
		$this->resetValues();

		if (!InputValidator::isEmptyArrayKey($content, "name")) {
			$this->NAME = $content["name"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "navigationCaption")) {
			$this->NAVIGATIONCAPTION = $content["navigationCaption"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "description")) {
			$this->DESCRIPTION = $content["description"];
		}

		// update timestamp when make the next request
		$timestamp = (int) (microtime(true) * 1000);
		$this->NEXT_REQUEST_TIMESTAMP = $timestamp + RESTClient::$NEXT_RESPONSE_WAIT_TIME;
	}

	/**
	 * This function checks whether a reload is needed.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @since 0.1.1 Unstatic every attributes.
	 * @api
	 */
	private function reload() {

		$timestamp = (int) (microtime(true) * 1000);

		// if the value is empty
		if (!InputValidator::isEmpty($this->NAME) &&
			!InputValidator::isEmpty($this->NAVIGATIONCAPTION) &&
			!InputValidator::isEmpty($this->DESCRIPTION) &&
			$this->NEXT_REQUEST_TIMESTAMP > $timestamp) {
			return;
		}

		$this->load();
	}

	/**
	 * This function resets all values.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use the default Locale.
	 * @since 0.1.1 Unstatic every attributes.
	 */
	private function resetValues() {

		$this->NAME = null;
		$this->NAVIGATIONCAPTION = null;
		$this->DESCRIPTION = null;
	}

	/**
	 * Gets the name in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Deprecated because the Locale is everytime the configured Locale.
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 * @api
	 * @deprecated
	 * @return String|null The name in the default localization or null if the default name is unset.
	 */
	public function getDefaultName() {
		
		self::errorReset();
		return $this->getName();
	}

	/**
	 * Gets the name.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 * @api
	 * @return String|null The name or null if the name is unset.
	 */
	 public function getName() {
		
		self::errorReset();
		$this->reload();
		return InputValidator::isEmpty($this->NAME) ? null : $this->NAME;
	}

	/**
	 * Gets the navigation caption in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Deprecated because the Locale is everytime the configured Locale.
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 * @api
	 * @deprecated
	 * @return String|null The navigation caption in the default localization or null if the default navigation caption is unset.
	 */
	public function getDefaultNavigationCaption() {
		
		self::errorReset();
		return $this->getNavigationCaption();
	}

	/**
	 * Gets the navigation caption.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 * @api
	 * @return String|null The navigation caption or null if the navigation caption is unset.
	 */
	 public function getNavigationCaption() {

		self::errorReset();
		$this->reload();
		return InputValidator::isEmpty($this->NAVIGATIONCAPTION) ? null : $this->NAVIGATIONCAPTION;
	}

	/**
	 * Gets the description in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Deprecated because the Locale is everytime the configured Locale.
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 * @api
	 * @deprecated
	 * @return String|null The description in the default localization or null if the default description is unset.
	 */
	public function getDefaultDescription() {
		
		self::errorReset();
		return $this->getDescription();
	}

	/**
	 * Gets the description.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 * @api
	 * @return String|null The localized description or null if the description is unset.
	 */
	 public function getDescription() {

		self::errorReset();
		$this->reload();
		return InputValidator::isEmpty($this->DESCRIPTION) ? null : $this->DESCRIPTION;
	}

	/**
	 * Prints the Information object as a string.
	 *
	 * This function returns the setted values of the Information object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.1
	 * @return String The Information as a string.
	 */
	public function __toString() {

		return "<strong>Name:</strong> " . $this->NAME . "<br/>" .
				"<strong>Navigation caption:</strong> " . $this->NAVIGATIONCAPTION . "<br/>" .
				"<strong>Description:</strong> " . $this->DESCRIPTION . "<br/>";
	}
}
?>