<?php
/**
 * This file represents the Product class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
/**
 * This is the product class for a Product in the shop.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @package ep6
 * @since 0.0.0
 * @since 0.1.0 Add price information.
 * @since 0.1.0 Function to delete itself.
 * @since 0.1.0 Delete different Locales.
 * @since 0.1.0 Implement Slideshow functionality.
 * @since 0.1.0 Implement attribute functionality.
 * @since 0.1.0 Implement stock level functionality.
 * @since 0.1.1 This object can be printed with echo.
 * @since 0.1.1 Don't use locale parameter in get functions.
 * @since 0.1.1 Unstatic every attributes.
 * @since 0.1.2 Insert the Setters.
 * @since 0.1.2 Add error reporting.
 * @since 0.2.0 Use ProductPrice for the price.
 * @since 0.2.0 Add missing Product attributes.
 * @subpackage Shopobjects\Product
 */
class Product {

	use ErrorReporting;

	/** @var String The REST path to the product ressource. */
	const RESTPATH = "products";

	/** @var String The REST path to the product ressource. */
	const RESTPATH_ATTRIBUTES = "custom-attributes";

	/** @var String The REST path to the product ressource. */
	const RESTPATH_STOCKLEVEL = "stock-level";

	/** @var ProductAttribute[] This array saves all the attributes. */
	private $attributes = array();

	/** @var ProductAvailibility|null Is this product availible or not. */
	private $availibility = null;

	/** @var String|null The text of availibility. */
	private $availibilityText = null;

	/** @var ProductPrice|null Here the base price is saved. */
	private $basePrice = null;

	/** @var ProductPrice|null Here the deposit price is saved. */
	private $depositPrice = null;

	/** @var String|null The description. */
	private $description = null;
	
	/** @var String|null Information about how long the delivery period is. */
	private $deliveryPeriod = null;
	
	/** @var Quantity|null The quantity how many weight has the product. */
	private $deliveryWeight = null;

	/** @var String|null Space to save the EAN. */
	private $ean = null;

	/** @var ProductPrice|null Here the eco participation price is saved. */
	private $ecoParticipationPrice = null;

	/** @var String|null This is the energy label. */
	private $energyLabelsString = null;

	/** @var URL|null The URL to the energy label file. */
	private $energyLabelSourceFile = null;

	/** @var String|null This are the essential features. */
	private $essentialFeatures = null;

	/** @var boolean Is this product for sale? */
	private $forSale = true;

	/** @var String|null Here the manufacturer name is saved. */
	private $manufacturer = null;

	/** @var ProductPrice|null Here the manufacturer price is saved. */
	private $manufacturerPrice = null;

	/** @var String|null The name of the product. */
	private $name = null;

	/** @var Images[] This are the images in the four different possibilities. */
	private $images = array();

	/** @var ProductPrice|null Here the price is saved. */
	private $price = null;

	/** @var String|null The product ID. */
	private $productID = null;

	/** @var String|null The product number. */
	private $productNumber = null;

	/** @var String[] Array to save the setted keywords. */
	private $searchKeywords = array();

	/** @var String|null The short description. */
	private $shortDescription = null;

	/** @var ProductSlideshow|null This object saves the slideshow. */
	private $slideshow = null;

	/** @var boolean Is this product a special offer? */
	private $specialOffer = false;

	/** @var float|null Space to save the stocklevel. */
	private $stockLevel = null;

	/** @var String|null Space to save the UPC. */
	private $upc = null;
	
	/** @var boolean Is this product visible. */
	private $visible = true;

	/** @var ProductPrice|null Here the price with deposit is saved. */
	private $withDepositPrice = null;

	/** @var int Timestamp in ms when the next request needs to be done. */
	private $NEXT_REQUEST_TIMESTAMP = 0;

	/**
	 * This is the constructor of the Product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param mixed[]|String $productParameter The product to create as array or product ID.
	 * @since 0.0.0
	 * @since 0.1.0 Add price information.
	 * @since 0.1.0 Use a default Locale.
	 * @since 0.1.1 Dont use the locale parameter in calling the product price attribute.
	 * @since 0.1.2 Exclude the REST request to the load() function.
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Product creation with product ID as parameter.
	 */
	public function __construct($productParameter) {

		if (!InputValidator::isString($productParameter) &&
			!InputValidator::isArray($productParameter)) {

			self::errorSet("P-1");
			Logger::warning("ep6\Product\nProduct parameter " . $productParameter . " to create product is invalid.");
			return;
		}

		if (InputValidator::isArray($productParameter)) {
			$this->parseData($productParameter);
		}
		else {
			$this->productId = $productParameter;
			$this->reload();
		}
	}

