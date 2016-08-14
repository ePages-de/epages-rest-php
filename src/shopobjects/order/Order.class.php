<?php
/**
 * This file represents the Order class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.1.3
 */
namespace ep6;
/**
 * This is the Order object for an Order in the shop.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @package ep6
 * @since 0.1.3
 * @subpackage Shopobjects\Order
 */
class Order {

	use ErrorReporting;

	/** @var String The REST path to the order ressource. */
	const RESTPATH = "orders";

	/** @var Date|null The date when the order was archived. */
	private $archiveDate = null;

	/** @var Address|null The address of the bill. */
	private $billingAddress = null;

	/** @var Date|null The date when order was closed. */
	private $closeDate = null;

	/** @var Date|null The date of create the order. */
	private $creationDate = null;

	/** @var Customer|null The customer of the order. */
	private $customer = null;

	/** @var String|null The comment of the customer. */
	private $customerComment = null;

	/** @var Date|null The date of when it is delivered. */
	private $deliveryDate = null;

	/** @var Date|null The date when order was dispatched. */
	private $dispatchDate = null;

	/** @var String|null The internal note on the order. */
	private $internalNote = null;

	/** @var Date|null The date of create the invoice. */
	private $invoiceDate = null;

	/** @var String|null The unique ID of the order. */
	private $orderId = null;

	/** @var String|null The number of the order. */
	private $orderNumber = null;

	/** @var Date|null The date when order was paid. */
	private $payDate = null;

	/** @var PaymentMethod|null The payment method of the order. */
	private $paymentMethod = null;

	/** @var Price|null The payment price of the order. */
	private $paymentPrice = null;

	/** @var Date|null The date of pending status. */
	private $pendingDate = null;

	/** @var Date|null The date when it was rejected. */
	private $rejectionDate = null;

	/** @var Date|null The date when order was returned. */
	private $returnDate = null;

	/** @var Address|null The address to ship. */
	private $shippingAddress = null;

	/** @var ShippingMethod|null The shipping method of the order. */
	private $shippingMethod = null;

	/** @var Price|null The shipping price of the order. */
	private $shippingPrice = null;

	/** @var Price|null The tax price. */
	private $taxPrice = null;

	/** @var Price|null The total price of the order. */
	private $totalPrice = null;

	/** @var Price|null The total price without tax. */
	private $totalWithoutTaxPrice = null;

	/** @var Date|null The date when order was viewed. */
	private $viewDate = null;

	/** @var int Timestamp in ms when the next request needs to be done. */
	private $NEXT_REQUEST_TIMESTAMP = 0;

	/**
	 * This is the constructor of the Order.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param mixed[]|String $orderParameter The order to create as array or order ID.
	 * @since 0.1.3
	 */
	public function __construct($orderParameter) {

		if (!InputValidator::isString($orderParameter) &&
			!InputValidator::isArray($orderParameter)) {

			self::errorSet("O-1");
			Logger::warning("ep6\Order\nOrder parameter " . $orderParameter . " to create order is invalid.");
			return;
		}

		if (InputValidator::isArray($orderParameter)) {
			$this->parseData($orderParameter);
		}
		else {
			$this->orderId = $orderParameter;
			$this->reload();
		}
	}

	/**
	 * Prints the Order object as a string.
	 *
	 * This function returns the setted values of the Order object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The Order as a string.
	 * @since 0.1.1
	 */
	public function __toString() {

		return "<strong>Order ID:</strong> " . $this->orderId . "<br/>" .
				"<strong>Customer:</strong> " . $this->customer . "<br/>" .
				"<strong>Customer comment:</strong> " . $this->customerComment . "<br/>" .
				"<strong>Internal note:</strong> " . $this->internalNote . "<br/>" .
				"<strong>Payment method:</strong> " . $this->paymentMethod . "<br/>" .
				"<strong>Shipping method:</strong> " . $this->shippingMethod . "<br/>" .
				"<strong>Date archive:</strong> " . $this->archiveDate . "<br/>" .
				"<strong>Date close:</strong> " . $this->closeDate . "<br/>" .
				"<strong>Date creation:</strong> " . $this->creationDate . "<br/>" .
				"<strong>Date delivery:</strong> " . $this->deliveryDate . "<br/>" .
				"<strong>Date dispatch:</strong> " . $this->dispatchDate . "<br/>" .
				"<strong>Date invoce:</strong> " . $this->invoiceDate . "<br/>" .
				"<strong>Date pay:</strong> " . $this->payDate . "<br/>" .
				"<strong>Date pending:</strong> " . $this->pendingDate . "<br/>" .
				"<strong>Date rejection:</strong> " . $this->rejectionDate . "<br/>" .
				"<strong>Date return:</strong> " . $this->returnDate . "<br/>" .
				"<strong>Date view:</strong> " . $this->viewDate . "<br/>" .
				"<strong>Billing address:</strong> " . $this->billingAddress . "<br/>" .
				"<strong>Shipping address:</strong> " . $this->shippingAddress . "<br/>";
	}

