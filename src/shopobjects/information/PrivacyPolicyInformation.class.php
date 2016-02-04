<?php
/**
 * This file represents the privacy policy information class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
/**
 * The privacy policy information.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @since 0.1.1 This object is now echoable.
 * @package ep6
 * @subpackage Shopobjects\Information
 * @see InformationTrait This trait has all information needed objects.
 */
class PrivacyPolicyInformation {

	use InformationTrait;

	/** @var String The REST path for privacy policy. */
	private static $RESTPATH = "legal/privacy-policy";

	/** @var int Timestamp in ms when the next request needs to be done. */
	private static $NEXT_REQUEST_TIMESTAMP = 0;

	/**
	 * Prints the Information object as a string.
	 *
	 * This function returns the setted values of the Information object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.1
	 * @return String The Information as a string.
	 */
	public function __toString() {

		return "<strong>Name:</strong> " . self::$NAME . "<br/>" .
				"<strong>Navigation caption:</strong> " . self::$NAVIGATIONCAPTION . "<br/>" .
				"<strong>Description:</strong> " . self::$DESCRIPTION . "<br/>" .
				"<strong>Next allowed request time:</strong> " . self::$NEXT_REQUEST_TIMESTAMP . "<br/>";
	}
}
?>