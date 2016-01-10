<?php
/**
 * This file represents the product class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
/**
 * This is the product class for a product in the shop.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @since 0.1.0 Add price information.
 * @since 0.1.0 Function to delete itself.
 * @since 0.1.0 Delete different Locales.
 * @since 0.1.0 Implement Slideshow functionality.
 * @package ep6
 * @subpackage Shopobjects\Product
 */
class Product {

	/** @var String The REST path to the product ressource. */
	private static $RESTPATH = "products";

	/** @var String|null The product ID. */
	private $productID = null;

	/** @var String|null The name of the product. */
	private $name = null;

	/** @var String|null The short description. */
	private $shortDescription = null;

	/** @var String|null The description. */
	private $description = null;

	/** @var boolean Is this product for sale? */
	private $forSale = true;

	/** @var boolean Is this product a special offer? */
	private $specialOffer = false;

	/** @var String|null The text of availibility. */
	private $availibilityText = null;

	/** @var Images[] This are the images in the four different possibilities. */
	private $images = array();

	/** @var PriceWithQuantity|null Here the price is saved. */
	private $price = null;

	/** @var Price|null Here the deposit price is saved. */
	private $depositPrice = null;

	/** @var Price|null Here the eco participation price is saved. */
	private $ecoParticipationPrice = null;

	/** @var Price|null Here the price with deposit is saved. */
	private $withDepositPrice = null;

	/** @var Price|null Here the manufactor price is saved. */
	private $manufactorPrice = null;

	/** @var Price|null Here the base price is saved. */
	private $basePrice = null;

	/** @var ProductSlideshow|null This object saves the slideshow. */
	private $slideshow = null;

	/**
	 * This is the constructor of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Add price information.
	 * @since 0.1.0 Use a default Locale.
	 * @api
	 * @param mixed[] $productParameter The product to create as array.
	 */
	public function __construct($productParameter) {

		if (!InputValidator::isArray($productParameter) ||
			InputValidator::isEmptyArray($productParameter)) {
			return;
		}

		// if the product comes from the shop API
		if (InputValidator::isArray($productParameter) &&
			!InputValidator::isEmptyArrayKey($productParameter, "productId")) {

			$this->productID = $productParameter['productId'];

			// load locale depended content
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

			// parse images
			foreach ($productParameter['images'] as $image) {
				if (InputValidator::isArray($image) &&
					!InputValidator::isEmptyArrayKey($image, "classifier") &&
					!InputValidator::isEmptyArrayKey($image, "url")) {
					$this->images[$image['classifier']] = new Image($image['url']);
				}
			}

			// parse price
			if (!InputValidator::isEmptyArrayKey($productParameter, "priceInfo")) {

				$priceInformation = $productParameter['priceInfo'];

				if (!InputValidator::isEmptyArrayKey($priceInformation, "price") &&
					!InputValidator::isEmptyArrayKey($priceInformation, "quantity") &&
					!InputValidator::isEmptyArrayKey($productParameter, "locale")) {
					$this->price = new PriceWithQuantity($priceInformation['price'], $priceInformation['quantity'], $productParameter["locale"]);
				}
				if (!InputValidator::isEmptyArrayKey($priceInformation, "depositPrice")) {
					$this->depositPrice = new Price($priceInformation['depositPrice']);
				}
				if (!InputValidator::isEmptyArrayKey($priceInformation, "ecoParticipationPrice")) {
					$this->ecoParticipationPrice = new Price($priceInformation['ecoParticipationPrice']);
				}
				if (!InputValidator::isEmptyArrayKey($priceInformation, "priceWithDeposits")) {
					$this->withDepositPrice = new Price($priceInformation['priceWithDeposits']);
				}
				if (!InputValidator::isEmptyArrayKey($priceInformation, "manufactorPrice")) {
					$this->manufactorPrice = new Price($priceInformation['manufactorPrice']);
				}
				if (!InputValidator::isEmptyArrayKey($priceInformation, "basePrice")) {
					$this->basePrice = new Price($priceInformation['basePrice']);
				}
			}
		}
	}