	/**
	 * Returns the date when the order was archived.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Date The date when it was archived.
	 * @since 0.1.3
	 */
	public function getArchiveDate() {

		self::errorReset();
		$this->reload();
		return $this->archiveDate;
	}

	/**
	 * Returns the billing address.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Address The billing address
	 * @since 0.1.3
	 */
	public function getBillingAddress() {

		self::errorReset();
		$this->reload();
		return $this->billingAddress;
	}

	/**
	 * Returns the date when it was closed.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Date The date when the order was closed.
	 * @since 0.1.3
	 */
	public function getCloseDate() {

		self::errorReset();
		$this->reload();
		return $this->closeDate;
	}

	/**
	 * Returns the date when it was created.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Date The date when the order was created.
	 * @since 0.1.3
	 */
	public function getCreationDate() {

		self::errorReset();
		$this->reload();
		return $this->creationDate;
	}

	/**
	 * Returns the customer.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Customer The customer which ordered.
	 * @since 0.1.3
	 */
	public function getCustomer() {

		self::errorReset();
		$this->reload();
		return $this->customer;
	}

	/**
	 * Returns the comment of the customer.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The comment which the customer made.
	 * @since 0.1.3
	 */
	public function getCustomerComment() {

		self::errorReset();
		$this->reload();
		return $this->customerComment;
	}

	/**
	 * Returns the date when it was delivered.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Date The date when the order was delivered.
	 * @since 0.1.3
	 */
	public function getDeliveryDate() {

		self::errorReset();
		$this->reload();
		return $this->deliveryDate;
	}

	/**
	 * Returns the date when it was dispatched.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Date The date when the order was dispatched.
	 * @since 0.1.3
	 */
	public function getDispatchDate() {

		self::errorReset();
		$this->reload();
		return $this->dispatchDate;
	}

	/**
	 * Returns the order ID.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The order ID.
	 * @since 0.1.3
	 */
	public function getID() {

		self::errorReset();
		$this->reload();
		return $this->orderId;
	}

	/**
	 * Returns the internal note.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The internal note.
	 * @since 0.1.3
	 */
	public function getInternalNote() {

		self::errorReset();
		$this->reload();
		return $this->internalNote;
	}

	/**
	 * Returns the date when the invoice was created.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Date The date when the invoice was created.
	 * @since 0.1.3
	 */
	public function getInvoiceDate() {

		self::errorReset();
		$this->reload();
		return $this->invoiceDate;
	}

	/**
	 * Returns the order number.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The order number.
	 * @since 0.1.3
	 */
	public function getNumber() {

		self::errorReset();
		$this->reload();
		return $this->orderNumber;
	}

	/**
	 * Returns the date when it was paid.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Date The date when the order was paid.
	 * @since 0.1.3
	 */
	public function getPayDate() {

		self::errorReset();
		$this->reload();
		return $this->payDate;
	}

	/**
	 * Returns the payment method.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return PaymentMethod The payment method.
	 * @since 0.1.3
	 */
	public function getPaymentMethod() {

		self::errorReset();
		$this->reload();
		return $this->paymentMethod;
	}

	/**
	 * Returns the price of the payment.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Price The price of the payment.
	 * @since 0.1.3
	 */
	public function getPaymentPrice() {

		self::errorReset();
		$this->reload();
		return $this->paymentPrice;
	}

