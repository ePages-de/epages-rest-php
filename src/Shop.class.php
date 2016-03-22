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
require_once(__DIR__ . "/util/ErrorReporting.trait.php");
require_once(__DIR__ . "/util/HTTPRequestMethod.enum.php");
require_once(__DIR__ . "/util/InputValidator.class.php");
require_once(__DIR__ . "/util/JSONHandler.class.php");
require_once(__DIR__ . "/util/Logger.class.php");
require_once(__DIR__ . "/util/LogLevel.enum.php");
require_once(__DIR__ . "/util/LogOutput.enum.php");
require_once(__DIR__ . "/util/RESTClient.class.php");
# include shopobjects
require_once(__DIR__ . "/shopobjects/Currencies.class.php");
require_once(__DIR__ . "/shopobjects/Locales.class.php");
require_once(__DIR__ . "/shopobjects/image/Image.class.php");
require_once(__DIR__ . "/shopobjects/information/Information.trait.php");
require_once(__DIR__ . "/shopobjects/information/ContactInformation.class.php");
require_once(__DIR__ . "/shopobjects/information/PrivacyPolicyInformation.class.php");
require_once(__DIR__ . "/shopobjects/information/RightsOfWithdrawalInformation.class.php");
require_once(__DIR__ . "/shopobjects/information/ShippingInformation.class.php");
require_once(__DIR__ . "/shopobjects/information/TermsAndConditionInformation.class.php");
require_once(__DIR__ . "/shopobjects/price/Price.class.php");
require_once(__DIR__ . "/shopobjects/price/PriceWithQuantity.class.php");
require_once(__DIR__ . "/shopobjects/price/ProductPrice.class.php");
require_once(__DIR__ . "/shopobjects/price/ProductPriceType.enum.php");
require_once(__DIR__ . "/shopobjects/price/ProductPriceWithQuantity.class.php");
require_once(__DIR__ . "/shopobjects/product/Product.class.php");
require_once(__DIR__ . "/shopobjects/product/ProductAttribute.class.php");
require_once(__DIR__ . "/shopobjects/product/ProductFilter.class.php");
require_once(__DIR__ . "/shopobjects/product/ProductSlideshow.class.php");

/**
 * This is the epages 6 shop object.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @package ep6
 * @since 0.0.0
 * @since 0.1.0 Configure the Locale and Currency to make REST calls.
 * @since 0.1.1 Now the shop can be printed via echo.
 * @since 0.1.1 Save their own shop credentials and use Information objects unstatic.
 * @since 0.1.2 Add error reporting.
 */
class Shop {

	use ErrorReporting;

	/** @var String|null The authentification token (access token). */
	private $authToken = null;

	/** @var ContactInformation|null The contact information object. */
	private $contactInformation = null;

	/** @var String|null The ePages host to connect. */
	private $host = null;

	/** @var boolean|null You use https or http? Default is true. */
	private $isssl = true;

	/** @var PrivacyPolicyInformation|null The privacy policy information object. */
	private $privacyPolicyInformation = null;

	/** @var RightsOfWithdrawalInformation|null The rights of withdrawal information object. */
	private $rightsOfWithdrawalInformation = null;

	/** @var ShippingInformation|null The shipping information object. */
	private $shippingInformation = null;

	/** @var String|null The refered ePages ahop. */
	private $shop = null;

	/** @var TermsAndConditionInformation|null The terms and condition information object. */
	private $termsAndConditionInformation = null;

	/**
	 * The constructor for the shop class.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $host The ePages host to connect.
	 * @param String $shop The refered ePages shop.
	 * @param String $authToken The authentificaton token to connect via REST.
	 * @param boolean $isssl True, if you use SSL, false if not. Default value is true.
	 * @since 0.0.0
	 * @since 0.1.1 Save the own login credentials.
	 * @since 0.1.2 Add error reporting.
	 */
	function __construct($host, $shop, $authToken, $isssl = true) {

		if (!InputValidator::isHost($host) ||
			!InputValidator::isShop($shop)) {

			Logger::warning("ep6\Shop\nHost (" . $host . ") or Shop (" . $shop . ") are not valid.");
			$error = !InputValidator::isHost($host) ? "S-1" : "S-2";
			self::setError($error);
			return;
		}

		$this->host = $host;
		$this->shop = $shop;
		$this->authToken = $authToken;
		$this->isssl = $isssl;

		RESTClient::connect($this->host, $this->shop, $this->authToken, $this->isssl);
	}

	/**
	 * The destructor for the main class.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.1 Unset the own shop credentials.
	 */
	function __destruct() {

		$this->host = null;
		$this->shop = null;
		$this->authToken = null;
		$this->isssl = null;

		RESTClient::disconnect();
	}

	/**
	 * Deletes a product.
	 *
	 * This function try to deletes the product in the shop. It also unset the given Product object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param Product The product to delete.
	 * @return boolean True if the deletion works, false if not.
	 * @since 0.1.0
	 * @since 0.1.2 Add error reporting.
	 */
	public function deleteProduct(&$product) {

		self::errorReset();

		if ($product->delete()) {

			$product = null;
			return true;
		}

		Logger::warning("ep6\Shop\nCan't delete product: " . $product);
		self::errorSet("S-5");

		return false;
	}

