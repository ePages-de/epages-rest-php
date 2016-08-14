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
	 * Prints the Address object as a string.
	 *
	 * This function returns the setted values of the Address object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The Address as a string.
	 * @since 0.2.0
	 */
	public function __toString() {

		return "<strong>Birthday:</strong> " . $this->birthday . "<br/>" .
				"<strong>City:</strong> " . $this->city . "<br/>" .
				"<strong>Company:</strong> " . $this->company . "<br/>" .
				"<strong>Country:</strong> " . $this->country . "<br/>" .
				"<strong>Email address:</strong> " . $this->emailAddress . "<br/>" .
				"<strong>First name:</strong> " . $this->firstName . "<br/>" .
				"<strong>Last Name:</strong> " . $this->lastName . "<br/>" .
				"<strong>Salutation:</strong> " . $this->salutation . "<br/>" .
				"<strong>State:</strong> " . $this->state . "<br/>" .
				"<strong>Street:</strong> " . $this->street . "<br/>" .
				"<strong>Street Details:</strong> " . $this->streetDetails . "<br/>" .
				"<strong>Title:</strong> " . $this->title . "<br/>" .
				"<strong>VAT ID:</strong> " . $this->VATID . "<br/>" .
				"<strong>Zip Code:</strong> " . $this->zipCode . "<br/>";
	}
	
	/**
	 * Gets the Address object represented as JSON.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The JSON representation of the Address object.
	 * @since 0.2.0
	 */
	public function asArray() {
		return array(
			"company" => $this->company,
			"salutation" => $this->salutation,
			"firstName" => $this->firstName,
			"lastName" => $this->lastName,
			"street" => $this->street,
			"streetDetails" => $this->streetDetails,
			"zipCode" => $this->zipCode,
			"city" => $this->city,
			"state" => $this->state,
			"country" => $this->country,
			"title" => $this->title,
			"vatId" => $this->VATID,
			"birthday" => $this->birthday,
			"emailAddress" => $this->emailAddress
		);
	}

	/**
	 * Returns the birthday.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The birthday.
	 * @since 0.1.3
	 * @since 0.2.0 errorReset in a static way.
	 */
	public function getBirthday() {

		self::errorReset();
		return $this->birthday;
	}

	/**
	 * Returns the city.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The city.
	 * @since 0.1.3
	 * @since 0.2.0 errorReset in a static way.
	 */
	public function getCity() {

		self::errorReset();
		return $this->city;
	}

	/**
	 * Returns the company.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The company.
	 * @since 0.1.3
	 * @since 0.2.0 errorReset in a static way.
	 */
	public function getCompany() {

		self::errorReset();
		return $this->company;
	}

	/**
	 * Returns the email address.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The email address.
	 * @since 0.1.3
	 * @since 0.2.0 errorReset in a static way.
	 */
	public function getEmailAddress() {

		self::errorReset();
		return $this->emailAddress;
	}

	/**
	 * Returns the first name.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The first name.
	 * @since 0.1.3
	 * @since 0.2.0 errorReset in a static way.
	 */
	public function getFirstName() {

		self::errorReset();
		return $this->firstName;
	}

	/**
	 * Returns the last name.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The last name.
	 * @since 0.1.3
	 * @since 0.2.0 errorReset in a static way.
	 */
	public function getLastName() {

		self::errorReset();
		return $this->lastName;
	}

	/**
	 * Returns the salutation.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The salutation.
	 * @since 0.1.3
	 * @since 0.2.0 errorReset in a static way.
	 */
	public function getSalutation() {

		self::errorReset();
		return $this->salutation;
	}

	/**
	 * Returns the state.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The state.
	 * @since 0.1.3
	 * @since 0.2.0 errorReset in a static way.
	 */
	public function getState() {

		self::errorReset();
		return $this->state;
	}

	/**
	 * Returns the street.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The street.
	 * @since 0.1.3
	 * @since 0.2.0 errorReset in a static way.
	 */
	public function getStreet() {

		self::errorReset();
		return $this->street;
	}

	/**
	 * Returns the street details.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The street details.
	 * @since 0.1.3
	 * @since 0.2.0 errorReset in a static way.
	 */
	public function getStreetDetails() {

		self::errorReset();
		return $this->streetDetails;
	}

	/**
	 * Returns the title.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The title.
	 * @since 0.1.3
	 * @since 0.2.0 errorReset in a static way.
	 */
	public function getTitle() {

		self::errorReset();
		return $this->title;
	}

	/**
	 * Returns the vatid.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The vatid.
	 * @since 0.1.3
	 * @since 0.2.0 errorReset in a static way.
	 */
	public function getVATID() {

		self::errorReset();
		return $this->VATID;
	}

	/**
	 * Returns the zip code.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The zip code.
	 * @since 0.1.3
	 * @since 0.2.0 errorReset in a static way.
	 */
	public function getZipCode() {

		self::errorReset();
		return $this->zipCode;
	}

	/**
	 * Sets the birthday.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $birthday The birthday.
	 * @since 0.2.0
	 */
	public function setBirthday($birthday) {

		self::errorReset();
		$this->birthday = $birthday;
	}

	/**
	 * Sets the city.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $city The city.
	 * @since 0.2.0
	 */
	public function setCity($city) {

		self::errorReset();
		$this->city = $city;
	}

	/**
	 * Sets the company.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $company The company.
	 * @since 0.2.0
	 */
	public function setCompany($company) {

		self::errorReset();
		$this->company = $company;
	}

	/**
	 * Sets the email address.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $emailAddress The email address.
	 * @since 0.2.0
	 */
	public function setEmailAddress($emailAddress) {

		self::errorReset();
		$this->emailAddress = $emailAddress;
	}

	/**
	 * Sets the first name.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $firstName The first name.
	 * @since 0.2.0
	 */
	public function setFirstName($firstName) {

		self::errorReset();
		$this->firstName = $firstName;
	}

	/**
	 * Sets the last name.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $lastName The last name.
	 * @since 0.2.0
	 */
	public function setLastName($lastName) {

		self::errorReset();
		$this->lastName = $lastName;
	}

	/**
	 * Sets the salutation.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $salutation The salutation.
	 * @since 0.2.0
	 */
	public function setSalutation($saluation) {

		self::errorReset();
		$this->salutation = $lastName;
	}

	/**
	 * Sets the state.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $state The state.
	 * @since 0.2.0
	 */
	public function setState($state) {

		self::errorReset();
		$this->state = $state;
	}

	/**
	 * Sets the street.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $street The street.
	 * @since 0.2.0
	 */
	public function setStreet($street) {

		self::errorReset();
		$this->street = $street;
	}

	/**
	 * Sets the street details.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $streetDetails The street details.
	 * @since 0.2.0
	 */
	public function setStreetDetails($streetDetails) {

		self::errorReset();
		$this->streetDetails = $streetDetails;
	}

	/**
	 * Sets the title.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $title The title.
	 * @since 0.2.0
	 */
	public function setTitle($title) {

		self::errorReset();
		$this->title = $title;
	}

	/**
	 * Sets the vatid.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $vatId The vatid.
	 * @since 0.2.0
	 */
	public function setVATID($vatId) {

		self::errorReset();
		$this->VATID = $vatId;
	}

	/**
	 * Sets the zip code.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $zipCode The zip code.
	 * @since 0.2.0
	 */
	public function setZipCode($zipCode) {

		self::errorReset();
		$this->zipCode = $zipCode;
	}
}
?>