	/**
	 * Returns the date when it was pending.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Date The date when the order was pending.
	 * @since 0.1.3
	 */
	public function getPendingDate() {

		self::errorReset();
		$this->reload();
		return $this->pendingDate;
	}

	/**
	 * Returns the date when it was rejected.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Date The date when the order was rejected.
	 * @since 0.1.3
	 */
	public function getRejectionDate() {

		self::errorReset();
		$this->reload();
		return $this->rejectionDate;
	}

	/**
	 * Returns the date when it was returned.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Date The date when the order was returned.
	 * @since 0.1.3
	 */
	public function getReturnDate() {

		self::errorReset();
		$this->reload();
		return $this->returnDate;
	}

	/**
	 * Returns the shipping method.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return ShippingMethod The shipping method.
	 * @since 0.1.3
	 */
	public function getShippingMethod() {

		self::errorReset();
		$this->reload();
		return $this->shippingMethod;
	}

	/**
	 * Returns the shipping price.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Price The shipping price.
	 * @since 0.1.3
	 */
	public function getShippingPrice() {

		self::errorReset();
		$this->reload();
		return $this->shippingPrice;
	}

	/**
	 * Returns the tax price.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Price The tax price.
	 * @since 0.1.3
	 */
	public function getTaxPrice() {

		self::errorReset();
		$this->reload();
		return $this->TaxPrice;
	}

	/**
	 * Returns total price.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Price The total price.
	 * @since 0.1.3
	 */
	public function getTotalPrice() {

		self::errorReset();
		$this->reload();
		return $this->totalPrice;
	}

	/**
	 * Returns the total price without tax.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Price The total price without tax.
	 * @since 0.1.3
	 */
	public function getTotalPriceWithoutTax() {

		self::errorReset();
		$this->reload();
		return $this->totalWithoutTaxPrice;
	}

	/**
	 * Returns the date when it was viewed.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Date The date when the order was viewed.
	 * @since 0.1.3
	 */
	public function getViewDate() {

		self::errorReset();
		$this->reload();
		return $this->viewDate;
	}

	/**
	 * Shows if the order is archived.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if it is archived, false if not.
	 * @since 0.1.3
	 */
	public function isArchived() {

		self::errorReset();
		$this->reload();
		return InputValidator::isEmpty($this->archiveDate) ? false: true;
	}

	/**
	 * Shows if the order is closed.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if it is closed, false if not.
	 * @since 0.1.3
	 */
	public function isClosed() {

		self::errorReset();
		$this->reload();
		return InputValidator::isEmpty($this->closeDate) ? false: true;
	}

	/**
	 * Shows if the order is created.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if it is created, false if not.
	 * @since 0.1.3
	 */
	public function isCreated() {

		self::errorReset();
		$this->reload();
		return InputValidator::isEmpty($this->creationDate) ? false: true;
	}

	/**
	 * Shows if the order is delivered.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if it is delivered, false if not.
	 * @since 0.1.3
	 */
	public function isDelivered() {

		self::errorReset();
		$this->reload();
		return InputValidator::isEmpty($this->deliveryDate) ? false: true;
	}

	/**
	 * Shows if the order is dispatched.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if it is dispatched, false if not.
	 * @since 0.1.3
	 */
	public function isDispatched() {

		self::errorReset();
		$this->reload();
		return InputValidator::isEmpty($this->dispatchDate) ? false: true;
	}

	/**
	 * Shows if the order is invoiced.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if it is invoiced, false if not.
	 * @since 0.1.3
	 */
	public function isInvoiced() {

		self::errorReset();
		$this->reload();
		return InputValidator::isEmpty($this->invoiceDate) ? false: true;
	}

	/**
	 * Shows if the order is paid.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if it is paid, false if not.
	 * @since 0.1.3
	 */
	public function isPaid() {

		self::errorReset();
		$this->reload();
		return InputValidator::isEmpty($this->payDate) ? false: true;
	}

	/**
	 * Shows if the order is pended.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if it is pended, false if not.
	 * @since 0.1.3
	 */
	public function isPended() {

		self::errorReset();
		$this->reload();
		return InputValidator::isEmpty($this->pendingDate) ? false: true;
	}

