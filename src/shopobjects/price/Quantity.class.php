<?php
/**
 * This file represents the quantity class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
/**
 * This is the class for quantity objects.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @package ep6
 * @subpackage Shopobjects\Price
 */
class Quantity {
		
	/** @var float The amount of the quantity. */
	private $amount = 0.0;
	
	/** @var String|null The unit of the quantity. */
	private $unit = null;
	
	/**
	 * This is the constructor of the quantity object.
	 *
	 * @api
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @param float $amount The quantity value.
	 * @param String $unit The unit of the quantity.
	 */
	public function __construct($amount, $unit) {
		
		if (!InputValidator::isFloat($amount) && !InputValidator::isUnit($unit)) {
			return;
		}
	}
}
?>