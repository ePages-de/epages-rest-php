<?php
/**
 * This file represents the shipping information class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
/**
 * This class is needed for the shipping information in the shop.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @package ep6
 * @subpackage Shopobjects\Information
 * @see InformationTrait This trait has all information needed objects.
 */
class ShippingInformation {

	use InformationTrait;

	/** @var String The REST path for shipping information. */
	private static $RESTPATH = "legal/shipping-information";

}
?>