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
		
	/** @var mixed[] The quantity. */
	private $quantity = array();
	
	/**
	 * This is the constructor of the price with quantity object.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.0
	 * @param mixed[] $priceParameter The price parameter.
	 * @param mixed[] $quantityParameter The quantity parameter.
	 */
	public function __construct($priceParameter, $quantityParameter) {
		
		parent::__construct($priceParameter);
		if (InputValidator::isArray($quantityParameter)) {
			
			if (!InputValidator::isEmptyArrayKey($quantityParameter, "amount")) {
				$this->quantity['amount'] = $quantityParameter['amount'];
			}
			if (!InputValidator::isEmptyArrayKey($quantityParameter, "unit")) {
				$this->quantity['unit'] = $quantityParameter['unit'];
			}
		}
	}
}
?>