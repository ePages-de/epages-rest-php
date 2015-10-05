<?php
namespace ep6;
require_once("src/shopobjects/information/InformationTrait.class.php");

class RightsOfWithdrawalInformation {
	
	use InformationTrait;

	/**
	 * The REST path for rights of withdrawal.
	 */
	private static $RESTPATH = "legal/rights-of-withdrawal";
	
}
?>