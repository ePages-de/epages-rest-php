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
require_once(__DIR__ . "/util/LogLevel.enum.php");
require_once(__DIR__ . "/util/LogOutput.enum.php");
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
require_once(__DIR__ . "/shopobjects/product/ProductSlideshow.class.php");
require_once(__DIR__ . "/shopobjects/product/ProductAttribute.class.php");
require_once(__DIR__ . "/shopobjects/image/Image.class.php");
require_once(__DIR__ . "/shopobjects/price/Price.class.php");
require_once(__DIR__ . "/shopobjects/price/PriceWithQuantity.class.php");
require_once(__DIR__ . "/shopobjects/price/ProductPriceType.enum.php");
require_once(__DIR__ . "/shopobjects/price/ProductPrice.class.php");
require_once(__DIR__ . "/shopobjects/price/ProductPriceWithQuantity.class.php");

/**
 * This is the epages 6 shop object.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @since 0.1.0 Configure the Locale and Currency to make REST calls.
 * @since 0.1.1 Now the shop can be printed via echo.
 * @since 0.1.1 Save their own shop credentials and use Information objects unstatic.
 * @package ep6
 * @example examples\connectingShop.php Create a new epage 6 shop object and disconnect.
 */
class Shop {

	/** @var ContactInformation|null The contact information object. */
	private $contactInformation = null;

	/** @var PrivacyPolicyInformation|null The privacy policy information object. */
	private $privacyPolicyInformation = null;

	/** @var RightsOfWithdrawalInformation|null The rights of withdrawal information object. */
	private $rightsOfWithdrawalInformation = null;

	/** @var ShippingInformation|null The shipping information object. */
	private $shippingInformation = null;

	/** @var String|null The ePages host to connect. */
	private $host = null;

	/** @var String|null The refered ePages ahop. */
	private $shop = null;

	/** @var String|null The authentification token (access token). */
	private $authToken = null;

	/** @var boolean|null You use https or http? Default is true. */
	private $isssl = true;

	/**
	 * The constructor for the shop class.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.1 Save the own login credentials.
	 * @api
	 * @param String $host The ePages host to connect.
	 * @param String $shop The refered ePages shop.
	 * @param String $authToken The authentificaton token to connect via REST.
	 * @param boolean $isssl True, if you use SSL, false if not. Default value is true.
	 * @source 2 1 Calls the REST client and connect.
	 */
	function __construct($host, $shop, $authToken, $isssl = true) {

		if (!InputValidator::isHost($host) ||
			!InputValidator::isShop($shop)) {
			return;
		}
		
		$this->host = $host;
		$this->shop = $shop;
		$this->authToken = $authToken;
		$this->isssl = $isssl;

		RESTClient::connect($this->host, $this->shop, $this->authToken, $this->isssl);
	}

	/**
	 * Use this shop from now.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.1
	 * @api
	 */
	function useShop() {

		if (InputValidator::isEmpty($this->host) ||
			InputValidator::isEmpty($this->shop)) {
			RESTClient::disconnect($this->host, $this->shop, $this->authToken, $this->isssl);
		}
	}

	/**
	 * The destructor for the main class.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.1 Unset the own shop credentials.
	 * @api
	 * @source 2 1 Disconnect the REST client.
	 */
	function __destruct() {

		$this->host = null;
		$this->shop = null;
		$this->authToken = null;
		$this->isssl = null;
		RESTClient::disconnect();
	}

	/**
	 * Prints the connection status via "FORCE".
	 *
	 * This function will print the current values of the REST client.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.1 Echo the object itself to see all values setted.
	 * @deprecated Echo the object itself to see all values setted.
	 */
	public function printStatus() {

		Logger::force(RESTClient);
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
	 * Returns configured Locale.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @return String|null The Locale which is configured for REST calls.
	 */
	public function getLocale() {

		return Locales::getLocale();
	}

	/**
	 * Set configured Locale.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @param String $locale The new used Locale.
	 * @return boolean True if set the Locale works, false if not.
	 */
	public function setLocale($locale) {

		return Locales::setLocale($locale);
	}

	/**
	 * Returns configured Currency.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @return String|null The Currency which is configured for REST calls.
	 */
	public function getCurrency() {

		return Currencies::getCurrency();
	}

	/**
	 * Set configured Currency.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @param String $locale The new used Locale.
	 * @return boolean True if set the Locale works, false if not.
	 */
	public function setCurrency($currency) {

		return Currencies::setCurrency($currency);
	}

	/**
	 * Get the contact information.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.1 Create an unstatic Information object.
	 * @return ContactInformation|null The contact information of the shop or null if the REST call will fail.
	 */
	public function getContactInformation() {

		if ($this->contactInformation==null) {
			$this->contactInformation = new ContactInformation();
		}
		return $this->contactInformation;
	}

	/**
	 * Get the privacy policy information.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.1 Create an unstatic Information object.
	 * @return PrivacyPolicyInformation|null The privacy policy information of the shop or null if the REST call will fail.
	 */
	public function getPrivacyPolicyInformation() {

		if ($this->privacyPolicyInformation==null) {
			$this->privacyPolicyInformation = new PrivacyPolicyInformation();
		}
		return $this->privacyPolicyInformation;
	}

	/**
	 * Get the rights of withdrawal information.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.1 Create an unstatic Information object.
	 * @return RightsOfWithdrawalInformation|null The rights of withdrawal information of the shop or null if the REST call will fail.
	 */
	public function getRightsOfWithdrawalInformation() {

		if ($this->rightsOfWithdrawalInformation==null) {
			$this->rightsOfWithdrawalInformation = new RightsOfWithdrawalInformation();
		}
		return $this->rightsOfWithdrawalInformation;
	}

	/**
	 * Get the shipping information.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.1 Create an unstatic Information object.
	 * @return ShippingInformation|null The shipping information of the shop or null if the REST call fails.
	 */
	public function getShippingInformation() {

		if ($this->shippingInformation==null) {
			$this->shippingInformation = new ShippingInformation();
		}
		return $this->shippingInformation;
	}

	/**
	 * Get the terms and condition information.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.1 Create an unstatic Information object.
	 * @return TermsAndCondiditonInformation The terms and condition information of the shop or null if the REST call fails.
	 */
	public function getTermsAndConditionInformation() {

		if ($this->termsAndConditionInformation==null) {
			$this->termsAndConditionInformation = new TermsAndConditionInformation();
		}
		return $this->termsAndConditionInformation;
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