	/**
	 * Prints the Product object as a string.
	 *
	 * This function returns the setted values of the Product object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The Product as a string.
	 * @since 0.1.1
	 * @since 0.2.0 Add new attributes.
	 */
	public function __toString() {

		return "<strong>Product ID:</strong> " . $this->productID . "<br/>" .
				"<strong>Name:</strong> " . $this->name . "<br/>" .
				"<strong>Short description:</strong> " . $this->shortDescription . "<br/>" .
				"<strong>Description:</strong> " . $this->description . "<br/>" .
				"<strong>For sale:</strong> " . $this->forSale . "<br/>" .
				"<strong>Special offer:</strong> " . $this->specialOffer . "<br/>" .
				"<strong>Availibility text:</strong> " . $this->availibilityText . "<br/>" .
				"<strong>Images:</strong> " . print_r($this->images) . "<br/>" .
				"<strong>Price:</strong> " . $this->price . "<br/>" .
				"<strong>Deposit price:</strong> " . $this->depositPrice . "<br/>" .
				"<strong>Ecoparticipation price:</strong> " . $this->ecoParticipationPrice . "<br/>" .
				"<strong>Visible:</strong> " . $this->visible . "<br/>" .
				"<strong>Delivery Period:</strong> " . $this->deliveryPeriod . "<br/>" .
				"<strong>Delivery Weight:</strong> " . $this->deliveryWeight . "<br/>" .
				"<strong>Availibility:</strong> " . $this->availibility . "<br/>" .
				"<strong>Energy Label Source File:</strong> " . $this->energyLabelSourceFile . "<br/>";
	}

	/**
	 * Decreases the stock level.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param float $step The value the stock level should be decreased, default value is 1.
	 * @return float|null The new stock level of the product.
	 * @since 0.1.0
	 * @since 0.1.2 Add error reporting.
	 */
	public function decreaseStockLevel($step = 1.0) {

		self::errorReset();

		$this->getStockLevel();

		// cast int to float
		if (InputValidator::isInt($step)) {

			$step = (float) $step;
		}

		if (!InputValidator::isRangedFloat($step, 0.0)) {

			return $this->stockLevel;
		}

		$this->changeStockLevel((float) $step * -1);

		return $this->stockLevel;
	}

	/**
	 * Deletes itself.
	 *
	 * Dont use this function. To delete a product its better to use $shop->deleteProduct($product).
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 * @return boolean True if the deletion was successful, false if not.
	 */
	public function delete() {

		// if request method is blocked
		if (!RESTClient::setRequestMethod(HTTPRequestMethod::DELETE)) {

			self::errorSet("RESTC-9");
			return false;
		}

		RESTClient::send(self::RESTPATH . "/" . $this->productID);

		return true;
	}

	/**
	 * Returns the product attributes.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param int $key The number of product attribute to get.
	 * @return ProductAttributes|null Gets the required product attributes.
	 * @since 0.1.0
	 * @since 0.1.2 Add error reporting.
	 */
	public function getAttribute($key) {

		self::errorReset();
		$timestamp = (int) (microtime(true) * 1000);

		// if the attribute is not loaded until now
		if (InputValidator::isEmptyArrayKey($this->attributes, $key) ||
			$this->NEXT_REQUEST_TIMESTAMP < $timestamp) {

			$this->loadAttributes();
		}

		if (InputValidator::isEmptyArrayKey($this->attributes, $key)) {

			self::errorSet("P-3");
			Logger::warning("ep6\Product\nThe attribute " . $key . " is not defined in the product.");
			return null;
		}

		return $this->attributes[$key];
	}

	/**
	 * Returns the product attributes.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return ProductAttributes[] Gets the product attributes in an array.
	 * @since 0.1.0
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 */
	public function getAttributes() {

		self::errorReset();
		$timestamp = (int) (microtime(true) * 1000);

		// if the attribute is not loaded until now
		if (InputValidator::isEmptyArray($this->attributes) ||
			$this->NEXT_REQUEST_TIMESTAMP < $timestamp) {

			$this->loadAttributes();
		}

		return $this->attributes;
	}

	/**
	 * Returns the availibility.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return ProductAvailibility The status of the availibility.
	 * @since 0.2.0
	 */
	public function getAvailibility() {

		self::errorReset();
		$this->reload();
		return $this->availibility;
	}

