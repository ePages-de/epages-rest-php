<?php
/**
 * This file represents the Shipping Method class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.1.3
 */
namespace ep6;
/**
 * This is a Shipping Method class to handle possible shipping methods.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @package ep6
 * @since 0.1.3
 * @subpackage Shopobjects\Method
 */
class ShippingMethod {

	use ErrorReporting;

	/** @var String|null The ID of the shipping method. **/
	private $shippingMethodId = null;

	/** @var String|null The name of the shipping method. **/
	private $name = null;

	/**
	 * This is the constructor.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String[] $shippingMethodParameter The shipping method in an array to construct.
	 * @since 0.1.3
	 */
	public function __construct($shippingMethodParameter) {

		// if parameter is no string
		if (!InputValidator::isArray($shippingMethodParameter)) {

			$this->errorSet("SM-1");
			Logger::error("ep6\ShippingMethod\nThe parameter shipping method paramater " . $shippingMethodParameter . " is no array.");
			return;
		}

		if (!InputValidator::isEmptyArrayKey($shippingMethodParameter, "id")) {

			$this->shippingMethodId = $shippingMethodParameter["id"];
		}

		if (!InputValidator::isEmptyArrayKey($shippingMethodParameter, "name")) {

			$this->name = $shippingMethodParameter["name"];
		}

	}

	/**
	 * Returns the ID of the shipping method.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The shipping method ID.
	 * @since 0.1.3
	 */
	public function getShippingMethodID() {

		$this->errorReset();
		return $this->shippingMethodId;
	}

	/**
	 * Returns the name of the shipping method.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The name.
	 * @since 0.1.3
	 */
	public function getName() {

		$this->errorReset();
		return $this->name;
	}
}
?>