	/**
	 * Get the contact information.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return ContactInformation|null The contact information of the shop or null if the REST call will fail.
	 * @since 0.0.0
	 * @since 0.1.1 Create an unstatic Information object.
	 * @since 0.1.2 Add error reporting.
	 */
	public function getContactInformation() {

		self::errorReset();

		if ($this->contactInformation==null) {

			$this->contactInformation = new ContactInformation();
		}

		return $this->contactInformation;
	}

	/**
	 * Returns all currencies.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return mixed[]|null All currencies in an array or null if the REST call will fail.
	 * @since 0.0.0
	 * @since 0.1.2 Add error reporting.
	 */
	public function getCurrencies() {

		self::errorReset();
		return Currencies::getItems();
	}

	/**
	 * Returns configured Currency.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String|null The Currency which is configured for REST calls.
	 * @since 0.1.0
	 * @since 0.1.2 Add error reporting.
	 */
	public function getCurrency() {

		self::errorReset();
		return Currencies::getCurrency();
	}

	/**
	 * Returns the default currencies.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String|null Default currencies or null if the REST call does fail.
	 * @since 0.0.0
	 * @since 0.1.2 Add error reporting.
	 */
	public function getDefaultCurrencies() {

		self::errorReset();
		return Currencies::getDefault();
	}

	/**
	 * Returns the default localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String|null Default localization or null if the REST call does fail.
	 * @since 0.0.0
	 * @since 0.1.2 Add error reporting.
	 */
	public function getDefaultLocales() {

		self::errorReset();
		return Locales::getDefault();
	}

	/**
	 * Returns configured Locale.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String|null The Locale which is configured for REST calls.
	 * @since 0.1.0
	 * @since 0.1.2 Add error reporting.
	 */
	public function getLocale() {

		self::errorReset();
		return Locales::getLocale();
	}

	/**
	 * Returns all localizations.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return mixed[]|null All localizations in an array or null if the REST call does fail.
	 * @since 0.0.0
	 * @since 0.1.2 Add error reporting.
	 */
	public function getLocales() {

		self::errorReset();
		return Locales::getItems();
	}

	/**
	 * Get the privacy policy information.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return PrivacyPolicyInformation|null The privacy policy information of the shop or null if the REST call will fail.
	 * @since 0.0.0
	 * @since 0.1.1 Create an unstatic Information object.
	 * @since 0.1.2 Add error reporting.
	 */
	public function getPrivacyPolicyInformation() {

		self::errorReset();

		if ($this->privacyPolicyInformation==null) {

			$this->privacyPolicyInformation = new PrivacyPolicyInformation();
		}

		return $this->privacyPolicyInformation;
	}

	/**
	 * Get the rights of withdrawal information.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return RightsOfWithdrawalInformation|null The rights of withdrawal information of the shop or null if the REST call will fail.
	 * @since 0.0.0
	 * @since 0.1.1 Create an unstatic Information object.
	 * @since 0.1.2 Add error reporting.
	 */
	public function getRightsOfWithdrawalInformation() {

		self::errorReset();

		if ($this->rightsOfWithdrawalInformation==null) {

			$this->rightsOfWithdrawalInformation = new RightsOfWithdrawalInformation();
		}

		return $this->rightsOfWithdrawalInformation;
	}

	/**
	 * Get the shipping information.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return ShippingInformation|null The shipping information of the shop or null if the REST call fails.
	 * @since 0.0.0
	 * @since 0.1.1 Create an unstatic Information object.
	 * @since 0.1.2 Add error reporting.
	 */
	public function getShippingInformation() {

		self::errorReset();

		if ($this->shippingInformation==null) {

			$this->shippingInformation = new ShippingInformation();
		}

		return $this->shippingInformation;
	}

	/**
	 * Get the terms and condition information.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return TermsAndCondiditonInformation The terms and condition information of the shop or null if the REST call fails.
	 * @since 0.0.0
	 * @since 0.1.1 Create an unstatic Information object.
	 * @since 0.1.2 Add error reporting.
	 */
	public function getTermsAndConditionInformation() {

		self::errorReset();

		if ($this->termsAndConditionInformation==null) {

			$this->termsAndConditionInformation = new TermsAndConditionInformation();
		}

		return $this->termsAndConditionInformation;
	}

	/**
	 * Set configured Currency.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $locale The new used Locale.
	 * @return boolean True if set the Locale works, false if not.
	 * @since 0.1.0
	 * @since 0.1.2 Add error reporting.
	 */
	public function setCurrency($currency) {

		self::errorReset();
		return Currencies::setCurrency($currency);
	}

	/**
	 * Set configured Locale.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $locale The new used Locale.
	 * @return boolean True if set the Locale works, false if not.
	 * @since 0.1.0
	 * @since 0.1.2 Add error reporting.
	 */
	public function setLocale($locale) {

		self::errorReset();
		return Locales::setLocale($locale);
	}

	/**
	 * Use this shop from now.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.1
	 * @since 0.1.2 Add error reporting.
	 */
	public function useShop() {

		self::errorReset();

		if (InputValidator::isEmpty($this->host) ||
			InputValidator::isEmpty($this->shop)) {

			RESTClient::disconnect($this->host, $this->shop, $this->authToken, $this->isssl);
			Logger::warning("ep6\Shop\nCan't use shop, because there no shop host and name configured.");
			$error = InputValidator::isEmpty($this->host) ? "S-3" : "S-4";
			self::setError($error);
		}
		else {

			RESTClient::connect($this->host, $this->shop, $this->authToken, $this->isssl);
		}
	}

}
?>