	/**
	 * Returns the availibility text.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The availibility text.
	 * @since 0.0.0
	 * @since 0.1.0 Use a default Locale.
	 * @since 0.1.1 Fix to call function without locale parameter.
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Add reload feature.
	 */
	public function getAvailibilityText() {

		self::errorReset();
		$this->reload();
		return $this->availibilityText;
	}

	/**
	 * Returns the base price.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return ProductPrice Gets the base price.
	 * @since 0.1.0
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Add reload feature.
	 */
	public function getBasePrice() {

		self::errorReset();
		$this->reload();
		return $this->basePrice;
	}

	/**
	 * Returns the delivery period.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String Gets the delivery period information.
	 * @since 0.2.0
	 */
	public function getDeliveryPeriod() {

		self::errorReset();
		$this->reload();
		return $this->deliveryPeriod;
	}

	/**
	 * Returns the delivery weight.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Quantity Gets the delivery weight information.
	 * @since 0.2.0
	 */
	public function getDeliveryWeight() {

		self::errorReset();
		$this->reload();
		return $this->deliveryWeight;
	}

	/**
	 * Returns the deposit price.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return ProductPrice Gets the deposit price.
	 * @since 0.1.0
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Add reload feature.
	 */
	public function getDepositPrice() {

		self::errorReset();
		$this->reload();
		return $this->depositPrice;
	}

	/**
	 * Returns the description.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The description.
	 * @since 0.0.0
	 * @since 0.1.0 Use a default Locale.
	 * @since 0.1.1 Fix to call function without locale parameter.
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Add reload feature.
	 */
	public function getDescription() {

		self::errorReset();
		$this->reload();
		return $this->description;
	}

	/**
	 * Returns the EAN.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String Gets the EAN.
	 * @since 0.1.2
	 * @since 0.1.3 Add reload feature.
	 */
	public function getEAN() {

		self::errorReset();
		$this->reload();
		return $this->EAN;
	}

	/**
	 * Returns the eco participation price.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return ProductPrice Gets the eco participation price.
	 * @since 0.1.0
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Add reload feature.
	 */
	public function getEcoParticipationPrice() {

		self::errorReset();
		$this->reload();
		return $this->ecoParticipationPrice;
	}

	/**
	 * Returns the energy labels string.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String Gets the energy labels string.
	 * @since 0.1.2
	 * @since 0.1.3 Add reload feature.
	 */
	public function getEnergyLabelsString() {

		self::errorReset();
		$this->reload();
		return $this->energyLabelsString;
	}

	/**
	 * Returns the energy label source file object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return URL Gets the energy label source file URL object.
	 * @since 0.2.0
	 */
	public function getEnergyLabelSourceFile() {

		self::errorReset();
		$this->reload();
		return $this->energyLabelsString;
	}

	/**
	 * Returns the essential features.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String Gets the essential features.
	 * @since 0.1.2
	 * @since 0.1.3 Add reload feature.
	 */
	public function getEssentialFeatures() {

		self::errorReset();
		$this->reload();
		return $this->essentialFeatures;
	}

	/**
	 * Returns the hot deal image.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Image The hot deal image.
	 * @since 0.0.0
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Add reload feature.
	 */
	public function getHotDealImage() {

		self::errorReset();
		$this->reload();
		return !InputValidator::isEmptyArrayKey($this->images, "HotDeal") ? $this->images["HotDeal"] : null;
	}

	/**
	 * Returns the product id.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The product id.
	 * @since 0.0.0
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Add reload feature.
	 */
	public function getID() {

		self::errorReset();
		$this->reload();
		return $this->productID;
	}

	/**
	 * Returns the large image.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Image The large image.
	 * @since 0.0.0
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Add reload feature.
	 */
	public function getLargeImage() {

		self::errorReset();
		$this->reload();
		return !InputValidator::isEmptyArrayKey($this->images, "Large") ? $this->images["Large"] : null;
	}

	/**
	 * Returns the name of the manufacturer.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String Gets the name of the manufacturer.
	 * @since 0.1.2
	 * @since 0.1.3 Add reload feature.
	 */
	public function getManufacturer() {

		self::errorReset();
		$this->reload();
		return $this->manufacturer;
	}

	/**
	 * Returns the manufacturer price.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return ProductPrice Gets the manufacturer price.
	 * @since 0.1.0
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Add reload feature.
	 */
	public function getManufacturerPrice() {

		self::errorReset();
		$this->reload();
		return $this->manufacturerPrice;
	}

