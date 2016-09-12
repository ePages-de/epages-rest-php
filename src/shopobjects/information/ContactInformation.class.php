<?php
/**
 * This file represents the Contact Information class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
/**
 * This is the Contact Information object of the shop.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @package ep6
 * @see InformationTrait This trait has all information needed objects.
 * @see ErrorReporting This trait gives the error reporting functionality.
 * @since 0.0.0
 * @since 0.1.0 Add a timestamp to save the next allowed REST call.
 * @since 0.1.0 Use a default Locale.
 * @since 0.1.1 The object can be echoed.
 * @since 0.1.1 Unstatic variables.
 * @since 0.1.2 Add error reporting.
 * @subpackage Shopobjects\Information
 */
class ContactInformation {

	use Information, ErrorReporting;

	/** @var String The REST path for Contact Information. */
	const RESTPATH = "legal/contact-information";

	/** @var String|null The address of the shop. */
	private $ADDRESS = null;

	/** @var String|null The company of the shop. */
	private $COMPANY = null;

	/** @var String|null The contact person of the shop. */
	private $CONTACTPERSON = null;

	/** @var String|null The job title of the contact person of the shop. */
	private $CONTACTPERSONJOBTITLE = null;

	/** @var String|null The email address of the shop. */
	private $EMAIL = null;

	/** @var int Timestamp in ms when the next request needs to be done. */
	private $NEXT_REQUEST_TIMESTAMP = 0;

	/** @var String|null The title of the shop. */
	private $TITLE = null;

	/** @var String|null The phone number of the shop. */
	private $PHONE = null;

	/** @var String|null The short description of the shop. */
	private $SHORTDESCRIPTION = null;

	/**
	 * Prints the Contact Information object as a string.
	 *
	 * This function returns the setted values of the Contact Information object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The Contact Information as a string.
	 * @since 0.1.1
	 */
	public function __toString() {

		return "<strong>Name:</strong> " . $this->NAME . "<br/>" .
				"<strong>Navigation caption:</strong> " . $this->NAVIGATIONCAPTION . "<br/>" .
				"<strong>Description:</strong> " . $this->DESCRIPTION . "<br/>" .
				"<strong>Title:</strong> " . $this->NAME . "<br/>" .
				"<strong>Short description:</strong> " . $this->SHORTDESCRIPTION . "<br/>" .
				"<strong>Company:</strong> " . $this->COMPANY . "<br/>" .
				"<strong>Contact person:</strong> " . $this->CONTACTPERSON . "<br/>" .
				"<strong>Contact person job title:</strong> " . $this->CONTACTPERSONJOBTITLE . "<br/>" .
				"<strong>Address:</strong> " . $this->ADDRESS . "<br/>" .
				"<strong>Phone:</strong> " . $this->PHONE . "<br/>" .
				"<strong>Email:</strong> " . $this->EMAIL . "<br/>" .
				"<strong>Next allowed request time:</strong> " . $this->NEXT_REQUEST_TIMESTAMP . "<br/>";
	}

	/**
	 * Gets the address.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String|null The address or null if the address is unset.
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 */
	 public function getAddress() {

		self::errorReset();
		$this->reload();

		return InputValidator::isEmpty($this->ADDRESS) ? null : $this->ADDRESS;
	}

	/**
	 * Gets the company.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String|null The company or null if the company is net set.
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 */
	 public function getCompany() {

		self::errorReset();
		$this->load();

		return InputValidator::isEmpty($this->COMPANY) ? null : $this->COMPANY;
	}

	/**
	 * Gets the contact person.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String|null The contact person or null uf the contact person is not set.
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 */
	 public function getContactPerson() {

		self::errorReset();
		$this->reload();

		return InputValidator::isEmpty($this->CONTACTPERSON) ? null : $this->CONTACTPERSON;
	}

	/**
	 * Gets the job title of the contact person.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String|null The job title of the contact person or null if the contact person job title is unset.
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 */
	 public function getContactPersonJobTitle() {

		self::errorReset();
		$this->reload();

		return InputValidator::isEmpty($this->CONTACTPERSONJOBTITLE) ? null : $this->CONTACTPERSONJOBTITLE;
	}

	/**
	 * Gets the email.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String|null The email or null if the email address is not set.
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 */
	 public function getEmail() {

		self::errorReset();
		$this->reload();

		return InputValidator::isEmpty($this->EMAIL) ? null : $this->EMAIL;
	}

	/**
	 * Gets the phone number.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String|null The phone number or null if the phone number is unset.
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 */
	 public function getPhone() {

		self::errorReset();
		$this->reload();

		return InputValidator::isEmpty($this->PHONE) ? null : $this->PHONE;
	}

