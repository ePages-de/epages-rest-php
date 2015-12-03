<?php
/**
 * This file represents the price class.
 */
namespace ep6;
/**
 * This is the class for price objects.
 */
class Price {
	
	/**
	 * The amount of the price.
	 */
	private $amount = 0.0;
	
	/**
	 * The tax type of the price.
	 */
	private $taxType = "";
	
	/**
	 * The curreny of the price.
	 */
	private $currency = "";
	
	/**
	 * This is the constructor of the price object.
	 *
	 * @param float amount		The price value.
	 * @param String currency	The currency of the price.
	 * @param String taxType	The tax type, can be "GROSS" or "NET".
	 */
	public function __construct($amount, $currency, $taxType) {
		
		if (!InputValidator::isFloat($amount) && !InputValidator::isCurrency($currency) && !InputValidator::isTaxType($taxType)) {
			return;
		}
	}
}
?>