	/**
	 * Returns the medium image.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Image The medium image.
	 * @since 0.0.0
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Add reload feature.
	 */
	public function getMediumImage() {

		self::errorReset();
		$this->reload();
		return !InputValidator::isEmptyArrayKey($this->images, "Medium") ? $this->images["Medium"] : null;
	}

	/**
	 * Returns the name.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The name.
	 * @since 0.0.0
	 * @since 0.1.0 Use a default Locale.
	 * @since 0.1.1 Fix to call function without locale parameter.
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Add reload feature.
	 */
	public function getName() {

		self::errorReset();
		$this->reload();
		return $this->name;
	}

	/**
	 * Returns the amount of search keywords.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return int Gets the number of search keywords.
	 * @since 0.1.2
	 * @since 0.1.3 Add reload feature.
	 */
	public function getNumberOfSearchKeywords() {

		self::errorReset();
		$this->reload();
		return sizeof($this->searchKeywords);
	}

	/**
	 * Returns the product price with quantity.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return ProductPrice Gets the product price with quantity.
	 * @since 0.1.0
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Add reload feature.
	 * @since 0.2.0 Return type ProductPrice.
	 */
	public function getPrice() {

		self::errorReset();
		$this->reload();
		return $this->price;
	}

	/**
	 * Returns the product number.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String Gets the product number.
	 * @since 0.1.2
	 * @since 0.1.3 Add reload feature.
	 */
	public function getProductNumber() {

		self::errorReset();
		$this->reload();
		return $this->productNumber;
	}

	/**
	 * Returns the search keyword.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String Gets a specific search keyword,, starting with 1.
	 * @param int $number The number of the search keyword which is required.
	 * @since 0.1.2
	 * @since 0.1.3 Add reload feature.
	 */
	public function getSearchKeyword($number) {

		self::errorReset();
		$this->reload();
		if (!InputValidator::isRangedInt($number, 1, $this->getNumberOfSearchKeywords())) {
			self::errorSet("P-9");
			return;
		}
		return $this->searchKeywords[$number - 1];
	}

	/**
	 * Returns the short description.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The short description.
	 * @since 0.0.0
	 * @since 0.1.0 Use a default Locale.
	 * @since 0.1.1 Fix to call function without locale parameter.
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Add reload feature.
	 */
	public function getShortDescription() {

		self::errorReset();
		$this->reload();
		return $this->shortDescription;
	}

	/**
	 * Returns the slideshow.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return ProductSlideshow Gets the product slideshow.
	 * @since 0.1.0
	 * @since 0.1.1 Slideshows are not reloadable since now.
	 * @since 0.1.2 Add error reporting.
	 */
	public function getSlideshow() {

		self::errorReset();

		// if the slideshow is not loaded until now
		if (InputValidator::isEmpty($this->slideshow) ||
			$this->NEXT_REQUEST_TIMESTAMP < $timestamp) {

			$this->slideshow = new ProductSlideshow($this->productID);
		}

		return $this->slideshow;
	}

	/**
	 * Returns the small image.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Image The small image.
	 * @since 0.0.0
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Add reload feature.
	 */
	public function getSmallImage() {

		self::errorReset();
		$this->reload();
		return !InputValidator::isEmptyArrayKey($this->images, "Small") ? $this->images["Small"] : null;
	}

	/**
	 * Returns the stock level.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return float|null The stock level of the product.
	 * @since 0.1.0
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 */
	public function getStockLevel() {

		self::errorReset();

		$timestamp = (int) (microtime(true) * 1000);

		// if the attribute is not loaded until now
		if (InputValidator::isEmpty($this->stockLevel) ||
			$this->NEXT_REQUEST_TIMESTAMP < $timestamp) {

			$this->loadStockLevel();
		}

		return $this->stockLevel;
	}

	/**
	 * Returns the UPC.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String Gets the UPC.
	 * @since 0.1.2
	 * @since 0.1.3 Add reload feature.
	 */
	public function getUPC() {

		self::errorReset();
		$this->reload();
		return $this->UPC;
	}

	/**
	 * Returns the with deposit price.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return ProductPrice Gets the with deposit price.
	 * @since 0.1.0
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Add reload feature.
	 */
	public function getWithDepositPrice() {

		self::errorReset();
		$this->reload();
		return $this->withDepositPrice;
	}

