<?php
/**
 * This file represents the contact information class.
 */
namespace ep6;
require_once("src/shopobjects/information/InformationTrait.class.php");
/**
 * This is the contact information object of the shop.
 */
class ContactInformation {
	
	use InformationTrait;

	/**
	 * The REST path for contact information.
	 */
	private static $RESTPATH = "legal/contact-information";

	/**
	 * The title of the shop, language dependend.
	 */
	private static $TITLE = array();
	
	/**
	 * The short description of the shop, language dependend.
	 */
	private static $SHORTDESCRIPTION = array();
	
	/**
	 * The company of the shop, language dependend.
	 */
	private static $COMPANY = array();
	
	/**
	 * The contact person of the shop, language dependend.
	 */
	private static $CONTACTPERSON = array();
	
	/**
	 * The job title of the contact person of the shop, language dependend.
	 */
	private static $CONTACTPERSONJOBTITLE = array();
	
	/**
	 * The address of the shop, language dependend.
	 */
	private static $ADDRESS = array();
	
	/**
	 * The phone number of the shop, language dependend.
	 */
	private static $PHONE = array();
	
	/**
	 * The email address of the shop, language dependend.
	 */
	private static $EMAIL = array();
	
	/**
	 * Reload the REST information.
	 *
	 * @param String 	$locale The localization to load the information.
	 */
	private static function load($locale) {

		// if request method is blocked
		if (!RESTClient::setRequestMethod("GET")) {
			return;
		}
	 	
		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
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
		if (array_key_exists("title", $content)) {
			self::$TITLE[$locale] = $content["title"];
		}
		if (array_key_exists("navigationCaption", $content)) {
			self::$NAVIGATIONCAPTION[$locale] = $content["navigationCaption"];
		}
		if (array_key_exists("shortDescription", $content)) {
			self::$SHORTDESCRIPTION[$locale] = $content["shortDescription"];
		}
		if (array_key_exists("description", $content)) {
			self::$DESCRIPTION[$locale] = $content["description"];
		}
		if (array_key_exists("company", $content)) {
			self::$COMPANY[$locale] = $content["company"];
		}
		if (array_key_exists("contactPerson", $content)) {
			self::$CONTACTPERSON[$locale] = $content["contactPerson"];
		}
		if (array_key_exists("contactPersonJobTitle", $content)) {
			self::$CONTACTPERSONJOBTITLE[$locale] = $content["contactPersonJobTitle"];
		}
		if (array_key_exists("address", $content)) {
			self::$ADDRESS[$locale] = $content["address"];
		}
		if (array_key_exists("phone", $content)) {
			self::$PHONE[$locale] = $content["phone"];
		}
		if (array_key_exists("email", $content)) {
			self::$EMAIL[$locale] = $content["email"];
		}
	}

	/**
	 * This function resets all locales values.
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
	 * @return String	The title in the default localization.
	 */
	public function getDefaultTitle() {
		
		// if no default language is visible
		if (empty(Locales::getDefault())) {
			return null;
		}
		
		return self::getTitle(Locales::getDefault());
	}
	
	/**
	 * Gets the title depended on the localization.
	 *
	 * @param String	$locale The locale String.
	 * @return String	The localized title.
	 */
	 public function getTitle($locale) {
	 	
		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}
		
		// if the localiation name is not set
		if (empty(self::$TITLE) || !array_key_exists($locale, self::$TITLE)) {
			self::load($locale);
			// after reload the REST ressource it is empty again.
			if (empty(self::$TITLE) || !array_key_exists($locale, self::$TITLE)) {
				return null;
			}
		}
		
