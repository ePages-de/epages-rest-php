<?php
/**
 * This file represents the product class.
 */
namespace ep6;
/**
 * This is the product class for a product in the shop.
 */
class Product {
	
	/**
	 * The product Id.
	 */
	private $productID = "";
	
	/**
	 * The language dependend name of the product.
	 */
	private $name = array();
	
	/**
	 * The language dependend short description.
	 */
	private $shortDescription = array();
	
	/**
	 * The language dependend description.
	 */
	private $description = array();
	
	/**
	 * Is this product for sale?
	 */
	private $forSale = true;
	
	/**
	 * Is this product a special offer?
	 */
	private $specialOffer = false;
	
	/**
	 * The language dependend text of availibility.
	 */
	private $availibilityText = array();
	
	/**
	 * This are the images in the four different possibilities.
	 */
	private $images = array();
	
	/**
	 * This is the constructor of the product.
	 *
	 * @param productId	The product id to create a product.
	 */
	public function __construct($productParameter) {
		
		// if the product comes from the shop API
		if (is_array($productParameter)) {
		 	
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
			

		}
		// if only the product id is known
		else if (is_string($productParameter)) {
			
		}
	}
	
	/**
	 * Returns the product id.
	 *
	 * @return String	The product id.
	 */
	public function getID() {
		
		return $this->productID;
	}
	
	/**
	 * Returns the name in a specific localization.
	 *
	 * @param locale	The localization of the requested name.
	 * @return String	The name.
	 */
	public function getName($locale) {
		
		return array_key_exists($locale, $this->name) ? $this->name[$locale] : null;
	}
	
	/**
	 * Returns the short description in a specific localization.
	 *
	 * @param locale	The localization of the requested short description.
	 * @return String	The short description.
	 */
	public function getShortDescription($locale) {
		
		return array_key_exists($locale, $this->shortDescription) ? $this->shortDescription[$locale] : null;
	}
	
	/**
	 * Returns the description in a specific localization.
	 *
	 * @param locale	The localization of the requested description.
	 * @return String	The description.
	 */
	public function getDescription($locale) {
		
		return array_key_exists($locale, $this->description) ? $this->description[$locale] : null;
	}
	
	/**
	 * Returns true if it is for sale.
	 *
	 * @return boolean	True if it is for sale, false if not.
	 */
	public function isForSale() {
		
		return $this->forSale;
	}
	
	/**
	 * Returns true if it is a special offer.
	 *
	 * @return boolean	True if it is a special offer, false if not.
	 */
	public function isSpecialOffer() {
		
		return $this->specialOffer;
	}
	
	/**
	 * Returns the availibility text in a specific localization.
	 *
	 * @param locale	The localization of the requested availibility text.
	 * @return String	The availibility text.
	 */
	public function getAvailibilityText($locale) {

		return array_key_exists($locale, $this->availibilityText) ? $this->availibilityText[$locale] : null;
	}
	
	/**
	 * Returns the small image.
	 *
	 * @return Image	The small image.
	 */
	public function getSmallImage() {

		return array_key_exists("Small", $this->images) ? $this->images["Small"] : null;
	}
	
	/**
	 * Returns the medium image.
	 *
	 * @return Image	The medium image.
	 */
	public function getMediumImage() {

		return array_key_exists("Medium", $this->images) ? $this->images["Medium"] : null;
	}
	
	/**
	 * Returns the large image.
	 *
	 * @return Image	The large image.
	 */
	public function getLargeImage() {

		return array_key_exists("Large", $this->images) ? $this->images["Large"] : null;
	}
	
	/**
	 * Returns the hot deal image.
	 *
	 * @return Image	The hot deal image.
	 */
	public function getHotDealImage() {

		return array_key_exists("HotDeal", $this->images) ? $this->images["HotDeal"] : null;
	}
}

?>