	/**
	 * Increases the stock level.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param float $step The value the stock level should be increased, default value is 1.
	 * @return float|null The new stock level of the product.
	 * @since 0.1.0
	 * @since 0.1.2 Add error reporting.
	 */
	public function increaseStockLevel($step = 1.0) {

		self::errorReset();

		$this->getStockLevel();

		// cast int to float
		if (InputValidator::isInt($step)) {

			$step = (float) $step;
		}

		if (!InputValidator::isRangedFloat($step, 0.0)) {

			return $this->stockLevel;
		}

		$this->changeStockLevel((float) $step);

		return $this->stockLevel;
	}

	/**
	 * Returns true if it is for sale.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if it is for sale, false if not.
	 * @since 0.0.0
	 * @since 0.1.2 Add error reporting.
	 */
	public function isForSale() {

		self::errorReset();
		$this->reload();
		return $this->forSale;
	}

	/**
	 * Returns true if it is a special offer.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if it is a special offer, false if not.
	 * @since 0.0.0
	 * @since 0.1.2 Add error reporting.
	 */
	public function isSpecialOffer() {

		self::errorReset();
		$this->reload();
		return $this->specialOffer;
	}

	/**
	 * Returns true if the product is visible.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if it is visible, false if not.
	 * @since 0.2.0
	 */
	public function isVisible() {

		self::errorReset();
		$this->reload();
		return $this->visible;
	}

	/**
	 * Sets the description of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $description The new description.
	 * @since 0.1.2
	 * @since 0.2.0 Fix function call.
	 */
	public function setDescription($description) {

		self::errorReset();

		$this->setAttribute("/description", $dscription);
	}

	/**
	 * Sets the EAN of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $ean The new EAN.
	 * @since 0.1.2
	 * @since 0.2.0 Fix function call.
	 */
	public function setEAN($ean) {

		self::errorReset();

		$this->setAttribute("/ean", $ean);
	}

	/**
	 * Sets the energy labels string of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $energyLabelsString The new energy labels string.
	 * @since 0.1.2
	 * @since 0.2.0 Fix function call.
	 */
	public function setEnergyLabelsString($energyLabelsString) {

		self::errorReset();

		$this->setAttribute("/energyLabelsString", $energyLabelsString);
	}

	/**
	 * Sets the essential features of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $essentialFeatures The new essential features.
	 * @since 0.1.2
	 * @since 0.2.0 Fix function call.
	 */
	public function setEssentialFeatures($essentialFeatures) {

		self::errorReset();

		$this->setAttribute("/essentialFeatures", $essentialFeatures);
	}

	/**
	 * Sets the manufacturer of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $manufacturer The new manufacturer.
	 * @since 0.1.2
	 * @since 0.2.0 Fix function call.
	 */
	public function setManufacturer($manufacturer) {

		self::errorReset();

		$this->setAttribute("/manufacturer", $manufacturer);
	}

	/**
	 * Sets the name of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $name The new name.
	 * @since 0.1.2
	 * @since 0.2.0 Fix function call.
	 */
	public function setName($name) {

		self::errorReset();

		$this->setAttribute("/name", $name);
	}

	/**
	 * Sets the product number of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $number The new product number.
	 * @since 0.1.2
	 * @since 0.2.0 Fix function call.
	 */
	public function setNumber($number) {

		self::errorReset();

		$this->setAttribute("/productNumber", $number);
	}

	/**
	 * Sets the search keywords of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $searchKeywords The new $search keywords.
	 * @since 0.1.2
	 * @since 0.2.0 Fix function call.
	 */
	public function setSearchKeywords($searchKeywords) {

		self::errorReset();

		$this->setAttribute("/searchKeywords", $searchKeywords);
	}

	/**
	 * Sets the short description of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $shortDescription The new short description.
	 * @since 0.1.2
	 * @since 0.2.0 Fix function call.
	 */
	public function setShortDescription($shortDescription) {

		self::errorReset();

		$this->setAttribute("/shortDescription", $shortDescription);
	}

	/**
	 * Sets the UPC of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $upc The new UPC.
	 * @since 0.1.2
	 * @since 0.2.0 Fix function call.
	 */
	public function setUPC($upc) {

		self::errorReset();

		$this->setAttribute("/upc", $upc);
	}

	/**
	 * Deletes the description value of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.2
	 * @since 0.2.0 Fix function call.
	 */
	public function unsetDescription() {

		self::errorReset();

		$this->unsetAttribute("/description");
	}

	/**
	 * Deletes the EAN value of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.2
	 */
	public function unsetEAN() {

		self::errorReset();

		$this->unsetAttribute("/ean");
	}

	/**
	 * Deletes the energy labels string value of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.2
	 */
	public function unsetEnergyLabelsString() {

		self::errorReset();

		$this->unsetAttribute("/energyLabelsString");
	}

