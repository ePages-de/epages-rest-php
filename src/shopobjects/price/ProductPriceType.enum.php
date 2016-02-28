<?php
namespace ep6;
/**
 * The Product Price types 'enum'.
 *
 * This are the possible Product Prices types.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.1.2
 * @package ep6
 * @subpackage Shobojects\Price
 */
abstract class ProductPriceType {
	/** @var String The normal price (with quantity). **/
	const PRICE = "price";
	/** @var String The deposit price. **/
	const DEPOSIT = "depositPrice";
	/** @var String The eco participation price. **/
	const ECOPARTICIPATION = "ecoParticipationPrice";
	/** @var String The price with deposits. **/
	const WITHDEPOSITS = "priceWithDeposits";
	/** @var String The manufactor price. **/
	const MANUFACTURER = "manufacturerPrice";
	/** @var String The base price. **/
	const BASE = "basePrice";
}

?>