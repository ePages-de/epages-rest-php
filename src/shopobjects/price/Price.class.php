<?php
/**
 * This file represents the price class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
/**
 * This is the class for price objects.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @since 0.1.1 This object is echoable.
 * @package ep6
 * @subpackage Shopobjects\Price
 */
class Price {

	/** @var float The amount of the price. */
	private $amount = 0.0;

	/** @var String|null The tax type of the price. */
	private $taxType = null;

	/** @var String|null The curreny of the price. */
	private $currency = null;

	/**
	 * This is the constructor of the price object.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Add functionality to construct.
	 * @param mixed[] $priceParamter The price parameter.
	 */
	public function __construct($priceParameter) {

		if (InputValidator::isArray($priceParameter)) {

			if (!InputValidator::isEmptyArrayKey($priceParameter, "amount")) {
				$this->amount = $priceParameter['amount'];
			}
			if (!InputValidator::isEmptyArrayKey($priceParameter, "taxType")) {
				$this->taxType = $priceParameter['taxType'];
			}
			if (!InputValidator::isEmptyArrayKey($priceParameter, "currency")) {
				$this->currency = $priceParameter['currency'];
			}
		}
	}

	/**
	 * Returns the amount.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @return float Gets the amount.
	 */
	public function getAmount() {

		return $this->amount;
	}

	/**
	 * Returns the tax type.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @return String Gets the tax type.
	 */
	public function getTaxType() {

		return $this->taxType;
	}

	/**
	 * Returns the currency.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @api
	 * @return Sttring Gets the currency.
	 */
	public function getCurrency() {

		return $this->currency;
	}

	/**
	 * Prints the Price object as a string.
	 *
	 * This function returns the setted values of the Price object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.1
	 * @return String The Price as a string.
	 */
	public function __toString() {

		return "<strong>Amount:</strong> " . $this->amount . "<br/>" .
				"<strong>Tax type:</strong> " . $this->taxType . "<br/>" .
				"<strong>Currency:</strong> " . $this->currency . "<br/>";
	}
}
?>