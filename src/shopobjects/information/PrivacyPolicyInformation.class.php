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
 * @since 0.1.1 Unstatic every attributes.
 * @since 0.1.2 Add error reporting.
 * @package ep6
 * @subpackage Shopobjects\Information
 * @see InformationTrait This trait has all information needed objects.
 */
class PrivacyPolicyInformation {

	use InformationTrait, ErrorReporting;

	/** @var String The REST path for privacy policy. */
	const RESTPATH = "legal/privacy-policy";

	/** @var int Timestamp in ms when the next request needs to be done. */
	private $NEXT_REQUEST_TIMESTAMP = 0;

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

		return "<strong>Name:</strong> " . $this->NAME . "<br/>" .
				"<strong>Navigation caption:</strong> " . $this->NAVIGATIONCAPTION . "<br/>" .
				"<strong>Description:</strong> " . $this->DESCRIPTION . "<br/>" .
				"<strong>Next allowed request time:</strong> " . $this->NEXT_REQUEST_TIMESTAMP . "<br/>";
	}
}
?>