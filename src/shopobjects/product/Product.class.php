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
 * @since 0.2.0 Add price information.
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
	 * @since 0.2.0 Add price information.
	 * @api
	 * @param mixed[] $productParameter The product to create as array.
	 */
	public function __construct($productParameter) {
		
		// if the product comes from the shop API
		if (InputValidator::isArray($productParameter)) {
		 	
			$this->productID = $productParameter['productId'];
			$this->name[$productParameter['locale']] = $productParameter['name'];
			$this->shortDescription[$productParameter['locale']] = $productParameter['shortDescription'];
			$this->description[$productParameter['locale']] = $productParameter['description'];
			$this->forSale = $productParameter['forSale'];
			$this->specialOffer = $productParameter['specialOffer'];
			$this->availibilityText[$productParameter['locale']] = $productParameter['availabilityText'];
			foreach ($productParameter['images'] as $image) {
				$this->images[$image['classifier']] = new Image($image['url']);
			}

			// save price
			$priceInformation = $productParameter['priceInfo'];
			$this->price = new PriceWithQuantity($priceInformation['price'], $priceInformation['quantity']);
			$this->depositPrice = new Price($priceInformation['depositPrice']);
			$this->ecoParticipationPrice = new Price($priceInformation['ecoParticipationPrice']);
			$this->withDepositPrice = new Price($priceInformation['priceWithDeposits']);
			$this->manufactorPrice = new Price($priceInformation['manufactorPrice']);
			$this->basePrice = new Price($priceInformation['basePrice']);
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
		
		return array_key_exists($locale, $this->name) ? $this->name[$locale] : null;
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
		
		return array_key_exists($locale, $this->shortDescription) ? $this->shortDescription[$locale] : null;
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
		
		return array_key_exists($locale, $this->description) ? $this->description[$locale] : null;
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

		return array_key_exists($locale, $this->availibilityText) ? $this->availibilityText[$locale] : null;
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

		return array_key_exists("Small", $this->images) ? $this->images["Small"] : null;
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

		return array_key_exists("Medium", $this->images) ? $this->images["Medium"] : null;
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

		return array_key_exists("Large", $this->images) ? $this->images["Large"] : null;
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

		return array_key_exists("HotDeal", $this->images) ? $this->images["HotDeal"] : null;
	}
}

?>