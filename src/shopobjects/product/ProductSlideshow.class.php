<?php
/**
 * This file represents the product slideshow class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.1.0
 */
namespace ep6;
/**
 * This is the product slideshow class which saves all images of the slideshow.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.1.0
 * @api
 * @package ep6
 * @subpackage Shopobjects\Product
 */
class ProductSlideshow {

	/** @var String The REST path to the product slideshow ressource. */
	private static $RESTPATH = "slideshow";

	/** @var Image[] The space for the images.
	 *
	 * It is saved like:
	 *   [0]
	 *     [Thumbnail]
	 *     [Small]
	 *     [...]
	 *   [1]
	 *     [...]
	 */
	private $images = array();

	/** @var String|null The space for the product id. */
	private $productID = null;

	/** @var int Timestamp in ms when the next request needs to be done. */
	private static $NEXT_REQUEST_TIMESTAMP = 0;

	/**
	 * Constructor of the Slideshow.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @param String $productID The product ID to get images.
	 */
	public function __construct($productID) {
		$this->load($productID);
	}

	/**
	 * This function gets the product images.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @param String $productID The product ID to get images.
	 */
	private function load($productID) {

		// if parameter is wrong or GET is blocked
		if (!InputValidator::isProductId($productID) ||
			!RESTClient::setRequestMethod(HTTPRequestMethod::GET)) {
			return;
		}

		$content = RESTClient::send("products/" . $productID . "/" . self::$RESTPATH);

		// if respond is empty
		if (InputValidator::isEmpty($content)) {
			return;
		}

		// if there is items
		if (InputValidator::isEmptyArrayKey($content, "items")) {
		    Logger::error("Respond for product/" . $productID . "/" . self::RESTPATH . " can not be interpreted.");
			return;
		}

		// is there any images found: load the images.
		foreach ($content['items'] as $number => $image) {

			// parse every image size
			if (!InputValidator::isEmptyArrayKey($image, "sizes")) {

				$object = null;
				foreach ($image["sizes"] as $size) {

					// if there is "url" and "classifier" set in the image
					if (!InputValidator::isEmptyArrayKey($size, "url") &&
						!InputValidator::isEmptyArrayKey($size, "classifier")) {

						$object[$size["classifier"]] == $size["url"];
					}
				}

				// if all needed sizes are available, save it
				if (!InputValidator::isEmptyArrayKey($object, "Thumbnail") &&
					!InputValidator::isEmptyArrayKey($object, "Small") &&
					!InputValidator::isEmptyArrayKey($object, "HotDeal") &&
					!InputValidator::isEmptyArrayKey($object, "MediumSmall") &&
					!InputValidator::isEmptyArrayKey($object, "Medium") &&
					!InputValidator::isEmptyArrayKey($object, "MediumLarge") &&
					!InputValidator::isEmptyArrayKey($object, "Large")) {
					array_push($this->images, $object);
				}
			}
		}

		$this->productID = $productID;

		// update timestamp when make the next request
		$timestamp = (int) (microtime(true) * 1000);
		self::$NEXT_REQUEST_TIMESTAMP = $timestamp + RESTClient::NEXT_RESPONSE_WAIT_TIME;
	}

	/**
	 * This function checks whether a reload is needed.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 */
	public static function reload() {

		$timestamp = (int) (microtime(true) * 1000);

		// if the value is empty
		if (self::$NEXT_REQUEST_TIMESTAMP > $timestamp) {
			return;
		}

		$this->load($this->productID);
	}

	/**
	 * Gets the number of available images.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @return int The number of images.
	 */
	public function getCountImages() {

		if (!InputValidator::isEmpty($this->images)) {
			return 0;
		}
		return sizeof($this->$images);
	}

	/**
	 * Returns a thumbnail image.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @param int $image The image number to get
	 * @return Image|null The thumbnail image.
	 */
	public function getThumbnailImage($image) {

		if ($this->getCountImages() == 0 ||
			!InputValidator::isRangedInt($image, 0, $this->getCountImages() - 1) ||
			!InputValidator::isEmptyArrayKey($this->images[$image], "Thumbnail")) {
			return null;
		}
		return $this->images[$image]["Thumbnail"];
	}

	/**
	 * Returns a small image.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @param int $image The image number to get
	 * @return Image|null The small image.
	 */
	public function getSmallImage($image) {

		if ($this->getCountImages() == 0 ||
			!InputValidator::isRangedInt($image, 0, $this->getCountImages() - 1) ||
			!InputValidator::isEmptyArrayKey($this->images[$image], "Small")) {
			return null;
		}
		return $this->images[$image]["Small"];
	}

	/**
	 * Returns a hotdeal image.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @param int $image The image number to get
	 * @return Image|null The hotdeal image.
	 */
	public function getHotDealImage($image) {

		if ($this->getCountImages() == 0 ||
			!InputValidator::isRangedInt($image, 0, $this->getCountImages() - 1) ||
			!InputValidator::isEmptyArrayKey($this->images[$image], "HotDeal")) {
			return null;
		}
		return $this->images[$image]["HotDeal"];
	}

	/**
	 * Returns a medium small image.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @param int $image The image number to get
	 * @return Image|null The medium small image.
	 */
	public function getMediumSmallImage($image) {

		if ($this->getCountImages() == 0 ||
			!InputValidator::isRangedInt($image, 0, $this->getCountImages() - 1) ||
			!InputValidator::isEmptyArrayKey($this->images[$image], "MediumSmall")) {
			return null;
		}
		return $this->images[$image]["MediumSmall"];
	}

	/**
	 * Returns a medium image.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @param int $image The image number to get
	 * @return Image|null The medium image.
	 */
	public function getMediumImage($image) {

		if ($this->getCountImages() == 0 ||
			!InputValidator::isRangedInt($image, 0, $this->getCountImages() - 1) ||
			!InputValidator::isEmptyArrayKey($this->images[$image], "Medium")) {
			return null;
		}
		return $this->images[$image]["Medium"];
	}

	/**
	 * Returns a medium large image.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @param int $image The image number to get
	 * @return Image|null The medium large image.
	 */
	public function getMediumLargeImage($image) {

		if ($this->getCountImages() == 0 ||
			!InputValidator::isRangedInt($image, 0, $this->getCountImages() - 1) ||
			!InputValidator::isEmptyArrayKey($this->images[$image], "MediumLarge")) {
			return null;
		}
		return $this->images[$image]["MediumLarge"];
	}

	/**
	 * Returns a large image.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @param int $image The image number to get
	 * @return Image|null The large image.
	 */
	public function getLargeImage($image) {

		if ($this->getCountImages() == 0 ||
			!InputValidator::isRangedInt($image, 0, $this->getCountImages() - 1) ||
			!InputValidator::isEmptyArrayKey($this->images[$image], "Large")) {
			return null;
		}
		return $this->images[$image]["Large"];
	}
}
?>