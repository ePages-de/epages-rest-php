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
 * @package ep6
 * @subpackage Shopobjects\Information
 * @see InformationTrait This trait has all information needed objects.
 */
class ContactInformation {

	use InformationTrait;

	/** @var String The REST path for contact information. */
	private static $RESTPATH = "legal/contact-information";

	/** @var String[] The title of the shop, language dependend. */
	private static $TITLE = array();

	/** @var String[] The short description of the shop, language dependend. */
	private static $SHORTDESCRIPTION = array();

	/** @var String[] The company of the shop, language dependend. */
	private static $COMPANY = array();

	/** @var String[] The contact person of the shop, language dependend. */
	private static $CONTACTPERSON = array();

	/** @var String[] The job title of the contact person of the shop, language dependend. */
	private static $CONTACTPERSONJOBTITLE = array();

	/** @var String[] The address of the shop, language dependend. */
	private static $ADDRESS = array();

	/** @var String[] The phone number of the shop, language dependend. */
	private static $PHONE = array();

	/** @var String[] The email address of the shop, language dependend. */
	private static $EMAIL = array();

	/** @var int Timestamp in ms when the next request needs to be done. */
	private static $NEXT_REQUEST_TIMESTAMP = 0;

	/**
	 * Reload the REST information.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use HTTPRequestMethod enum.
	 * @param String $locale The localization to load the information.
	 */
	private static function load($locale) {

		// if request method is blocked
		if (!RESTClient::setRequestMethod(HTTPRequestMethod::GET) ||
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
		if (!InputValidator::isEmptyArrayKey($content, "title")) {
			self::$TITLE[$locale] = $content["title"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "navigationCaption")) {
			self::$NAVIGATIONCAPTION[$locale] = $content["navigationCaption"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "shortDescription")) {
			self::$SHORTDESCRIPTION[$locale] = $content["shortDescription"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "description")) {
			self::$DESCRIPTION[$locale] = $content["description"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "company")) {
			self::$COMPANY[$locale] = $content["company"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "contactPerson")) {
			self::$CONTACTPERSON[$locale] = $content["contactPerson"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "contactPersonJobTitle")) {
			self::$CONTACTPERSONJOBTITLE[$locale] = $content["contactPersonJobTitle"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "address")) {
			self::$ADDRESS[$locale] = $content["address"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "phone")) {
			self::$PHONE[$locale] = $content["phone"];
		}
		if (!InputValidator::isEmptyArrayKey($content, "email")) {
			self::$EMAIL[$locale] = $content["email"];
		}

		// update timestamp when make the next request
		$timestamp = (int) (microtime(true) * 1000);
		self::$NEXT_REQUEST_TIMESTAMP = $timestamp + RESTClient::NEXT_RESPONSE_WAIT_TIME;
	}

	/**
	 * This function checks whether a reload is needed.
	 *
	 * @param String $locale The localization of the reloadable content.
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 */
	private static function reload($locale) {

		if (!InputValidator::isLocale($locale)) {
			return;
		}

		$timestamp = (int) (microtime(true) * 1000);

		// if the value is empty
		if (!InputValidator::isEmptyArrayKey(self::$NAME, $locale) &&
			!InputValidator::isEmptyArrayKey(self::$NAVIGATIONCAPTION, $locale) &&
			!InputValidator::isEmptyArrayKey(self::$DESCRIPTION, $locale) &&
			!InputValidator::isEmptyArrayKey(self::$TITLE, $locale) &&
			!InputValidator::isEmptyArrayKey(self::$SHORTDESCRIPTION, $locale) &&
			!InputValidator::isEmptyArrayKey(self::$COMPANY, $locale) &&
			!InputValidator::isEmptyArrayKey(self::$CONTACTPERSON, $locale) &&
			!InputValidator::isEmptyArrayKey(self::$CONTACTPERSONJOBTITLE, $locale) &&
			!InputValidator::isEmptyArrayKey(self::$ADDRESS, $locale) &&
			!InputValidator::isEmptyArrayKey(self::$PHONE, $locale) &&
			!InputValidator::isEmptyArrayKey(self::$EMAIL, $locale) &&
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

		self::$NAME = array();
		self::$TITLE = array();
		self::$NAVIGATIONCAPTION = array();
		self::$SHORTDESCRIPTION = array();
		self::$DESCRIPTION = array();
		self::$COMPANY = array();
		self::$CONTACTPERSON = array();
		self::$CONTACTPERSONJOBTITLE = array();
		self::$ADDRESS = array();
		self::$PHONE = array();
		self::$EMAIL = array();
	}

	/**
	 * Gets the title in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return String|null The title in the default localization or null if the default title is not set.
	 */
	public function getDefaultTitle() {

		// if no default language is visible
		if (InputValidator::isEmpty(Locales::getDefault())) {
			return null;
		}

		return self::getTitle(Locales::getDefault());
	}

	/**
	 * Gets the title depended on the localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @api
	 * @param String $locale The locale String.
	 * @return String|null The localized title or null if the localized title is not set.
	 */
	 public function getTitle($locale) {

		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}

		self::reload($locale);

		// after reload the REST ressource it is empty again.
		if (InputValidator::isEmptyArrayKey(self::$TITLE, $locale)) {
			return null;
		}

		return self::$TITLE[$locale];
	}

	/**
	 * Gets the short description in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return String|null The short description in the default localization or null if the short description is not set.
	 */
	public function getDefaultShortDescription() {

		// if no default language is visible
		if (InputValidator::isEmpty(Locales::getDefault())) {
			return null;
		}

		return self::getShortDescription(Locales::getDefault());
	}

	/**
	 * Gets the short description depended on the localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @api
	 * @param String $locale The locale String.
	 * @return String|null The localized short description or null if the short description is not set.
	 */
	 public function getShortDescription($locale) {

		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}

		self::reload($locale);

		// after reload the REST ressource it is empty again.
		if (InputValidator::isEmptyArrayKey(self::$SHORTDESCRIPTION, $locale)) {
			return null;
		}

		return self::$SHORTDESCRIPTION[$locale];
	}