	/**
	 * Shows if the order is rejected.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if it is rejected, false if not.
	 * @since 0.1.3
	 */
	public function isRejected() {

		self::errorReset();
		$this->reload();
		return InputValidator::isEmpty($this->rejectionDate) ? false: true;
	}

	/**
	 * Shows if the order is returned.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if it is returend, false if not.
	 * @since 0.1.3
	 */
	public function isReturned() {

		self::errorReset();
		$this->reload();
		return InputValidator::isEmpty($this->returnDate) ? false: true;
	}

	/**
	 * Shows if the order is viewed.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if it is viewed, false if not.
	 * @since 0.1.3
	 */
	public function isViewed() {

		self::errorReset();
		$this->reload();
		return InputValidator::isEmpty($this->viewDate) ? false: true;
	}

	/**
	 * Sets the archived on date of the order.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param Date $archivedOn The archivedOn Date.
	 * @since 0.2.0
	 */
	public function setArchiveDate($archivedOn) {

		self::errorReset();

		$this->setAttribute("/archivedOn", $archivedOn->asReadable());
	}
	
	/**
	 * Sets the billing address of the order.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param Address $billingAddress The billing address.
	 * @since 0.2.0
	 */
	public function setBillingAddress($billingAddress) {

		self::errorReset();

		$this->setAttribute("/billingAddress", $billingAddress->asArray());
	}

	/**
	 * Sets the closed on date of the order.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param Date $closedOn The closedOn Date.
	 * @since 0.2.0
	 */
	public function setCloseDate($closedOn) {

		self::errorReset();

		$this->setAttribute("/closedOn", $closedOn->asReadable());
	}

	/**
	 * Sets the customer comment of the order.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $customerComment The new customer comment.
	 * @since 0.2.0
	 */
	public function setCustomerComment($customerComment) {

		self::errorReset();

		$this->setAttribute("/customerComment", $customerComment);
	}

	/**
	 * Sets the delivered on date of the order.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param Date $deliveredOn The deliveredOn Date.
	 * @since 0.2.0
	 */
	public function setDeliveryDate($deliveredOn) {

		self::errorReset();

		$this->setAttribute("/deliveredOn", $deliveredOn->asReadable());
	}

	/**
	 * Sets the dispatched on date of the order.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param Date $dispatchedOn The dispatchedOn Date.
	 * @since 0.2.0
	 */
	public function setDispatchDate($dispatchedOn) {

		self::errorReset();

		$this->setAttribute("/dispatchedOn", $dispatchedOn->asReadable());
	}

	/**
	 * Sets the internal note of the order.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $internalNote The new internal note.
	 * @since 0.2.0
	 */
	public function setInternalNote($internalNote) {

		self::errorReset();

		$this->setAttribute("/internalNote", $internalNote);
	}

	/**
	 * Sets the invoiced on date of the order.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param Date $invoicedOn The invoicedOn Date.
	 * @since 0.2.0
	 */
	public function setInvoiceDate($invoicedOn) {

		self::errorReset();

		$this->setAttribute("/invoicedOn", $invoicedOn->asReadable());
	}

	/**
	 * Sets the paid on date of the order.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param Date $paidOn The paidOn Date.
	 * @since 0.2.0
	 */
	public function setPayDate($paidOn) {

		self::errorReset();

		$this->setAttribute("/paidOn", $paidOn->asReadable());
	}

	/**
	 * Sets the pending on date of the order.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param Date $pendingOn The pendingOn Date.
	 * @since 0.2.0
	 */
	public function setPendingDate($pendingOn) {

		self::errorReset();

		$this->setAttribute("/pendingOn", $pendingOn->asReadable());
	}
	
	/**
	 * Sets the rejected on date of the order.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param Date $rejectedOn The rejectedOn Date.
	 * @since 0.2.0
	 */
	public function setRejectionDate($rejectedOn) {

		self::errorReset();

		$this->setAttribute("/rejectedOn", $rejectedOn->asReadable());
	}

	/**
	 * Sets the returned on date of the order.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param Date $returnedOn The returnedOn Date.
	 * @since 0.2.0
	 */
	public function setReturnDate($returnedOn) {

		self::errorReset();

		$this->setAttribute("/returnedOn", $returnedOn->asReadable());
	}