		return self::$TITLE[$locale];
	}
	
	/**
	 * Gets the short description in the default localization.
	 *
	 * @return String	The short description in the default localization.
	 */
	public function getDefaultShortDescription() {
		
		// if no default language is visible
		if (empty(Locales::getDefault())) {
			return null;
		}
		
		return self::getShortDescription(Locales::getDefault());
	}
	
	/**
	 * Gets the short description depended on the localization.
	 *
	 * @param String	$locale The locale String.
	 * @return String	The localized short description.
	 */
	 public function getShortDescription($locale) {
	 	
		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}
		
		// if the localiation name is not set
		if (empty(self::$SHORTDESCRIPTION) || !array_key_exists($locale, self::$SHORTDESCRIPTION)) {
			self::load($locale);
			// after reload the REST ressource it is empty again.
			if (empty(self::$SHORTDESCRIPTION) || !array_key_exists($locale, self::$SHORTDESCRIPTION)) {
				return null;
			}
		}
		
		return self::$SHORTDESCRIPTION[$locale];
	}
	
	/**
	 * Gets the company in the default localization.
	 *
	 * @return String	The company in the default localization.
	 */
	public function getDefaultCompany() {
		
		// if no default language is visible
		if (empty(Locales::getDefault())) {
			return null;
		}
		
		return self::getCompany(Locales::getDefault());
	}
	
	/**
	 * Gets the company depended on the localization.
	 *
	 * @param String	$locale The locale String.
	 * @return String	The localized company.
	 */
	 public function getCompany($locale) {
	 	
		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}
		
		// if the localiation name is not set
		if (empty(self::$COMPANY) || !array_key_exists($locale, self::$COMPANY)) {
			self::load($locale);
			// after reload the REST ressource it is empty again.
			if (empty(self::$COMPANY) || !array_key_exists($locale, self::$COMPANY)) {
				return null;
			}
		}
		
		return self::$COMPANY[$locale];
	}
	
	/**
	 * Gets the contact person in the default localization.
	 *
	 * @return String	The contact person in the default localization.
	 */
	public function getDefaultContactPerson() {
		
		// if no default language is visible
		if (empty(Locales::getDefault())) {
			return null;
		}
		
		return self::getContactPerson(Locales::getDefault());
	}
	
	/**
	 * Gets the contact person depended on the localization.
	 *
	 * @param String	$locale The locale String.
	 * @return String	The localized contact person.
	 */
	 public function getContactPerson($locale) {
	 	
		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}
		
		// if the localiation name is not set
		if (empty(self::$CONTACTPERSON) || !array_key_exists($locale, self::$CONTACTPERSON)) {
			self::load($locale);
			// after reload the REST ressource it is empty again.
			if (empty(self::$CONTACTPERSON) || !array_key_exists($locale, self::$CONTACTPERSON)) {
				return null;
			}
		}
		
		return self::$CONTACTPERSON[$locale];
	}
	
	/**
	 * Gets the job title of the contact person in the default localization.
	 *
	 * @return String	The job title of the contact person in the default localization.
	 */
	public function getDefaultContactPersonJobTitle() {
		
		// if no default language is visible
		if (empty(Locales::getDefault())) {
			return null;
		}
		
		return self::getContactPersonJobTitle(Locales::getDefault());
	}
	
	/**
	 * Gets the job title of the contact person depended on the localization.
	 *
	 * @param String	$locale The locale String.
	 * @return String	The localized job title of the contact person.
	 */
	 public function getContactPersonJobTitle($locale) {
	 	
		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}
		
		// if the localiation name is not set
		if (empty(self::$CONTACTPERSONJOBTITLE) || !array_key_exists($locale, self::$CONTACTPERSONJOBTITLE)) {
			self::load($locale);
			// after reload the REST ressource it is empty again.
			if (empty(self::$CONTACTPERSONJOBTITLE) || !array_key_exists($locale, self::$CONTACTPERSONJOBTITLE)) {
				return null;
			}
		}
		
		return self::$CONTACTPERSONJOBTITLE[$locale];
	}
	
	/**
	 * Gets the address in the default localization.
	 *
	 * @return String	The address in the default localization.
	 */
	public function getDefaultAddress() {
		
		// if no default language is visible
		if (empty(Locales::getDefault())) {
			return null;
		}
		
		return self::getAddress(Locales::getDefault());
	}
	
	/**
	 * Gets the address depended on the localization.
	 *
	 * @param String	$locale The locale String.
	 * @return String	The localized address.
	 */
	 public function getAddress($locale) {
	 	
		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}
		
		// if the localiation name is not set
		if (empty(self::$ADDRESS) || !array_key_exists($locale, self::$ADDRESS)) {
			self::load($locale);
			// after reload the REST ressource it is empty again.
			if (empty(self::$ADDRESS) || !array_key_exists($locale, self::$ADDRESS)) {
				return null;
			}
		}
		
		return self::$ADDRESS[$locale];
	}
	
	/**
	 * Gets the phone number in the default localization.
	 *
	 * @return String	The phone number in the default localization.
	 */
	public function getDefaultPhone() {
		
		// if no default language is visible
		if (empty(Locales::getDefault())) {
			return null;
		}
		
		return self::getPhone(Locales::getDefault());
	}
	
	/**
	 * Gets the phone number depended on the localization.
	 *
	 * @param String	$locale The locale String.
	 * @return String	The localized phone number.
	 */
	 public function getPhone($locale) {
	 	
		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}
		
		// if the localiation name is not set
		if (empty(self::$PHONE) || !array_key_exists($locale, self::$PHONE)) {
			self::load($locale);
			// after reload the REST ressource it is empty again.
			if (empty(self::$PHONE) || !array_key_exists($locale, self::$PHONE)) {
				return null;
			}
		}
		
		return self::$PHONE[$locale];
	}
	
	/**
	 * Gets the email in the default localization.
	 *
	 * @return String	The email in the default localization.
	 */
	public function getDefaultEmail() {
		
		// if no default language is visible
		if (empty(Locales::getDefault())) {
			return null;
		}
		
		return self::getEmail(Locales::getDefault());
	}
	
	/**
	 * Gets the email depended on the localization.
	 *
	 * @param String	$locale The locale String.
	 * @return String	The localized email.
	 */
	 public function getEmail($locale) {
	 	
		// if the locale parameter is not localization string
		if (!InputValidator::isLocale($locale)) {
			return null;
		}
		
		// if the localiation name is not set
		if (empty(self::$EMAIL) || !array_key_exists($locale, self::$EMAIL)) {
			self::load($locale);
			// after reload the REST ressource it is empty again.
			if (empty(self::$EMAIL) || !array_key_exists($locale, self::$EMAIL)) {
				return null;
			}
		}
		
		return self::$EMAIL[$locale];
	}
}
?>