	/**
	 * Gets the company in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return String|null The company in the default localization or null if the short description is not set.
	 */
	public function getDefaultCompany() {

		// if no default language is visible
		if (InputValidator::isEmpty(Locales::getDefault())) {
			return null;
		}

		return self::getCompany(Locales::getDefault());
	}

	/**
	 * Gets the company depended on the localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @api
	 * @param String $locale The locale String.
	 * @return String|null The localized company or null if the company is net set.
	 */
	 public function getCompany($locale) {

		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}

		self::load($locale);

		// after reload the REST ressource it is empty again.
		if (InputValidator::isEmptyArrayKey(self::$COMPANY, $locale)) {
			return null;
		}

		return self::$COMPANY[$locale];
	}

	/**
	 * Gets the contact person in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return String|null The contact person in the default localization or null if the contact person is not set.
	 */
	public function getDefaultContactPerson() {

		// if no default language is visible
		if (InputValidator::isEmpty(Locales::getDefault())) {
			return null;
		}

		return self::getContactPerson(Locales::getDefault());
	}

	/**
	 * Gets the contact person depended on the localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @api
	 * @param String $locale The locale String.
	 * @return String|null The localized contact person or null uf the contact person is not set.
	 */
	 public function getContactPerson($locale) {

		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}

		self::reload($locale);

		// after reload the REST ressource it is empty again.
		if (InputValidator::isEmptyArrayKey(self::$CONTACTPERSON, $locale)) {
			return null;
		}

		return self::$CONTACTPERSON[$locale];
	}

	/**
	 * Gets the job title of the contact person in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return String|null The job title of the contact person in the default localization or null if the contact person job title is not set.
	 */
	public function getDefaultContactPersonJobTitle() {

		// if no default language is visible
		if (InputValidator::isEmpty(Locales::getDefault())) {
			return null;
		}

		return self::getContactPersonJobTitle(Locales::getDefault());
	}

	/**
	 * Gets the job title of the contact person depended on the localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @api
	 * @param String $locale The locale String.
	 * @return String|null The localized job title of the contact person or null if the contact person job title is unset.
	 */
	 public function getContactPersonJobTitle($locale) {

		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}

		self::reload($locale);

		// after reload the REST ressource it is empty again.
		if (InputValidator::isEmptyArrayKey(self::$CONTACTPERSONJOBTITLE, $locale)) {
			return null;
		}

		return self::$CONTACTPERSONJOBTITLE[$locale];
	}

	/**
	 * Gets the address in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return String|null The address in the default localization or null if the default address is not set.
	 */
	public function getDefaultAddress() {

		// if no default language is visible
		if (InputValidator::isEmpty(Locales::getDefault())) {
			return null;
		}

		return self::getAddress(Locales::getDefault());
	}

	/**
	 * Gets the address depended on the localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @api
	 * @param String $locale The locale String.
	 * @return String|null The localized address or null if the address is unset.
	 */
	 public function getAddress($locale) {

		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}

		self::reload($locale);

		// after reload the REST ressource it is empty again.
		if (InputValidator::isEmptyArrayKey(self::$ADDRESS, $locale)) {
			return null;
		}

		return self::$ADDRESS[$locale];
	}

	/**
	 * Gets the phone number in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return String|null The phone number in the default localization or null if the default phone number is unset.
	 */
	public function getDefaultPhone() {

		// if no default language is visible
		if (InputValidator::isEmpty(Locales::getDefault())) {
			return null;
		}

		return self::getPhone(Locales::getDefault());
	}

	/**
	 * Gets the phone number depended on the localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @api
	 * @param String $locale The locale String.
	 * @return String|null The localized phone number or null if the phone number is unset.
	 */
	 public function getPhone($locale) {

		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}

		self::reload($locale);

		// after reload the REST ressource it is empty again.
		if (InputValidator::isEmptyArrayKey(self::$PHONE, $locale)) {
			return null;
		}

		return self::$PHONE[$locale];
	}

	/**
	 * Gets the email in the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return String|null The email in the default localization or null if the default email address is unset.
	 */
	public function getDefaultEmail() {

		// if no default language is visible
		if (InputValidator::isEmpty(Locales::getDefault())) {
			return null;
		}

		return self::getEmail(Locales::getDefault());
	}

	/**
	 * Gets the email depended on the localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @api
	 * @param String $locale The locale String.
	 * @return String|null The localized email or null if the email address is not set.
	 */
	 public function getEmail($locale) {

		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}

		self::reload($locale);

		// after reload the REST ressource it is empty again.
		if (InputValidator::isEmptyArrayKey(self::$EMAIL, $locale)) {
			return null;
		}

		return self::$EMAIL[$locale];
	}
}
?>