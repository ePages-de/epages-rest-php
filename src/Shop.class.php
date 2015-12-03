<?php
/**
 * This is the main class of the ep6client.
 */
namespace ep6;
# include framework configuration
require_once("src/configuration/config.php");
# include helpful objects, all are used in a static way
require_once("src/util/Logger.class.php");
require_once("src/util/InputValidator.class.php");
require_once("src/util/RESTClient.class.php");
require_once("src/util/JSONHandler.class.php");
# include shopobjects
require_once("src/shopobjects/Locales.class.php");
require_once("src/shopobjects/Currencies.class.php");
require_once("src/shopobjects/information/ContactInformation.class.php");
require_once("src/shopobjects/information/PrivacyPolicyInformation.class.php");
require_once("src/shopobjects/information/RightsOfWithdrawalInformation.class.php");
require_once("src/shopobjects/information/TermsAndConditionInformation.class.php");
require_once("src/shopobjects/information/ShippingInformation.class.php");
require_once("src/shopobjects/product/ProductFilter.class.php");
require_once("src/shopobjects/product/Product.class.php");
require_once("src/shopobjects/image/Image.class.php");
require_once("src/shopobjects/price/Price.class.php");
require_once("src/shopobjects/price/Quantity.class.php");
require_once("src/shopobjects/price/PriceInfo.class.php");

/**
 * This is the epages 6 shop object.
 *
 * Create a new epage 6 Shop object with:
 *   shop = new ep6\Shop(HOSTNAME, SHOP, AUTHTOKEN, ISSSL);
 */
class Shop {
	
	/**
	 * The contact information.
	 */
	private static $contactInformation = null;
	
	/**
	 * The privacy policy information.
	 */
	private static $privacyPolicyInformation = null;
	
	/**
	 * The rights of withdrawal information.
	 */
	private static $rightsOfWithdrawalInformation = null;
	
	/**
	 * The shippinh information.
	 */
	private static $shippinhInformation = null;
	
	/**
	 * The terms and condition information.
	 */
	private static $termsAndConditionInformation = null;

	/**
	 * The constructor for the main class.
	 *
	 * @param String	$host		The ePages host to connect.
	 * @param String	$shop		The refered ePages shop.
	 * @param String	$authToken	The authentificaton token to connect via REST.
	 * @param boolean	$isssl		True, if you use SSL, false if not. Default value is true.
	 */
	function __construct($host, $shop, $authToken, $isssl = true) {

		RESTClient::connect($host, $shop, $authToken, $isssl);
	}

	/**
	 * The destructor for the main class.
	 */
	function __destruct() {

		RESTClient::disconnect();
	}

	/**
	 * Prints the connection status via "FORCE".
	 */
	public function printStatus() {

		RESTClient::printStatus();
	}

	/**
	 * Returns the default localization.
	 *
	 * @return String	Default localization.
	 */
	public function getDefaultLocales() {

		return Locales::getDefault();
	}

	/**
	 * Returns all localizations.
	 *
	 * @return array	All localizations.
	 */
	public function getLocales() {

		return Locales::getItems();
	}

	/**
	 * Returns the default currencies.
	 *
	 * @return String	Default currencies.
	 */
	public function getDefaultCurrencies() {

		return Currencies::getDefault();
	}

	/**
	 * Returns all currencies.
	 *
	 * @return array	All currencies.
	 */
	public function getCurrencies() {

		return Currencies::getItems();
	}

	/**
	 * Get the contact information.
	 *
	 * @return ContactInformation	The contact information of the shop.
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
	 * @return PrivacyPolicyInformation	The privacy policy information of the shop.
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
	 * @return RightsOfWithdrawalInformation	The rights of withdrawal information of the shop.
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
	 * @return ShippingInformation	The shipping information of the shop.
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
	 * @return TermsAndCondiditonInformation	The terms and condition information of the shop.
	 */
	public function getTermsAndConditionInformation() {

		if (self::$termsAndConditionInformation==null) {
			self::$termsAndConditionInformation = new TermsAndConditionInformation();
		}
		return self::$termsAndConditionInformation;
	}

}
?>