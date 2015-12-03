<?php
/**
 * This file represents the terms and condition information class.
 */
namespace ep6;
require_once("src/shopobjects/information/InformationTrait.class.php");
/**
 * This class is required for showing the information of terms and condition.
 */
class TermsAndConditionInformation {
	
	use InformationTrait;

	/**
	 * The REST path for terms and condition.
	 */
	private static $RESTPATH = "legal/terms-and-conditions";
	
}
?>