	/**
	 * Deletes the essential features value of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.2
	 */
	public function unsetEssentialFeatures() {

		self::errorReset();

		$this->unsetAttribute("/essentialFeatures");
	}

	/**
	 * Deletes the manufacturer value of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.2
	 */
	public function unsetManufacturer() {

		self::errorReset();

		$this->unsetAttribute("/manufacturer");
	}

	/**
	 * Deletes the name value of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.2
	 */
	public function unsetName() {

		self::errorReset();

		$this->unsetAttribute("/name");
	}

	/**
	 * Deletes the search keywords of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.2
	 */
	public function unsetSearchKeywords() {

		self::errorReset();

		$this->unsetAttribute("/searchKeywords");
	}

	/**
	 * Deletes the short description value of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.2
	 */
	public function unsetShortDescription() {

		self::errorReset();

		$this->unsetAttribute("/shortDescription");
	}

	/**
	 * Deletes the UPC value of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.2
	 */
	public function unsetUPC() {

		self::errorReset();

		$this->unsetAttribute("/upc");
	}

	/**
	 * Change the stock level with a step.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 * @param float $step The step to change.
	 */
	private function changeStockLevel($step) {

		// if parameter is wrong or GET is blocked
		if (!RESTClient::setRequestMethod(HTTPRequestMethod::PUT)) {

			self::errorSet("RESTC-9");
			return;
		}

		if (!InputValidator::isFloat($step)) {

			self::errorSet("P-8");
			Logger::error("The " . $step . " step to change the stocklevel is no float.");
			return;
		}

		$postfields = array("changeStocklevel" => $step);
		$content = RESTClient::send(self::RESTPATH . "/" . $this->productID . "/" .  self::RESTPATH_STOCKLEVEL, $postfields);

		// if respond is empty
		if (InputValidator::isEmpty($content)) {

			self::errorSet("P-6");
			return;
		}

		// if there are no items
		if (InputValidator::isEmptyArrayKey($content, "stocklevel")) {

			self::errorSet("P-7");
		    Logger::error("Respond for " . self::RESTPATH . "/" . $this->productID . "/" .  self::RESTPATH_STOCKLEVEL . " can not be interpreted.");
			return;
		}

		$this->stockLevel = (float) $content["stocklevel"];

		// update timestamp when make the next request
		$timestamp = (int) (microtime(true) * 1000);
		$this->NEXT_REQUEST_TIMESTAMP = $timestamp + RESTClient::$NEXT_RESPONSE_WAIT_TIME;
	}

	/**
	 * Loads the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.2
	 * @since 0.1.3 Remove data parsing into correct function.
	 */
	private function load() {

		// if parameter is wrong or GET is blocked
		if (!RESTClient::setRequestMethod(HTTPRequestMethod::GET)) {

			self::errorSet("RESTC-9");
			return;
		}

		$content = RESTClient::sendWithLocalization(self::RESTPATH . "/" . $this->productID, Locales::getLocale());

		// if respond is empty
		if (InputValidator::isEmpty($content)) {

			self::errorSet("PF-8");
			return;
		}

		$this->parseData($content);

		// update timestamp when make the next request
		$timestamp = (int) (microtime(true) * 1000);
		$this->NEXT_REQUEST_TIMESTAMP = $timestamp + RESTClient::$NEXT_RESPONSE_WAIT_TIME;
	}

	/**
	 * Loads the product attributes.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 */
	private function loadAttributes() {

		// if parameter is wrong or GET is blocked
		if (!RESTClient::setRequestMethod(HTTPRequestMethod::GET)) {

			self::errorSet("RESTC-9");
			return;
		}

		$content = RESTClient::send(self::RESTPATH . "/" . $this->productID . "/" .  self::RESTPATH_ATTRIBUTES);

		// if respond is empty
		if (InputValidator::isEmpty($content)) {

			self::errorSet("P-4");
			return;
		}

		// if there are no items
		if (InputValidator::isEmptyArrayKey($content, "items")) {

			self::errorSet("P-5");
		    Logger::error("Respond for " . self::RESTPATH . "/" . $this->productID . "/" .  self::RESTPATH_ATTRIBUTES . " can not be interpreted.");
			return;
		}

		// is there any attribute found: load the attribute.
		foreach ($content['items'] as $number => $attribute) {

			// parse every attribute
			$this->attributes[$number] = new ProductAttribute($attribute);
		}

		// update timestamp when make the next request
		$timestamp = (int) (microtime(true) * 1000);
		$this->NEXT_REQUEST_TIMESTAMP = $timestamp + RESTClient::$NEXT_RESPONSE_WAIT_TIME;
	}

