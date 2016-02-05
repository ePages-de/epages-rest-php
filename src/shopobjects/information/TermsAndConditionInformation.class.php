<?php
/**
 * This file represents the terms and condition information class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
/**
 * This class is required for showing the information of terms and condition.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @since 0.1.1 This object is now echoable.
 * @since 0.1.1 Unstatic every attributes.
 * @package ep6
 * @subpackage Shopobjects\Information
 * @see InformationTrait This trait has all information needed objects.
 */
class TermsAndConditionInformation {

	use InformationTrait;

	/** @var String The REST path for terms and condition. */
	const RESTPATH = "legal/terms-and-conditions";

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