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
			
			$this->amount = $priceParameter['amount'];
			$this->taxType = $priceParameter['taxType'];
			$this->currency = $priceParameter['currency'];
		}
	}
}
?>