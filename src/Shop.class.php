<?php
/**
 * This is the main class of the ep6client.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
# include framework configuration
require_once(__DIR__ . "/configuration/config.php");
# include helpful objects, all are used in a static way
require_once(__DIR__ . "/util/InputValidator.class.php");
require_once(__DIR__ . "/util/JSONHandler.class.php");
require_once(__DIR__ . "/util/Logger.class.php");
require_once(__DIR__ . "/util/RESTClient.class.php");
# include shopobjects
require_once(__DIR__ . "/shopobjects/Locales.class.php");
require_once(__DIR__ . "/shopobjects/Currencies.class.php");
require_once(__DIR__ . "/shopobjects/information/InformationTrait.class.php");
require_once(__DIR__ . "/shopobjects/information/ContactInformation.class.php");
require_once(__DIR__ . "/shopobjects/information/PrivacyPolicyInformation.class.php");
require_once(__DIR__ . "/shopobjects/information/RightsOfWithdrawalInformation.class.php");
require_once(__DIR__ . "/shopobjects/information/TermsAndConditionInformation.class.php");
require_once(__DIR__ . "/shopobjects/information/ShippingInformation.class.php");
require_once(__DIR__ . "/shopobjects/product/ProductFilter.class.php");
require_once(__DIR__ . "/shopobjects/product/Product.class.php");
require_once(__DIR__ . "/shopobjects/image/Image.class.php");
require_once(__DIR__ . "/shopobjects/price/Price.class.php");
require_once(__DIR__ . "/shopobjects/price/PriceWithQuantity.class.php");

/**
 * This is the epages 6 shop object.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @package ep6
 * @example examples\connectingShop.php Create a new epage 6 shop object and disconnect.
 */
class Shop {

	/** @var ContactInformation|null The contact information object. */
	private static $contactInformation = null;

	/** @var PrivacyPolicyInformation|null The privacy policy information object. */
	private static $privacyPolicyInformation = null;

	/** @var RightsOfWithdrawalInformation|null The rights of withdrawal information object. */
	private static $rightsOfWithdrawalInformation = null;

	/** @var ShippingInformation|null The shipping information object. */
	private static $shippingInformation = null;

	/** @var TermsAndConditionInformation|null The terms and condition information object. */
	private static $termsAndConditionInformation = null;

	/**
	 * The constructor for the shop class.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @param String $host The ePages host to connect.
	 * @param String $shop The refered ePages shop.
	 * @param String $authToken The authentificaton token to connect via REST.
	 * @param boolean $isssl True, if you use SSL, false if not. Default value is true.
	 * @source 2 1 Calls the REST client and connect.
	 */
	function __construct($host, $shop, $authToken, $isssl = true) {

		RESTClient::connect($host, $shop, $authToken, $isssl);
	}

	/**
	 * The destructor for the main class.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @source 2 1 Disconnect the REST client.
	 */
	function __destruct() {

		RESTClient::disconnect();
	}

	/**
	 * Prints the connection status via "FORCE".
	 *
	 * This function will print the current values of the REST client.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 */
	public function printStatus() {

		RESTClient::printStatus();
	}

	/**
	 * Returns the default localization.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @return String|null Default localization or null if the REST call does fail.
	 */
	public function getDefaultLocales() {

		return Locales::getDefault();
	}

	/**
	 * Returns all localizations.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @return mixed[]|null All localizations in an array or null if the REST call does fail.
	 */
	public function getLocales() {

		return Locales::getItems();
	}

	/**
	 * Returns the default currencies.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @return String|null Default currencies or null if the REST call does fail.
	 */
	public function getDefaultCurrencies() {

		return Currencies::getDefault();
	}

	/**
	 * Returns all currencies.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @return mixed[]|null All currencies in an array or null if the REST call will fail.
	 */
	public function getCurrencies() {

		return Currencies::getItems();
	}

	/**
	 * Get the contact information.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @return ContactInformation|null The contact information of the shop or null if the REST call will fail.
	 */
	public function getContactInformation() {

		if (self::$contactInformation==null) {
			self::$contactInformation = new ContactInformation();
		}
		return self::$contactInformation;
	}

	/**
	 * Get the privacy policy information.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @return PrivacyPolicyInformation|null The privacy policy information of the shop or null if the REST call will fail.
	 */
	public function getPrivacyPolicyInformation() {

		if (self::$privacyPolicyInformation==null) {
			self::$privacyPolicyInformation = new PrivacyPolicyInformation();
		}
		return self::$privacyPolicyInformation;
	}

	/**
	 * Get the rights of withdrawal information.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @return RightsOfWithdrawalInformation|null The rights of withdrawal information of the shop or null if the REST call will fail.
	 */
	public function getRightsOfWithdrawalInformation() {

		if (self::$rightsOfWithdrawalInformation==null) {
			self::$rightsOfWithdrawalInformation = new RightsOfWithdrawalInformation();
		}
		return self::$rightsOfWithdrawalInformation;
	}

	/**
	 * Get the shipping information.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @return ShippingInformation|null The shipping information of the shop or null if the REST call fails.
	 */
	public function getShippingInformation() {

		if (self::$shippingInformation==null) {
			self::$shippingInformation = new ShippingInformation();
		}
		return self::$shippingInformation;
	}

	/**
	 * Get the terms and condition information.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @return TermsAndCondiditonInformation The terms and condition information of the shop or null if the REST call fails.
	 */
	public function getTermsAndConditionInformation() {

		if (self::$termsAndConditionInformation==null) {
			self::$termsAndConditionInformation = new TermsAndConditionInformation();
		}
		return self::$termsAndConditionInformation;
	}

	/**
	 * Deletes a product.
	 *
	 * This function try to deletes the product in the shop. It also unset the given Product object.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @param Product The product to delete.
	 * @return boolean True if the deletion works, false if not.
	 */
	public function deleteProduct(&$product) {

		if ($product->delete()) {
			$product = null;
			return true;
		}
		return false;
	}

}
?>