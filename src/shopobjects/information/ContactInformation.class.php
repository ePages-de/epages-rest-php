<?php
/**
 * This file represents the contact information class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
/**
 * This is the contact information object of the shop.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @since 0.1.0 Add a timestamp to save the next allowed REST call.
 * @since 0.1.0 Use a default Locale.
 * @since 0.1.1 The object can be echoed.
 * @package ep6
 * @subpackage Shopobjects\Information
 * @see InformationTrait This trait has all information needed objects.
 */
class ContactInformation {

	use InformationTrait;

	/** @var String The REST path for contact information. */
	private static $RESTPATH = "legal/contact-information";

	/** @var String|null The title of the shop, language dependend. */
	private static $TITLE = null;

	/** @var String|null The short description of the shop, language dependend. */
	private static $SHORTDESCRIPTION = null;

	/** @var String|null The company of the shop, language dependend. */
	private static $COMPANY = null;

	/** @var String|null The contact person of the shop, language dependend. */
	private static $CONTACTPERSON = null;

	/** @var String|null The job title of the contact person of the shop, language dependend. */
	private static $CONTACTPERSONJOBTITLE = null;

	/** @var String|null The address of the shop, language dependend. */
	private static $ADDRESS = null;

	/** @var String|null The phone number of the shop, language dependend. */
	private static $PHONE = null;

	/** @var String|null The email address of the shop, language dependend. */
	private static $EMAIL = null;

	/** @var int Timestamp in ms when the next request needs to be done. */
	private static $NEXT_REQUEST_TIMESTAMP = 0;

	/**
	 * Reload the REST information.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use HTTPRequestMethod enum.
	 * @since 0.1.0 Use a default Locale.
	 */
	private static function load() {

		// if request method is blocked
		if (!RESTClient::setRequestMethod(HTTPRequestMethod::GET)) {
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
		if (!InputValidator::isEmptyArrayKey($content, "title")) {
			self::$TITLE = $content["title"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "navigationCaption")) {
			self::$NAVIGATIONCAPTION = $content["navigationCaption"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "shortDescription")) {
			self::$SHORTDESCRIPTION = $content["shortDescription"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "description")) {
			self::$DESCRIPTION = $content["description"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "company")) {
			self::$COMPANY = $content["company"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "contactPerson")) {
			self::$CONTACTPERSON = $content["contactPerson"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "contactPersonJobTitle")) {
			self::$CONTACTPERSONJOBTITLE = $content["contactPersonJobTitle"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "address")) {
			self::$ADDRESS = $content["address"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "phone")) {
			self::$PHONE = $content["phone"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "email")) {
			self::$EMAIL = $content["email"];
		}

		// update timestamp when make the next request
		$timestamp = (int) (microtime(true) * 1000);
		self::$NEXT_REQUEST_TIMESTAMP = $timestamp + RESTClient::$NEXT_RESPONSE_WAIT_TIME;
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
			!InputValidator::isEmpty(self::$TITLE) &&
			!InputValidator::isEmpty(self::$SHORTDESCRIPTION) &&
			!InputValidator::isEmpty(self::$COMPANY) &&
			!InputValidator::isEmpty(self::$CONTACTPERSON) &&
			!InputValidator::isEmpty(self::$CONTACTPERSONJOBTITLE) &&
			!InputValidator::isEmpty(self::$ADDRESS) &&
			!InputValidator::isEmpty(self::$PHONE) &&
			!InputValidator::isEmpty(self::$EMAIL) &&
			self::$NEXT_REQUEST_TIMESTAMP > $timestamp) {
			return;
		}

		self::load($locale);
	}

	/**
	 * This function resets all locales values.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 */
	private static function resetValues() {

		self::$NAME = null;
		self::$TITLE = null;
		self::$NAVIGATIONCAPTION = null;
		self::$SHORTDESCRIPTION = null;
		self::$DESCRIPTION = null;
		self::$COMPANY = null;
		self::$CONTACTPERSON = null;
		self::$CONTACTPERSONJOBTITLE = null;
		self::$ADDRESS = null;
		self::$PHONE = null;
		self::$EMAIL = null;
	}

	/**
	 * Gets the title in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Deprecated because the Locale is everytime the configured Locale.
	 * @api
	 * @deprecated
	 * @return String|null The title in the default localization or null if the default title is not set.
	 */
	public function getDefaultTitle() {

		return self::getTitle();
	}

	/**
	 * Gets the title.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @api
	 * @return String|null The title or null if the localized title is not set.
	 */
	 public function getTitle() {

		self::reload();
		return InputValidator::isEmpty(self::$TITLE) ? null : self::$TITLE;
	}

	/**
	 * Gets the short description in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Deprecated because the Locale is everytime the configured Locale.
	 * @api
	 * @deprecated
	 * @return String|null The short description in the default localization or null if the short description is not set.
	 */
	public function getDefaultShortDescription() {

		return self::getShortDescription();
	}

