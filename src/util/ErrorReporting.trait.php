<?php
/**
 * This file represents the Error Reporting trait.
 *
 * With error reporting you can use useful functions to check if the last call will throw an error.
 * All useful objects in ep6 will inherit this functionality.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.1.2
 */
namespace ep6;
/**
 * This is the functionality to check whether there is an error on last library usage or not.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @package ep6
 * @since 0.1.2
 * @subpackage Util
 */
trait ErrorReporting {

	/** @var array Saves the possibile error messages with the error number. */
	private static $ERRORMESSAGES = array(
		"A-1"	=>	"The address parameter to construct an address is no array.",
		"C-1"	=>	"Respond for Currencies is not valid.",
		"C-2"	=>	"Can't set the currency, because it is not available in the shop.",
		"CI-1"	=>	"Contact information respond is empty.",
		"D-1"	=>	"The date parameter in creating a new Date object is not string.",
		"D-2"	=>	"The given date parameter is no valid date format.",
		"JSONH-1"	=>	"JSON to parse is not valid",
		"JSONH-2"	=>	"Parsing the JSON does not work.",
		"JSONH-3"	=>	"The array to parse into JSON is invalid.",
		"JSONH-4"	=>	"Parsing into JSON does not work.",
		"L-1"	=>	"Respond for Locales is not valid.",
		"L-2"	=>	"Can't set the locale, because it is not available in the shop.",
		"O-1"	=>	"Order construct parameter is wrong.",
		"OF-1"	=>	"Order Filter GET REST respond is empty.",
		"OF-2"	=>	"The REST respond can't be interpreted correctly.",
		"OF-3"	=>	"The order filter attribute in OrderFilter constructor is invalid.",
		"OF-4"	=>	"The order filter page number is invalid.",
		"OF-5"	=>	"The order filter results per page number is invalid.",
		"OF-6"	=>	"Filter parameter in Product Filter are not valid.",
		"P-1"	=>	"Product construct parameter is wrong.",
		"P-2"	=>	"The new attribute value is not valid.",
		"P-3"	=>	"Unknown attribute in the product.",
		"P-4"	=>	"Product attributes REST respond is empty.",
		"P-5"	=>	"The product does not have attributes.",
		"P-6"	=>	"Product stocklevel REST respond is empty.",
		"P-7"	=>	"The product does not have stocklevel.",
		"P-8"	=>	"The step for the new stocklevel is no float.",
		"P-9"	=>	"The requested search keyword does not exist.",
		"PF-1"	=>	"The product filter attribute in ProductFilter constructor is invalid.",
		"PF-2"	=>	"The product filter parameter is unknown.",
		"PF-3"	=>	"The product filter page number is invalid.",
		"PF-4"	=>	"The product filter results per page number is invalid.",
		"PF-5"	=>	"The product filter direction is invalid.",
		"PF-6"	=>	"The product sort parameter is invalid.",
		"PF-7"	=>	"There are already 12 product IDs to filter. To add more delete one.",
		"PF-8"	=>	"Product Filter GET REST respond is empty.",
		"PF-9"	=>	"The REST respond can't be interpreted correctly.",
		"PF-10"	=>	"Filter parameter in Product Filter are not valid.",
		"PM-1"	=>	"The parameter to construct the payment method is no array.",
		"PP-1"	=>	"Product price does belong to any product.",
		"PP-2"	=>	"Product price set function does not have a float parameter.",
		"PP-3"	=>	"Product price set function is not allowed with this product price type.",
		"PS-1"	=>	"Invalid product ID to load attributes.",
		"PS-2"	=>	"Empty response while getting product slideshow.",
		"PS-3"	=>	"The REST response returns an invalid response.",
		"PS-4"	=>	"There are no slideshow images.",
		"PS-5"	=>	"The slideshow image number is unknown.",
		"PS-6"	=>	"The required slideshow image exists but is empty.",
		"RESTC-1"	=>	"Entered host for REST communication is not valid.",
		"RESTC-2"	=>	"Entered shop for connecting is not valid.",
		"RESTC-3"	=>	"Requested locale is not valid.",
		"RESTC-4"	=>	"",
		"RESTC-5"	=>	"POST parameters are not valid.",
		"RESTC-6"	=>	"REST client is not connected.",
		"RESTC-7"	=>	"",
		"RESTC-8"	=>	"Response returns an error code.",
		"RESTC-9"	=>	"HTTP method is not allowed.",
		"RESTC-10"	=>	"New request wait time is not valid.",
		"RESTC-11"	=>	"REST request can't send because of transfering problems.",
		"RESTC-12"	=>	"REST request can't send because of networking error.",
		"RESTC-13"	=>	"There were too many redirects via REST.",
		"S-1"	=>	"Entered host is not valid.",
		"S-2"	=>	"Entered shop is not valid.",
		"S-3"	=>	"No host name configured for using the shop.",
		"S-4"	=>	"No shop name configured for using the shop.",
		"S-5"	=>	"Can't delete product.",
		"SM-1"	=>	"The parameter to construct the shipping method is no array.",
		"TI-1"	=>	"The REST path in information object is empty.",
		"TI-2"	=>	"Information call respond is empty."
		);

	/** @var String|null Saves the last happened error, or null if there is no. */
	private static $ERROR = null;

	/**
	 * Checks whether there was an error or not.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if there was an error, false if not.
	 * @since 0.1.2
	 */
	public static function error() {

		return InputValidator::isEmpty(self::$ERROR) ? false : true;
	}

	/**
	 * This function returns the error message of the last happened error.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String|null The error message of the last happened error or null if there is no error message.
	 * @since 0.1.2
	 */
	public static function errorMessage() {

		return self::$error() ? self::$ERRORMESSAGES[self::errorNumber()] : null;
	}

	/**
	 * This function returns the error number of the last happened error.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String|null The error number of the last happened error, or null if there is no error.
	 * @since 0.1.2
	 */
	public static function errorNumber() {

		return self::$ERROR;
	}

	/**
	 * Sets an occured error.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $errorNumber The error number to set.
	 * @return True if the error number is valid, false if not.
	 * @since 0.1.2
	 */
	private static function errorSet($errorNumber) {

		// if the parameter is empty or not a defined error number.
		if (InputValidator::isEmpty($errorNumber)
			|| InputValidator::isEmptyArrayKey(self::$ERRORMESSAGES, $errorNumber)) {

			return false;
		}

		self::$ERROR = $errorNumber;

		return true;
	}

	/**
	 * Resets the error message.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.2
	 */
	 private static function errorReset() {

		self::$ERROR = null;
	}
}
?>