	/**
	 * Loads the stock level.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 */
	private function loadStockLevel() {

		// if parameter is wrong or GET is blocked
		if (!RESTClient::setRequestMethod(HTTPRequestMethod::GET)) {

			self::errorSet("RESTC-9");
			return;
		}

		$content = RESTClient::send(self::RESTPATH . "/" . $this->productID . "/" .  self::RESTPATH_STOCKLEVEL);

		// if respond is empty
		if (InputValidator::isEmpty($content)) {

			self::errorSet("P-6");
			return;
		}

		// if there are no items
		if (InputValidator::isEmptyArrayKey($content, "stocklevel")) {

			self::errorSet("P-7");
		    Logger::error("Respond for " . self::RESTPATH . "/" . $this->productID . "/" .  self::RESTPATH_STOCKLEVEL . " can not be interpreted.");
			return;
		}
		$this->stockLevel = (float) $content["stocklevel"];

		// update timestamp when make the next request
		$timestamp = (int) (microtime(true) * 1000);
		$this->NEXT_REQUEST_TIMESTAMP = $timestamp + RESTClient::$NEXT_RESPONSE_WAIT_TIME;
	}

	/**
	 * Parses the REST response data and save it.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param Array $productParameter The product in an array.
	 * @since 0.1.3
	 */
	private function parseData($productParameter) {

		// if the product comes from the shop API
		if (InputValidator::isArray($productParameter) &&
			!InputValidator::isEmptyArrayKey($productParameter, "productId")) {

			$this->productID = $productParameter['productId'];

			if (!InputValidator::isEmptyArrayKey($productParameter, "forSale")) {

				$this->forSale = $productParameter['forSale'];
			}

			if (!InputValidator::isEmptyArrayKey($productParameter, "specialOffer")) {

				$this->specialOffer = $productParameter['specialOffer'];
			}

			if (!InputValidator::isEmptyArrayKey($productParameter, "name")) {

				$this->name = $productParameter['name'];
			}

			if (!InputValidator::isEmptyArrayKey($productParameter, "shortDescription")) {

				$this->shortDescription = $productParameter['shortDescription'];
			}

			if (!InputValidator::isEmptyArrayKey($productParameter, "description")) {

				$this->description = $productParameter['description'];
			}

			if (!InputValidator::isEmptyArrayKey($productParameter, "availabilityText")) {

				$this->availabilityText = $productParameter['availabilityText'];
			}

			if (!InputValidator::isEmptyArrayKey($productParameter, "productNumber")) {

				$this->productNumber = $productParameter['productNumber'];
			}

			if (!InputValidator::isEmptyArrayKey($productParameter, "energyLabelsString")) {

				$this->energyLabelsString = $productParameter['energyLabelsString'];
			}

			if (!InputValidator::isEmptyArrayKey($productParameter, "manufacturer")) {

				$this->manufacturer = $productParameter['manufacturer'];
			}

			if (!InputValidator::isEmptyArrayKey($productParameter, "upc")) {

				$this->UPC = $productParameter['upc'];
			}

			if (!InputValidator::isEmptyArrayKey($productParameter, "ean")) {

				$this->EAN = $productParameter['ean'];
			}

			if (!InputValidator::isEmptyArrayKey($productParameter, "essentialFeatures")) {

				$this->essentialFeatures = $productParameter['essentialFeatures'];
			}

			if (!InputValidator::isEmptyArrayKey($productParameter, "searchKeywords")) {

				$this->searchKeywords = $productParameter['searchKeywords'];
			}

			if (!InputValidator::isEmptyArrayKey($productParameter, "visibility")) {

				$this->visibility = $productParameter['visibility'];
			}

			if (!InputValidator::isEmptyArrayKey($productParameter, "deliveryPeriod")) {

				$this->deliveryPeriod = $productParameter['deliveryPeriod'];
			}

			if (!InputValidator::isEmptyArrayKey($productParameter, "deliveryWeight")) {

				$this->deliveryWeight = new Quantity($productParameter['deliveryWeight']);
			}

			if (!InputValidator::isEmptyArrayKey($productParameter, "availibility") &&
				($productParameter['availibility'] == ProductAvailibility::ONSTOCK ||
				$productParameter['availibility'] == ProductAvailibility::WARNSTOCK ||
				$productParameter['availibility'] == ProductAvailibility::OUTSTOCK)) {

				$this->availibility = $productParameter['availibility'];
			}

			if (!InputValidator::isEmptyArrayKey($productParameter, "energyLabelSourceFile")) {

				$this->energyLabelSourceFile = $productParameter['energyLabelSourceFile'];
			}

			// parse images
			if (!InputValidator::isEmptyArrayKey($productParameter, "images")) {

				foreach ($productParameter['images'] as $image) {

					if (InputValidator::isArray($image) &&
						!InputValidator::isEmptyArrayKey($image, "classifier") &&
						!InputValidator::isEmptyArrayKey($image, "url")) {

						$this->images[$image['classifier']] = new Image($image['url']);
					}
				}
			}

			// parse price
			if (!InputValidator::isEmptyArrayKey($productParameter, "priceInfo")) {

				$priceInformation = $productParameter['priceInfo'];

				if (!InputValidator::isEmptyArrayKey($priceInformation, "price") &&
					!InputValidator::isEmptyArrayKey($priceInformation, "quantity")) {

					$this->price = new ProductPrice($this->productID, ProductPriceType::PRICE, $priceInformation['price'], $priceInformation['quantity']);
				}

				if (!InputValidator::isEmptyArrayKey($priceInformation, "depositPrice")) {

					$this->depositPrice = new ProductPrice($this->productID, ProductPriceType::DEPOSIT, $priceInformation['depositPrice'], $priceInformation['quantity']);
				}

				if (!InputValidator::isEmptyArrayKey($priceInformation, "ecoParticipationPrice")) {

					$this->ecoParticipationPrice = new ProductPrice($this->productID, ProductPriceType::ECOPARTICIPATION, $priceInformation['ecoParticipationPrice'], $priceInformation['quantity']);
				}

				if (!InputValidator::isEmptyArrayKey($priceInformation, "priceWithDeposits")) {

					$this->withDepositPrice = new ProductPrice($this->productID, ProductPriceType::WITHDEPOSITS, $priceInformation['priceWithDeposits'], $priceInformation['quantity']);
				}

				if (!InputValidator::isEmptyArrayKey($priceInformation, "manufacturerPrice")) {

					$this->manufacturerPrice = new ProductPrice($this->productID, ProductPriceType::MANUFACTURER, $priceInformation['manufacturerPrice'], $priceInformation['quantity']);
				}

				if (!InputValidator::isEmptyArrayKey($priceInformation, "basePrice")) {

					$this->basePrice = new ProductPrice($this->productID, ProductPriceType::BASE, $priceInformation['basePrice'], $priceInformation['quantity']);
				}
			}
		}

		// update timestamp when make the next request
		$timestamp = (int) (microtime(true) * 1000);
		$this->NEXT_REQUEST_TIMESTAMP = $timestamp + RESTClient::$NEXT_RESPONSE_WAIT_TIME;
	}

