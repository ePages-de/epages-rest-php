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
 * @package ep6
 * @subpackage Shopobjects\Product
 */
class Product {
	
	/** @var String|null The product ID. */
	private $productID = null;
	
	/** @var Strng[] The language dependend name of the product. */
	private $name = array();
	
	/** @var String[] The language dependend short description. */
	private $shortDescription = array();
	
	/** @var String[] The language dependend description. */
	private $description = array();
	
	/** @var boolean Is this product for sale? */
	private $forSale = true;
	
	/** @var boolean Is this product a special offer? */
	private $specialOffer = false;
	
	/** @var String[] The language dependend text of availibility. */
	private $availibilityText = array();
	
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
	
	/**
	 * This is the constructor of the product.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Add price information.
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
			
			// if you have a localization
			if (!InputValidator::isEmptyArrayKey($productParameter, "locale")) {

				if (!InputValidator::isEmptyArrayKey($productParameter, "name")) {
					$this->name[$productParameter['locale']] = $productParameter['name'];
				}
				if (!InputValidator::isEmptyArrayKey($productParameter, "shortDescription")) {
					$this->shortDescription[$productParameter['locale']] = $productParameter['shortDescription'];
				}
				if (!InputValidator::isEmptyArrayKey($productParameter, "description")) {
					$this->description[$productParameter['locale']] = $productParameter['description'];
				}
				if (!InputValidator::isEmptyArrayKey($productParameter, "availabilityText")) {
					$this->availabilityText[$productParameter['locale']] = $productParameter['availabilityText'];
				}
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
					!InputValidator::isEmptyArrayKey($priceInformation, "quantity")) {
					$this->price = new PriceWithQuantity($priceInformation['price'], $priceInformation['quantity']);
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
	 * @api
	 * @param String $locale The localization of the requested name.
	 * @return String The name.
	 */
	public function getName($locale) {
		
		return !InputValidator::isEmptyArrayKey($this->name, $locale) ? $this->name[$locale] : null;
	}
	
	/**
	 * Returns the short description in a specific localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @param String $locale The localization of the requested short description.
	 * @return String The short description.
	 */
	public function getShortDescription($locale) {
		
		return !InputValidator::isEmptyArrayKey($this->shortDescription, $locale) ? $this->shortDescription[$locale] : null;
	}
	
	/**
	 * Returns the description in a specific localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @param String $locale The localization of the requested description.
	 * @return String The description.
	 */
	public function getDescription($locale) {
		
		return !InputValidator::isEmptyArrayKey($this->description, $locale) ? $this->description[$locale] : null;
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
	 * @api
	 * @param String $locale The localization of the requested availibility text.
	 * @return String The availibility text.
	 */
	public function getAvailibilityText($locale) {

		return !InputValidator::isEmptyArrayKey($this->availibilityText, $locale) ? $this->availibilityText[$locale] : null;
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
}

?>