	/**
	 * Sets the shipping address of the order.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param Address $shippingAddress The shipping address.
	 * @since 0.2.0
	 */
	public function setShippingAddress($shippingAddress) {

		self::errorReset();

		$this->setAttribute("/shippingAddress", $shippingAddress->asArray());
	}

	/**
	 * Sets the viewed on date of the order.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param Date $viewedOn The viewedOn Date.
	 * @since 0.2.0
	 */
	public function setViewDate($viewedOn) {

		self::errorReset();

		$this->setAttribute("/viewedOn", $viewedOn->asReadable());
	}

	/**
	 * Loads the order.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	private function load() {

		// if parameter is wrong or GET is blocked
		if (!RESTClient::setRequestMethod(HTTPRequestMethod::GET)) {

			self::errorSet("RESTC-9");
			return;
		}

		$content = RESTClient::sendWithLocalization(self::RESTPATH . "/" . $this->orderId, Locales::getLocale());

		// if respond is empty
		if (InputValidator::isEmpty($content)) {

			self::errorSet("OF-1");
			return;
		}

		$this->parseData($content);

		// update timestamp when make the next request
		$timestamp = (int) (microtime(true) * 1000);
		$this->NEXT_REQUEST_TIMESTAMP = $timestamp + RESTClient::$NEXT_RESPONSE_WAIT_TIME;
	}

	/**
	 * Parses the REST response data and save it.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param Array $orderParameter The order in an array.
	 * @since 0.1.3
	 */
	private function parseData($orderParameter) {

		// if the product comes from the shop API
		if (InputValidator::isArray($orderParameter) &&
			!InputValidator::isEmptyArrayKey($orderParameter, "orderId")) {

			$this->orderId = $orderParameter['orderId'];

			if (!InputValidator::isEmptyArrayKey($orderParameter, "orderNumber")) {

				$this->orderNumber = $orderParameter['orderNumber'];
			}

			if (!InputValidator::isEmptyArrayKey($orderParameter, "creationDate")) {

				$this->creationDate = new Date($orderParameter['creationDate']);
			}

			if (!InputValidator::isEmptyArrayKey($orderParameter, "billingAddress") &&
				InputValidator::isArray($orderParameter["billingAddress"])) {

				$this->billingAddress = new Address($orderParameter['billingAddress']);
			}

			if (!InputValidator::isEmptyArrayKey($orderParameter, "shippingAddress") &&
				InputValidator::isArray($orderParameter["shippingAddress"])) {

				$this->shippingAddress = new Address($orderParameter['shippingAddress']);
			}

			if (!InputValidator::isEmptyArrayKey($orderParameter, "invoicedOn")) {

				$this->invoiceDate = new Date($orderParameter['invoicedOn']);
			}

			if (!InputValidator::isEmptyArrayKey($orderParameter, "deliveredOn")) {

				$this->deliveryDate = new Date($orderParameter['deliveredOn']);
			}

			if (!InputValidator::isEmptyArrayKey($orderParameter, "pendingOn")) {

				$this->pendingDate = new Date($orderParameter['pendingOn']);
			}

			if (!InputValidator::isEmptyArrayKey($orderParameter, "archivedOn")) {

				$this->archiveDate = new Date($orderParameter['archivedOn']);
			}

			if (!InputValidator::isEmptyArrayKey($orderParameter, "dispatchedOn")) {

				$this->dispatchDate = new Date($orderParameter['dispatchedOn']);
			}

			if (!InputValidator::isEmptyArrayKey($orderParameter, "viewedOn")) {

				$this->viewDate = new Date($orderParameter['viewedOn']);
			}

			if (!InputValidator::isEmptyArrayKey($orderParameter, "rejectedOn")) {

				$this->rejectionDate = new Date($orderParameter["rejectedOn"]);
			}

			if (!InputValidator::isEmptyArrayKey($orderParameter, "closedOn")) {

				$this->closeDate = new Date($orderParameter["closedOn"]);
			}

			if (!InputValidator::isEmptyArrayKey($orderParameter, "paidOn")) {

				$this->payDate = new Date($orderParameter["paidOn"]);
			}

			if (!InputValidator::isEmptyArrayKey($orderParameter, "returnedOn")) {

				$this->returnDate = new Date($orderParameter["returnedOn"]);
			}

			if (!InputValidator::isEmptyArrayKey($orderParameter, "currencyId") &&
				!InputValidator::isEmptyArrayKey($orderParameter, "taxModel")) {

				$priceParameter = array("taxType" => $orderParameter["taxModel"],
										"currency" => $orderParameter["currencyId"]);

				if (!InputValidator::isEmptyArrayKey($orderParameter, "grandTotal")) {

					$priceParameter["amount"] = $orderParameter["grandTotal"];
					$this->totalPrice = new Price($priceParameter);
				}

				if (!InputValidator::isEmptyArrayKey($orderParameter, "totalBeforeTax")) {

					$priceParameter["amount"] = $orderParameter["totalBeforeTax"];
					$this->totalWithoutTaxPrice = new Price($priceParameter);
				}

				if (!InputValidator::isEmptyArrayKey($orderParameter, "totalTax")) {

					$priceParameter["amount"] = $orderParameter["totalTax"];
					$this->taxPrice = new Price($priceParameter);
				}
			}

			if (!InputValidator::isEmptyArrayKey($orderParameter, "customerComment")) {

				$this->customerComment = $orderParameter["customerComment"];
			}

			if (!InputValidator::isEmptyArrayKey($orderParameter, "internalNote")) {

				$this->internalNote = $orderParameter["internalNote"];
			}

			if (!InputValidator::isEmptyArrayKey($orderParameter, "shippingData")) {

				$shippingData = $orderParameter["shippingData"];

				if (!InputValidator::isEmptyArrayKey($shippingData, "shippingMethod" )) {

					$this->shippingMethod = new ShippingMethod($shippingData["shippingMethod"]);
				}

				if (!InputValidator::isEmptyArrayKey($shippingData, "price" )) {

					$this->shippingPrice = new Price($shippingData["price"]);
				}
			}

			if (!InputValidator::isEmptyArrayKey($orderParameter, "paymentData")) {

				$paymentData = $orderParameter["paymentData"];

				if (!InputValidator::isEmptyArrayKey($paymentData, "paymentMethod" )) {

					$this->paymentMethod = new ShippingMethod($paymentData["paymentMethod"]);
				}

				if (!InputValidator::isEmptyArrayKey($paymentData, "price" )) {

					$this->paymentPrice = new Price($paymentData["price"]);
				}
			}
		}

		// update timestamp when make the next request
		$timestamp = (int) (microtime(true) * 1000);
		$this->NEXT_REQUEST_TIMESTAMP = $timestamp + RESTClient::$NEXT_RESPONSE_WAIT_TIME;
	}

