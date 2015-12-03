<?php
/**
 * This file represents the quantity class.
 */
namespace ep6;
/**
 * This is the class for quantity objects.
 */
class Quantity {
		
	/**
	 * The amount of the quantity.
	 */
	private $amount = 0.0;
	
	/**
	 * The unit of the quantity.
	 */
	private $unit = "";
	
	/**
	 * This is the constructor of the quantity object.
	 *
	 * @param float amount	The quantity value.
	 * @param String unit	The unit of the quantity.
	 */
	public function __construct($amount, $unit) {
		
		if (!InputValidator::isFloat($amount) && !InputValidator::isUnit($unit)) {
			return;
		}
	}
}
?>