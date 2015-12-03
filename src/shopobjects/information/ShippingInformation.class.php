<?php
/**
 * This file represents the shipping information class.
 */
namespace ep6;
require_once("src/shopobjects/information/InformationTrait.class.php");
/**
 * This class is needed for the shipping information in the shop.
 */
class ShippingInformation {
	
	use InformationTrait;

	/**
	 * The REST path for shipping information.
	 */
	private static $RESTPATH = "legal/shipping-information";
	
}
?>