 	/**
 	 * This function checks whether a reload is needed.
 	 *
 	 * @author David Pauli <contact@david-pauli.de>
 	 * @since 0.1.3
 	 */
 	private function reload() {

 		$timestamp = (int) (microtime(true) * 1000);

 		// if the value is empty
 		if ($this->NEXT_REQUEST_TIMESTAMP > $timestamp) {
 			return;
 		}

 		$this->load();
 	}

	/**
	 * Sets an attribute of the order.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $path The path to this attribute.
	 * @param String $value The new attribute value.
	 * @since 0.2.0
	 */
	private function setAttribute($path, $value) {

		// if PATCH does not work
		if (!RESTClient::setRequestMethod("PATCH")) {

			self::errorSet("RESTC-9");
			return;
		}

		$parameter = array("op" => "add", "path" => $path, "value" => $value);
		$orderParameter = RESTClient::send(self::RESTPATH . "/" . $this->orderId, $parameter);

		// update the order
		$this->parseData($orderParameter);
	}

	/**
	 * Unsets an attribute value of the order.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $path The path to this attribute.
	 * @since 0.2.0
	 */
	private function unsetAttribute($path) {

		// if PATCH does not work
		if (!RESTClient::setRequestMethod("PATCH")) {

			self::errorSet("RESTC-9");
			return;
		}

		$parameter = array("op" => "remove", "path" => $path);
		$productParameter = RESTClient::send(self::RESTPATH, $parameter);

		// update the product
		$this->parseData($productParameter);
	}
}
?>