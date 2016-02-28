<?php
/**
 * This file represents the price with quantity class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.1.0
 */
namespace ep6;
/**
 * This is the class for prices which has a quantity.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.1.0
 * @since 0.1.1 This object is echoable.
 * @since 0.1.1 Disallow the locale parameter.
 * @package ep6
 * @subpackage Shopobjects\Price
 */
class PriceWithQuantity extends Price {

	/** @var int|null The quantity amount. */
	protected $quantityAmount = null;

	/** @var String|null The localized quantity unit. */
	protected $quantityUnit = null;

	/**
	 * This is the constructor of the price with quantity object.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @since 0.1.1 No locale parameter is needed
	 * @param mixed[] $priceParameter The price parameter.
	 * @param mixed[] $quantityParameter The quantity parameter.
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
	 * Returns the quantity amount.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @return String Gets the quantity amount.
	 */
	public function getQuantityAmount() {

		return $this->quantityAmount;
	}

	/**
	 * Returns the quantity unit.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @since 0.1.1 No locale parameter is needed.
	 * @api
	 * @param String $locale The localization.
	 * @return String Gets the quantity unit.
	 */
	public function getQuantityUnit() {

		return $this->quantityUnit;
	}

	/**
	 * Prints the Price with quantity object as a string.
	 *
	 * This function returns the setted attributes of the Price with quantity object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.1
	 * @return String The Price with quantity as a string.
	 */
	public function __toString() {

		return "<strong>Amount:</strong> " . $this->amount . "<br/>" .
				"<strong>Tax type:</strong> " . $this->taxType . "<br/>" .
				"<strong>Currency:</strong> " . $this->currency . "<br/>" .
				"<strong>Formatted:</strong> " . $this->formatted . "<br/>" .
				"<strong>Quantity amount:</strong> " . $this->quantityAmount . "<br/>" .
				"<strong>Quantity unit:</strong> " . $this->quantityUnit . "<br/>";
	}
}
?>