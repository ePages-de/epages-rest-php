<?php
/**
 * This file represents the Quantity class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.2.0
 */
namespace ep6;
/**
 * This is the class for Quantity attributes.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @package ep6
 * @since 0.2.0
 * @subpackage Shopobjects\Quantity
 */
class Quantity {

	/** @var int|null The quantity amount. */
	protected $amount = null;

	/** @var String|null The quantity unit. */
	protected $unit = null;

	/**
	 * This is the constructor of the Quantity object.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @param mixed[] $quantityParameter The quantity parameter.
	 * @since 0.2.0
	 */
	public function __construct($quantityParameter) {

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
	 * Prints the Quantity object as a string.
	 *
	 * This function returns the setted attributes of the Quantity object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The Quantity as a string.
	 * @since 0.2.0
	 */
	public function __toString() {

		return "<strong>Amount:</strong> " . $this->amount . "<br/>" .
				"<strong>Unit:</strong> " . $this->unit . "<br/>";
	}

	/**
	 * Returns the quantity amount.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String Gets the quantity amount.
	 * @since 0.2.0
	 */
	public function getAmount() {

		return $this->amount;
	}

	/**
	 * Returns the quantity unit.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String Gets the quantity unit.
	 * @since 0.2.0
	 */
	public function getUnit() {

		return $this->unit;
	}
}
?>