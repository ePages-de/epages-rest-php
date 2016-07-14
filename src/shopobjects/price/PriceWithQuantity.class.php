<?php
/**
 * This file represents the Price With Quantity class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.1.0
 */
namespace ep6;
/**
 * This is the class for Prices which has a Quantity.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @package ep6
 * @since 0.1.0
 * @since 0.1.1 This object is echoable.
 * @since 0.1.1 Disallow the locale parameter.
 * @subpackage Shopobjects\Price
 */
class PriceWithQuantity extends Price {

	/** @var int|null The quantity amount. */
	protected $quantityAmount = null;

	/** @var String|null The quantity unit. */
	protected $quantityUnit = null;

	/**
	 * This is the constructor of the Price With Quantity object.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @param mixed[] $priceParameter The price parameter.
	 * @param mixed[] $quantityParameter The quantity parameter.
	 * @since 0.1.0
	 * @since 0.1.1 No locale parameter is needed
	 */
	public function __construct($priceParameter, $quantityParameter) {

		parent::__construct($priceParameter);

		if (InputValidator::isArray($quantityParameter)) {

			if (!InputValidator::isEmptyArrayKey($quantityParameter, "amount")) {

				$this->quantityAmount = $quantityParameter['amount'];
			}

			if (!InputValidator::isEmptyArrayKey($quantityParameter, "unit")) {

				$this->quantityUnit = $quantityParameter['unit'];
			}
		}
	}

	/**
	 * Prints the Price With Quantity object as a string.
	 *
	 * This function returns the setted attributes of the Price With Quantity object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The Price With Quantity as a string.
	 * @since 0.1.1
	 */
	public function __toString() {

		return "<strong>Amount:</strong> " . $this->amount . "<br/>" .
				"<strong>Tax type:</strong> " . $this->taxType . "<br/>" .
				"<strong>Currency:</strong> " . $this->currency . "<br/>" .
				"<strong>Formatted:</strong> " . $this->formatted . "<br/>" .
				"<strong>Quantity amount:</strong> " . $this->quantityAmount . "<br/>" .
				"<strong>Quantity unit:</strong> " . $this->quantityUnit . "<br/>";
	}

	/**
	 * Returns the quantity amount.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String Gets the quantity amount.
	 * @since 0.1.0
	 */
	public function getQuantityAmount() {

		return $this->quantityAmount;
	}

	/**
	 * Returns the quantity unit.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String Gets the quantity unit.
	 * @since 0.1.0
	 * @since 0.1.1 No locale parameter is needed.
	 */
	public function getQuantityUnit() {

		return $this->quantityUnit;
	}
}
?>