<?php
/**
 * This file represents the privacy policy information class.
 */
namespace ep6;
require_once("src/shopobjects/information/InformationTrait.class.php");
/**
 * The privacy policy information.
 */
class PrivacyPolicyInformation {
	
	use InformationTrait;

	/**
	 * The REST path for privacy policy.
	 */
	private static $RESTPATH = "legal/privacy-policy";
	
}
?>