	/**
	 * Returns the product id.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return String The product id.
	 */
	public function getID() {

		return $this->productID;
	}

	/**
	 * Returns the name in a specific localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a default Locale.
	 * @api
	 * @param String $locale The localization of the requested name.
	 * @return String The name.
	 */
	public function getName($locale) {

		return $this->name;
	}

	/**
	 * Returns the short description in a specific localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a default Locale.
	 * @api
	 * @param String $locale The localization of the requested short description.
	 * @return String The short description.
	 */
	public function getShortDescription($locale) {

		return $this->shortDescription;
	}

	/**
	 * Returns the description in a specific localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a default Locale.
	 * @api
	 * @param String $locale The localization of the requested description.
	 * @return String The description.
	 */
	public function getDescription($locale) {

		return $this->description;
	}

	/**
	 * Returns true if it is for sale.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return boolean True if it is for sale, false if not.
	 */
	public function isForSale() {

		return $this->forSale;
	}

	/**
	 * Returns true if it is a special offer.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return boolean True if it is a special offer, false if not.
	 */
	public function isSpecialOffer() {

		return $this->specialOffer;
	}

	/**
	 * Returns the availibility text in a specific localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a default Locale.
	 * @api
	 * @param String $locale The localization of the requested availibility text.
	 * @return String The availibility text.
	 */
	public function getAvailibilityText($locale) {

		return $this->availibilityText;
	}

	/**
	 * Returns the small image.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return Image The small image.
	 */
	public function getSmallImage() {

		return !InputValidator::isEmptyArrayKey($this->images, "Small") ? $this->images["Small"] : null;
	}

	/**
	 * Returns the medium image.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return Image The medium image.
	 */
	public function getMediumImage() {

		return !InputValidator::isEmptyArrayKey($this->images, "Medium") ? $this->images["Medium"] : null;
	}

	/**
	 * Returns the large image.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return Image The large image.
	 */
	public function getLargeImage() {

		return !InputValidator::isEmptyArrayKey($this->images, "Large") ? $this->images["Large"] : null;
	}

	/**
	 * Returns the hot deal image.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return Image The hot deal image.
	 */
	public function getHotDealImage() {

		return !InputValidator::isEmptyArrayKey($this->images, "HotDeal") ? $this->images["HotDeal"] : null;
	}

	/**
	 * Returns the price with quantity.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @return PriceWithQuantity Gets the price with quantity.
	 */
	public function getPrice() {

		return $this->price;
	}

	/**
	 * Returns the deposit price.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @return Price Gets the deposit price.
	 */
	public function getDepositPrice() {

		return $this->depositPrice;
	}

	/**
	 * Returns the eco participation price.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @return Price Gets the eco participation price.
	 */
	public function getEcoParticipationPrice() {

		return $this->ecoParticipationPrice;
	}

	/**
	 * Returns the with deposit price.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @return Price Gets the with deposit price.
	 */
	public function getWithDepositPrice() {

		return $this->withDepositPrice;
	}

	/**
	 * Returns the manufactor price.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @return Price Gets the manufactor price.
	 */
	public function getManufactorPrice() {

		return $this->manufactorPrice;
	}

	/**
	 * Returns the base price.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @return Price Gets the base price.
	 */
	public function getBasePrice() {

		return $this->basePrice;
	}

	/**
	 * Returns the slideshow.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @return ProductSlideshow Gets the product slideshow.
	 */
	public function getSlideshow() {

		// if the slideshow is not loaded until now
		if (InputValidator::isEmpty($this->slideshow)) {
			$this->slideshow = new ProductSlideshow($this->productID);
		}
		else {
			$this->slideshow->reload();
		}
		return $this->slideshow;
	}

	/**
	 * Deletes itself.
	 *
	 * Dont use this function. To delete a product its better to use $shop->deleteProduct($product).
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @return boolean True if the deletion was successful, false if not.
	 */
	public function delete() {

		// if request method is blocked
		if (!RESTClient::setRequestMethod(HTTPRequestMethod::DELETE)) {
			return false;
		}

		RESTClient::send(self::$RESTPATH . "/" . $this->productID);

		return true;
	}
}

?>