 	/**
 	 * This function checks whether a reload is needed.
 	 *
 	 * @author David Pauli <contact@david-pauli.de>
 	 * @since 0.1.3
 	 */
 	private function reload() {

 		$timestamp = (int) (microtime(true) * 1000);

 		// if the value is empty
 		if ($this->NEXT_REQUEST_TIMESTAMP > $timestamp) {
 			return;
 		}

 		$this->load();
 	}

	/**
	 * Sets an attribute of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $path The path to this attribute.
	 * @param String $value The new attribute value.
	 * @since 0.1.2
	 */
	private function setAttribute($path, $value) {

		// if parameter is no string or
		if (!InputValidator::isString($value)) {

			Logger::warning("ep6\Product\nNew attribute (" . $value . ") is no string.");
			self::errorSet("P-2");
			return;
		}

		// if PATCH does not work
		if (!RESTClient::setRequestMethod("PATCH")) {

			self::errorSet("RESTC-9");
			return;
		}

		$parameter = array("op" => "add", "path" => $path, "value" => $value);
		$productParameter = RESTClient::send(self::RESTPATH . "/" . $this->productID, $parameter);

		// update the product
		$this->parseData($productParameter);
	}

	/**
	 * Unsets an attribute value of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $path The path to this attribute.
	 * @since 0.1.2
	 */
	private function unsetAttribute($path) {

		// if PATCH does not work
		if (!RESTClient::setRequestMethod("PATCH")) {

			self::errorSet("RESTC-9");
			return;
		}

		$parameter = array("op" => "remove", "path" => $path);
		$productParameter = RESTClient::send(self::RESTPATH, $parameter);

		// update the product
		$this->parseData($productParameter);
	}
}
?>