	/**
	 * Gets the short description.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @api
	 * @return String|null The short description or null if the short description is not set.
	 */
	 public function getShortDescription() {

		self::reload();
		return InputValidator::isEmpty(self::$SHORTDESCRIPTION) ? null : self::$SHORTDESCRIPTION;
	}

	/**
	 * Gets the company in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Deprecated because the Locale is everytime the configured Locale.
	 * @api
	 * @deprecated
	 * @return String|null The company in the default localization or null if the short description is not set.
	 */
	public function getDefaultCompany() {

		return self::getCompany();
	}

	/**
	 * Gets the company.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @api
	 * @return String|null The company or null if the company is net set.
	 */
	 public function getCompany() {

		self::load();
		return InputValidator::isEmpty(self::$COMPANY) ? null : self::$COMPANY;
	}

	/**
	 * Gets the contact person in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Deprecated because the Locale is everytime the configured Locale.
	 * @api
	 * @deprecated
	 * @return String|null The contact person in the default localization or null if the contact person is not set.
	 */
	public function getDefaultContactPerson() {

		return self::getContactPerson();
	}

	/**
	 * Gets the contact person.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @api
	 * @return String|null The contact person or null uf the contact person is not set.
	 */
	 public function getContactPerson() {

		self::reload();
		return InputValidator::isEmpty(self::$CONTACTPERSON) ? null :self::$CONTACTPERSON;
	}

	/**
	 * Gets the job title of the contact person in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Deprecated because the Locale is everytime the configured Locale.
	 * @api
	 * @deprecated
	 * @return String|null The job title of the contact person in the default localization or null if the contact person job title is not set.
	 */
	public function getDefaultContactPersonJobTitle() {

		return self::getContactPersonJobTitle();
	}

	/**
	 * Gets the job title of the contact person.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @api
	 * @return String|null The job title of the contact person or null if the contact person job title is unset.
	 */
	 public function getContactPersonJobTitle() {

		self::reload();
		return InputValidator::isEmpty(self::$CONTACTPERSONJOBTITLE) ? null : self::$CONTACTPERSONJOBTITLE;
	}

	/**
	 * Gets the address in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Deprecated because the Locale is everytime the configured Locale.
	 * @api
	 * @deprecated
	 * @return String|null The address in the default localization or null if the default address is not set.
	 */
	public function getDefaultAddress() {

		return self::getAddress(Locales::getDefault());
	}

	/**
	 * Gets the address.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @api
	 * @return String|null The address or null if the address is unset.
	 */
	 public function getAddress() {

		self::reload();
		return InputValidator::isEmpty(self::$ADDRESS) ? null : self::$ADDRESS;
	}

	/**
	 * Gets the phone number in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Deprecated because the Locale is everytime the configured Locale.
	 * @api
	 * @deprecated
	 * @return String|null The phone number in the default localization or null if the default phone number is unset.
	 */
	public function getDefaultPhone() {

		return self::getPhone();
	}

	/**
	 * Gets the phone number.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @api
	 * @return String|null The phone number or null if the phone number is unset.
	 */
	 public function getPhone() {

		self::reload();
		return InputValidator::isEmpty(self::$PHONE) ? null : self::$PHONE;
	}

	/**
	 * Gets the email in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Deprecated because the Locale is everytime the configured Locale.
	 * @api
	 * @deprecated
	 * @return String|null The email in the default localization or null if the default email address is unset.
	 */
	public function getDefaultEmail() {

		return self::getEmail(Locales::getDefault());
	}

	/**
	 * Gets the email.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @api
	 * @return String|null The email or null if the email address is not set.
	 */
	 public function getEmail() {

		self::reload();
		return InputValidator::isEmpty(self::$EMAIL) ? null : self::$EMAIL;
	}

	/**
	 * Prints the Information object as a string.
	 *
	 * This function returns the setted values of the Information object.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.1
	 * @return String The Information as a string.
	 */
	public function __toString() {


		return "<strong>Name:</strong> " . self::$NAME . "<br/>" .
				"<strong>Navigation caption:</strong> " . self::$NAVIGATIONCAPTION . "<br/>" .
				"<strong>Description:</strong> " . self::$DESCRIPTION . "<br/>" .
				"<strong>Title:</strong> " . self::$NAME . "<br/>" .
				"<strong>Short description:</strong> " . self::$SHORTDESCRIPTION . "<br/>" .
				"<strong>Company:</strong> " . self::$COMPANY . "<br/>" .
				"<strong>Contact person:</strong> " . self::$CONTACTPERSON . "<br/>" .
				"<strong>Contact person job title:</strong> " . self::$CONTACTPERSONJOBTITLE . "<br/>" .
				"<strong>Address:</strong> " . self::$ADDRESS . "<br/>" .
				"<strong>Phone:</strong> " . self::$PHONE . "<br/>" .
				"<strong>Email:</strong> " . self::$EMAIL . "<br/>" .
				"<strong>Next allowed request time:</strong> " . self::$NEXT_REQUEST_TIMESTAMP . "<br/>";
	}
}
?>