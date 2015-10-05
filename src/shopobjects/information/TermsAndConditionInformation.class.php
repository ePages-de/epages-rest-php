<?php
namespace ep6;
require_once("src/shopobjects/information/InformationTrait.class.php");

class TermsAndConditionInformation {
	
	use InformationTrait;

	/**
	 * The REST path for terms and condition.
	 */
	private static $RESTPATH = "legal/terms-and-conditions";
	
}
?>