<?php
/**
 * This file represents the Image class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
/**
 * This is the image class which is used for images.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @package ep6
 * @since 0.0.0
 * @since 0.1.1 Now the object can be echoed.
 * @subpackage Shopobjects\Image
 */
class Image {

	/** @var string This is the path to the origin URL. */
	private $URL = null;

	/**
	 * To create a new Image object use this constructor with the original URL.
	 *
 	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $url The origin URL of the Image.
 	 * @since 0.0.0
	 */
	public function __construct($url) {

		$this->URL = $url;
	}

	/**
	 * Prints the Image object as a string.
	 *
	 * This function returns the setted values of the Image object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The Image as a string.
	 * @since 0.1.1
	 */
	public function __toString() {

		return "<strong>URL:</strong> " . $this->URL . "<br/>";
	}

	/**
	 * Gets the original URL of the Image.
	 *
 	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The original URL.
 	 * @since 0.0.0
	 */
	public function getOriginURL() {

		return $this->URL;
	}
}