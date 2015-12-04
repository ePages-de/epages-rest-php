<?php
/**
 * This file represents the terms and condition information class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
require_once("src/shopobjects/information/InformationTrait.class.php");
/**
 * This class is required for showing the information of terms and condition.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @package ep6
 * @subpackage Shopobjects\Information
 * @see InformationTrait This trait has all information needed objects.
 */
class TermsAndConditionInformation {
	
	use InformationTrait;

	/** @var String The REST path for terms and condition. */
	private static $RESTPATH = "legal/terms-and-conditions";
	
}
?>