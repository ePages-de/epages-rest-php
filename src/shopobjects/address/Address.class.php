<?php
/**
 * This file represents the Address class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.1.3
 */
namespace ep6;
/**
 * This is an Address class to handle addressees from shop.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @package ep6
 * @since 0.1.3
 * @subpackage Shopobjects\Address
 */
class Address {

	use ErrorReporting;

	/** @var String|null The birthday. **/
	private $birthday = null;

	/** @var String|null The city. **/
	private $city = null;

	/** @var String|null The company name. **/
	private $company = null;

	/** @var String|null The country. **/
	private $country = null;

	/** @var String|null The email address. **/
	private $emailAddress = null;

	/** @var String|null The first name. **/
	private $firstName = null;

	/** @var String|null The last name. **/
	private $lastName = null;

	/** @var String|null The salutation. **/
	private $salutation = null;

	/** @var String|null The state. **/
	private $state = null;

	/** @var String|null The street. **/
	private $street = null;

	/** @var String|null The street details. **/
	private $streetDetails = null;

	/** @var String|null The title. **/
	private $title = null;

	/** @var String|null The VAT ID. **/
	private $VATID = null;

	/** @var String|null The zip code. **/
	private $zipCode = null;

	/**
	 * This is the constructor.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String[] $addressParameter The array with information of the adddress.
	 * @since 0.1.3
	 */
	public function __construct($addressParameter) {

		// if parameter is no array
		if (!InputValidator::isArray($addressParameter)) {

			$this->errorSet("D-1");
			Logger::error("ep6\Address\nThe address parameter " . $addressParameter . " is no array.");
			return;
		}

		if (!InputValidator::isEmptyArrayKey($addressParameter, "birthday")) {
			$this->birthday = $addressParameter["birthday"];
		}

		if (!InputValidator::isEmptyArrayKey($addressParameter, "city")) {
			$this->city = $addressParameter["city"];
		}

		if (!InputValidator::isEmptyArrayKey($addressParameter, "company")) {
			$this->company = $addressParameter["company"];
		}

		if (!InputValidator::isEmptyArrayKey($addressParameter, "country")) {
			$this->country = $addressParameter["country"];
		}

		if (!InputValidator::isEmptyArrayKey($addressParameter, "emailAddress")) {
			$this->emailAddress = $addressParameter["emailAddress"];
		}

		if (!InputValidator::isEmptyArrayKey($addressParameter, "firstName")) {
			$this->firstName = $addressParameter["firstName"];
		}

		if (!InputValidator::isEmptyArrayKey($addressParameter, "lastName")) {
			$this->lastName = $addressParameter["lastName"];
		}

		if (!InputValidator::isEmptyArrayKey($addressParameter, "salutation")) {
			$this->salutation = $addressParameter["salutation"];
		}

		if (!InputValidator::isEmptyArrayKey($addressParameter, "state")) {
			$this->state = $addressParameter["state"];
		}

		if (!InputValidator::isEmptyArrayKey($addressParameter, "street")) {
			$this->street = $addressParameter["street"];
		}

		if (!InputValidator::isEmptyArrayKey($addressParameter, "streetDetails")) {
			$this->streetDetails = $addressParameter["streetDetails"];
		}

		if (!InputValidator::isEmptyArrayKey($addressParameter, "title")) {
			$this->title = $addressParameter["title"];
		}

		if (!InputValidator::isEmptyArrayKey($addressParameter, "vatId")) {
			$this->VATID = $addressParameter["vatId"];
		}

		if (!InputValidator::isEmptyArrayKey($addressParameter, "zipCode")) {
			$this->zipCode = $addressParameter["zipCode"];
		}
	}

	/**
	 * Returns the birthday.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The birthday.
	 * @since 0.1.3
	 */
	public function getBirthday() {

		$this->errorReset();
		return $this->birthday;
	}

	/**
	 * Returns the city.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The city.
	 * @since 0.1.3
	 */
	public function getCity() {

		$this->errorReset();
		return $this->city;
	}

	/**
	 * Returns the company.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The company.
	 * @since 0.1.3
	 */
	public function getCompany() {

		$this->errorReset();
		return $this->company;
	}

	/**
	 * Returns the email address.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The email address.
	 * @since 0.1.3
	 */
	public function getEmailAddress() {

		$this->errorReset();
		return $this->emailAddress;
	}

	/**
	 * Returns the first name.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The first name.
	 * @since 0.1.3
	 */
	public function getFirstName() {

		$this->errorReset();
		return $this->firstName;
	}

	/**
	 * Returns the last name.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The last name.
	 * @since 0.1.3
	 */
	public function getLastName() {

		$this->errorReset();
		return $this->lastName;
	}

	/**
	 * Returns the salutation.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The salutation.
	 * @since 0.1.3
	 */
	public function getSalutation() {

		$this->errorReset();
		return $this->salutation;
	}

	/**
	 * Returns the state.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The state.
	 * @since 0.1.3
	 */
	public function getState() {

		$this->errorReset();
		return $this->state;
	}

	/**
	 * Returns the street.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The street.
	 * @since 0.1.3
	 */
	public function getStreet() {

		$this->errorReset();
		return $this->street;
	}

	/**
	 * Returns the street details.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The street details.
	 * @since 0.1.3
	 */
	public function getStreetDetails() {

		$this->errorReset();
		return $this->streetDetails;
	}

	/**
	 * Returns the title.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The title.
	 * @since 0.1.3
	 */
	public function getTitle() {

		$this->errorReset();
		return $this->title;
	}

	/**
	 * Returns the vatid.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The vatid.
	 * @since 0.1.3
	 */
	public function getVATID() {

		$this->errorReset();
		return $this->VATID;
	}

	/**
	 * Returns the zip code.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The zip code.
	 * @since 0.1.3
	 */
	public function getZipCode() {

		$this->errorReset();
		return $this->zipCode;
	}
}
?>