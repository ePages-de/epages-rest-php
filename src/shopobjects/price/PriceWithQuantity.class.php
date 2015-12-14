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
 * @package ep6
 * @subpackage Shopobjects\Price
 */
class PriceWithQuantity extends Price {
		
	/** @var int The quantity amount. */
	private $quantityAmount = null;
		
	/** @var mixed[] The localized quantity unit. */
	private $quantityUnit = array();
	
	/**
	 * This is the constructor of the price with quantity object.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @param mixed[] $priceParameter The price parameter.
	 * @param mixed[] $quantityParameter The quantity parameter.
	 * @param String $locale The localization parameter.
	 */
	public function __construct($priceParameter, $quantityParameter, $locale) {

		parent::__construct($priceParameter);

		if (InputValidator::isArray($quantityParameter)) {
			
			if (!InputValidator::isEmptyArrayKey($quantityParameter, "amount")) {
				$this->quantityAmount = $quantityParameter['amount'];
			}

			if (InputValidator::isLocale($locale)) {
				if (!InputValidator::isEmptyArrayKey($quantityParameter, "unit")) {
					$this->quantityUnit[$locale] = $quantityParameter['unit'];
				}
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
	 * @api
	 * @param String $locale The localization.
	 * @return String Gets the quantity unit.
	 */
	public function getQuantityUnit($locale) {

		if (!InputValidator::isLocale($locale)) {
			return;
		}

		return !InputValidator::isEmptyArrayKey($this->quantityUnit, $locale) ? $this->quantityUnit[$locale] : null;
	}
}
?>