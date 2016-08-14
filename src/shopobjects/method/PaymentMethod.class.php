<?php
/**
 * This file represents the Payment Method class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.1.3
 */
namespace ep6;
/**
 * This is a Payment Method class to handle possible payment methods.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @package ep6
 * @since 0.1.3
 * @subpackage Shopobjects\Method
 */
class PaymentMethod {

	use ErrorReporting;

	/** @var String|null The ID of the payment method. **/
	private $paymentMethodId = null;

	/** @var String|null The name of the payment method. **/
	private $name = null;

	/**
	 * This is the constructor.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String[] $paymentMethodParameter The payment method in an array to construct.
	 * @since 0.1.3
	 */
	public function __construct($paymentMethodParameter) {

		// if parameter is no string
		if (!InputValidator::isArray($paymentMethodParameter)) {

			$this->errorSet("SM-1");
			Logger::error("ep6\PaymentMethod\nThe parameter payment method paramater " . $paymentMethodParameter . " is no array.");
			return;
		}

		if (!InputValidator::isEmptyArrayKey($paymentMethodParameter, "id")) {

			$this->paymentMethodId = $paymentMethodParameter["id"];
		}

		if (!InputValidator::isEmptyArrayKey($paymentMethodParameter, "name")) {

			$this->name = $paymentMethodParameter["name"];
		}

	}

	/**
	 * Prints the Payment Method object as a string.
	 *
	 * This function returns the setted values of the Payment Method object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The Payment Method as a string.
	 * @since 0.2.0
	 */
	public function __toString() {

		return "<strong>Payment Method ID:</strong> " . $this->paymentMethodId . "<br/>" .
				"<strong>Name:</strong> " . $this->name . "<br/>";
	}

	/**
	 * Returns the ID of the payment method.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The payment method ID.
	 * @since 0.1.3
	 */
	public function getPaymentMethodID() {

		$this->errorReset();
		return $this->paymentMethodId;
	}

	/**
	 * Returns the name of the payment method.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The name.
	 * @since 0.1.3
	 */
	public function getName() {

		$this->errorReset();
		return $this->name;
	}
}
?>