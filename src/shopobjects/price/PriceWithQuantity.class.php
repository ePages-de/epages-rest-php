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
		
	/** @var float The amount of the quantity. */
	private $amount = 0.0;
	
	/** @var String|null The unit of the quantity. */
	private $unit = null;
	
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
			
			$this->amount = $quantityParameter['amount'];
			$this->unit = $quantityParameter['unit'];
		}
	}
}
?>