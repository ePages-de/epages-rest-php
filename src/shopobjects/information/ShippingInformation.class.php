<?php
namespace ep6;
require_once("src/shopobjects/information/InformationTrait.class.php");

class ShippingInformation {
	
	use InformationTrait;

	/**
	 * The REST path for shipping information.
	 */
	private static $RESTPATH = "legal/shipping-information";
	
}
?>