	/**
	 * Gets the short description.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String|null The short description or null if the short description is not set.
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 */
	 public function getShortDescription() {

		self::errorReset();
		$this->reload();

		return InputValidator::isEmpty($this->SHORTDESCRIPTION) ? null : $this->SHORTDESCRIPTION;
	}

	/**
	 * Gets the title.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String|null The title or null if the localized title is not set.
	 * @since 0.0.0
	 * @since 0.1.0 Use a reload function.
	 * @since 0.1.0 Use the default Locale.
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 */
	 public function getTitle() {

		self::errorReset();
		$this->reload();

		return InputValidator::isEmpty($this->TITLE) ? null : $this->TITLE;
	}

	/**
	 * Reload the REST information.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use HTTPRequestMethod enum.
	 * @since 0.1.0 Use a default Locale.
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 * @since 0.2.1 Implement REST client fixes.
	 */
	private function load() {

		// if request method is blocked
		if (!RESTClient::setRequestMethod(HTTPRequestMethod::GET)) {

			self::errorSet("RESTC-9");
			return;
		}

		RESTClient::sendWithLocalization(self::RESTPATH, Locales::getLocale());
		$content = RESTClient::getJSONContent();

		// if respond is empty
		if (InputValidator::isEmpty($content)) {

			self::errorSet("CI-1");
			return;
		}

		// reset values
		$this->resetValues();

		if (!InputValidator::isEmptyArrayKey($content, "name")) {

			$this->NAME = $content["name"];
		}

		if (!InputValidator::isEmptyArrayKey($content, "title")) {

			$this->TITLE = $content["title"];
		}

		if (!InputValidator::isEmptyArrayKey($content, "navigationCaption")) {

			$this->NAVIGATIONCAPTION = $content["navigationCaption"];
		}

		if (!InputValidator::isEmptyArrayKey($content, "shortDescription")) {

			$this->SHORTDESCRIPTION = $content["shortDescription"];
		}

		if (!InputValidator::isEmptyArrayKey($content, "description")) {

			$this->DESCRIPTION = $content["description"];
		}

		if (!InputValidator::isEmptyArrayKey($content, "company")) {

			$this->COMPANY = $content["company"];
		}

		if (!InputValidator::isEmptyArrayKey($content, "contactPerson")) {

			$this->CONTACTPERSON = $content["contactPerson"];
		}

		if (!InputValidator::isEmptyArrayKey($content, "contactPersonJobTitle")) {

			$this->CONTACTPERSONJOBTITLE = $content["contactPersonJobTitle"];
		}

		if (!InputValidator::isEmptyArrayKey($content, "address")) {

			$this->ADDRESS = $content["address"];
		}

		if (!InputValidator::isEmptyArrayKey($content, "phone")) {

			$this->PHONE = $content["phone"];
		}

		if (!InputValidator::isEmptyArrayKey($content, "email")) {

			$this->EMAIL = $content["email"];
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
	 */
	private function reload() {

		$timestamp = (int) (microtime(true) * 1000);

		// if the value is empty
		if (!InputValidator::isEmpty($this->NAME) &&
			!InputValidator::isEmpty($this->NAVIGATIONCAPTION) &&
			!InputValidator::isEmpty($this->DESCRIPTION) &&
			!InputValidator::isEmpty($this->TITLE) &&
			!InputValidator::isEmpty($this->SHORTDESCRIPTION) &&
			!InputValidator::isEmpty($this->COMPANY) &&
			!InputValidator::isEmpty($this->CONTACTPERSON) &&
			!InputValidator::isEmpty($this->CONTACTPERSONJOBTITLE) &&
			!InputValidator::isEmpty($this->ADDRESS) &&
			!InputValidator::isEmpty($this->PHONE) &&
			!InputValidator::isEmpty($this->EMAIL) &&
			$this->NEXT_REQUEST_TIMESTAMP > $timestamp) {

			return;
		}

		$this->load();
	}

	/**
	 * This function resets all locales values.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.1 Unstatic every attributes.
	 */
	private function resetValues() {

		$this->NAME = null;
		$this->TITLE = null;
		$this->NAVIGATIONCAPTION = null;
		$this->SHORTDESCRIPTION = null;
		$this->DESCRIPTION = null;
		$this->COMPANY = null;
		$this->CONTACTPERSON = null;
		$this->CONTACTPERSONJOBTITLE = null;
		$this->ADDRESS = null;
		$this->PHONE = null;
		$this->EMAIL = null;
	}
}
?>