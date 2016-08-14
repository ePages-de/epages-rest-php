<?php
/**
 * This file represents the Order Filter class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
/**
 * This is a Order Filter class to search orders via the REST call "orders".
 *
 * @author David Pauli <contact@david-pauli.de>
 * @package ep6
 * @since 0.1.3
 * @subpackage Shopobjects\Order
 */
class OrderFilter {

	use ErrorReporting;

	/** @var String The REST path to the filter ressource. */
	const RESTPATH = "orders";

	/** @var Date|null Search for orders which are created after this date. */
	private $createdAfterDate = null;

	/** @var Date|null Search for orders which are created before this date. */
	private $createdBeforeDate = null;

	/** @var String|null Search orders made by this customer ID. */
	private $customerId = null;

	/** @var The filter of the Order Filter. */
	private $filters = array();

	/** @var boolean Show orders which are already archived. */
	private $isArchived = null;

	/** @var boolean Show orders which are already closed. */
	private $isClosed = null;

	/** @var boolean Show orders which are already delivered. */
	private $isDelivered = null;

	/** @var boolean Show orders which are already dispatched. */
	private $isDispatched = null;

	/** @var boolean Show orders which are already invoiced. */
	private $isInvoiced = null;

	/** @var boolean Show orders which are already paid. */
	private $isPaid = null;

	/** @var boolean Show orders which are already pending. */
	private $isPending = null;

	/** @var boolean Show orders which are already rejected. */
	private $isRejected = null;

	/** @var boolean Show orders which are already returned. */
	private $isReturned = null;

	/** @var boolean Show orders which are already viewed. */
	private $isViewed = null;

	/** @var Date|null Show orders which are updated since this date. */
	private $lastUpdateDate = null;

	/** @var int The page number to search. */
	private $page = 1;

	/** @var String|null Search orders with this product ID in there. */
	private $productId = null;

	/** @var int|null The number of all results. */
	private $results = null;

	/** @var int The number of elements per page. */
	private $resultsPerPage = 10;

	/** @var boolean|null The attribute to sort by last update time. */
	private $sortLastUpdate = null;

	/**
	 * Prints the Order Filter object as a string.
	 *
	 * This function returns the setted values of the Order Filter object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The Order Filter as a string.
	 * @since 0.1.1
	 */
	public function __toString() {

		return "<strong>Page:</strong> " . $this->page . "<br/>" .
				"<strong>Results per page:</strong> " . $this->resultsPerPage . "<br/>" .
				"<strong>Customer ID:</strong> " . $this->customerId . "<br/>" .
				"<strong>'Created after' date:</strong> " . $this->createdAfterDate . "<br/>" .
				"<strong>'Created before' date:</strong> " . $this->createdBeforeDate . "<br/>" .
				"<strong>Is archived:</strong> " . $this->archivedDate . "<br/>" .
				"<strong>Is closed:</strong> " . $this->closedDate . "<br/>" .
				"<strong>Is delivered:</strong> " . $this->deliveredDate . "<br/>" .
				"<strong>Is dispatched:</strong> " . $this->dispatchedDate . "<br/>" .
				"<strong>Is invoiced:</strong> " . $this->invoicedDate . "<br/>" .
				"<strong>Is paid:</strong> " . $this->paidDate . "<br/>" .
				"<strong>Is pending:</strong> " . $this->pendingDate . "<br/>" .
				"<strong>Is rejected:</strong> " . $this->rejectedDate . "<br/>" .
				"<strong>Is returned:</strong> " . $this->returnedDate . "<br/>" .
				"<strong>'Last update' date:</strong> " . $this->lastUpdateDate . "<br/>" .
				"<strong>Product ID:</strong> " . $this->productId . "<br/>" .
				"<strong>Sort for last update:</strong> " . $this->sortLastUpdate . "<br/>" .
				"<strong>Is closed:</strong> " . $this->isClosed . "<br/>";
	}

	/**
	 * This is the function to add a filter.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $attribute The attribute to filter.
	 * @param String $value The value to filter and compare.
	 * @param FilterOperation $operator The operation to do.
	 * @param String $type The type of the parameter, like "bool".
	 * @return boolean
	 * @since 0.1.3
	 */
	public function addFilter($attribute, $value, $operator, $type) {

		$this->errorReset();

		if (InputValidator::isEmpty($attribute) || InputValidator::isEmpty($value) || InputValidator::isEmpty($operator)) {
			$this->errorSet("OF-6");
			return false;
		}

		$filterParameter = array("attribute"	=> $attribute,
						"value"			=> $value,
						"operator"		=> $operator,
						"type"		=> $type);

		array_push($this->filters, new Filter($filterParameter));

		return true;
	}

	/**
	 * This function gets the created after date.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Date The created after date string of this order filter.
	 * @since 0.1.3
	 */
	public function getCreatedAfterDate() {

		$this->errorReset();

		return $this->createdAfterDate;
	}

	/**
	 * This function gets the created before date.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Date The created before date of this order filter.
	 * @since 0.1.3
	 */
	public function getCreatedBeforeDate() {

		$this->errorReset();

		return $this->createdBeforeDate;
	}

	/**
	 * This function gets the customer ID string.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The customer ID of this order filter.
	 * @since 0.1.3
	 */
	public function getCustomerID() {

		$this->errorReset();

		return $this->categoryID;
	}

	/**
	 * This function gets the last update date.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Date The last update date of this order filter.
	 * @since 0.1.3
	 */
	public function getLastUpdateDate() {

		$this->errorReset();

		return $this->lastUpdateDate;
	}

	/**
	 * This function returns the orders by using the order filter.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 * @since 0.2.0 Set error message for empty responses to notify.
	 * @return Order[] Returns an array of orders.
	 */
	public function getOrders() {

		$this->errorReset();

		$parameter = $this->getParameter();

		// if request method is blocked
		if (!RESTClient::setRequestMethod(HTTPRequestMethod::GET)) {

			$this->errorSet("RESTC-9");
			return;
		}

		$content = RESTClient::send(self::RESTPATH . "?" . $parameter);

		// if respond is empty
		if (InputValidator::isEmpty($content)) {

			$this->errorSet("OF-1");
		    Logger::notify("ep6\OrderFilter\nREST respond for getting orders is empty.");
			return;
		}

		// if there is no results, page AND resultsPerPage element
		if (InputValidator::isEmptyArrayKey($content, "results") ||
			InputValidator::isEmptyArrayKey($content, "page") ||
			InputValidator::isEmptyArrayKey($content, "resultsPerPage")) {

			$this->errorSet("OF-2");
		    Logger::error("ep6\OrderFilter\nRespond for " . self::RESTPATH . " can not be interpreted.");
			return;
		}

		$this->results = $content['results'];

		$orders = array();

		// is there any order found: load the products.
	 	if (!InputValidator::isEmptyArrayKey($content, "items") && (sizeof($content['items']) != 0)) {

			foreach ($content['items'] as $item) {

				$order = new Order($item);

				// go to every filter
				foreach ($this->filters as $filter) {

					if (!InputValidator::isEmptyArrayKey($item, $filter->getAttribute()) || $filter->getOperator() == FilterOperation::UNDEF) {

						if (!InputValidator::isArray($item[$filter->getAttribute()])) {

							if (!$filter->isElementInFilter($item)) {
								continue 2;
							}
						}
					}
					else {
						continue 2;
					}
				}

				array_push($orders, $order);
			}
	 	}

		return $orders;
	}

	/**
	 * This function gets the page.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return iint The page of this order filter.
	 * @since 0.1.3
	 */
	public function getPage() {

		$this->errorReset();

		return $this->page;
	}

	/**
	 * This function gets the product ID.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return Stringt The product ID of this order filter.
	 * @since 0.1.3
	 */
	public function getProductID() {

		$this->errorReset();

		return $this->productId;
	}

	/**
	 * This function gets the results per page.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return int The results per page of this order filter.
	 * @since 0.1.3
	 */
	public function getResultsPerPage() {

		$this->errorReset();

		return $this->resultsPerPage;
	}

	/**
	 * This function returns the hash code of the object to equals the object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String Returns the hash code of the object.
	 * @since 0.1.3
	 */
	public function hashCode() {

		$this->errorReset();

		$message = $this->page
			. $this->resultsPerPage
			. $this->customerId
			. $this->createdAfterDate
			. $this->createdBeforeDate
			. $this->archivedDate
			. $this->closedDate
			. $this->deliveredDate
			. $this->dispatchDate
			. $this->invoicedDate
			. $this->paidDate
			. $this->pendingDate
			. $this->rejectedDate
			. $this->returnedDate
			. $this->lastUpdateDate
			. $this->productId
			. $this->sortLastUpdate
			. $this->isClosed;

		return hash("sha512", $message);
	}

	/**
	 * This function gets whether orders are filtered which are archived.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if orders are filtered which are archived, false if not.
	 * @since 0.1.3
	 */
	public function isArchived() {

		$this->errorReset();

		return $this->isArchived;
	}

	/**
	 * This function gets whether orders are filtered which are closed.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if orders are filtered which are closed, false if not.
	 * @since 0.1.3
	 */
	public function isClosed() {

		$this->errorReset();

		return $this->isClosed;
	}

	/**
	 * This function gets whether orders are filtered which are delivered.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if orders are filtered which are delivered, false if not.
	 * @since 0.1.3
	 */
	public function isDelivered() {

		$this->errorReset();

		return $this->isDelivered;
	}

	/**
	 * This function gets whether orders are filtered which are dispatched.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if orders are filtered which are dispatched, false if not.
	 * @since 0.1.3
	 */
	public function isDispatched() {

		$this->errorReset();

		return $this->isDispatched;
	}

	/**
	 * This function gets whether orders are filtered which are invoiced.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if orders are filtered which are invoiced, false if not.
	 * @since 0.1.3
	 */
	public function isInvoiced() {

		$this->errorReset();

		return $this->isInvoiced;
	}

	/**
	 * This function gets whether orders are filtered which are paid.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if orders are filtered which are paid, false if not.
	 * @since 0.1.3
	 */
	public function isPaid() {

		$this->errorReset();

		return $this->isPaid;
	}

	/**
	 * This function gets whether orders are filtered which are pending.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if orders are filtered which are pending, false if not.
	 * @since 0.1.3
	 */
	public function isPending() {

		$this->errorReset();

		return $this->isPending;
	}

	/**
	 * This function gets whether orders are filtered which are rejected.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if orders are filtered which are rejected, false if not.
	 * @since 0.1.3
	 */
	public function isRejected() {

		$this->errorReset();

		return $this->isRejected;
	}

	/**
	 * This function gets whether orders are filtered which are returned.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if orders are filtered which are returned, false if not.
	 * @since 0.1.3
	 */
	public function isReturned() {

		$this->errorReset();

		return $this->isReturned;
	}

	/**
	 * This function gets whether orders are sorted since their last update.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if orders are sorted by their last update, false if not.
	 * @since 0.1.3
	 */
	public function isSortLastUpdate() {

		$this->errorReset();

		return $this->sortLastUpdate;
	}

	/**
	 * This function gets whether orders are filtered which are viewed.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if orders are filtered which are viewed, false if not.
	 * @since 0.1.3
	 */
	public function isViewed() {

		$this->errorReset();

		return $this->isViewed;
	}

	/**
	 * This function reset all product IDs from filter.
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a default Locale and Currency.
	 * @since 0.1.2 Add error reporting.
	 */
	public function resetFilter() {

		$this->errorReset();

		$this->page = 1;
		$this->resultsPerPage = 10;
		$this->customerId = null;
		$this->createdAfterDate = null;
		$this->createdBeforeDate = null;
		$this->isArchived = null;
		$this->isClosed = null;
		$this->isDelivered = null;
		$this->isDispatched = null;
		$this->isInvoiced = null;
		$this->isPaid = null;
		$this->isPending = null;
		$this->isRejected = null;
		$this->isReturned = null;
		$this->isViewed = null;
		$this->lastUpdateDate = null;
		$this->productId = null;
		$this->sortLastUpdate = null;
	}

	/**
	 * This function activates to find orders which are archived.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function setArchived() {

		$this->errorReset();
		$this->isArchived = true;
	}

	/**
	 * This function activates to find orders which are closed.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function setClosed() {

		$this->errorReset();
		$this->isClosed = true;
	}

	/**
	 * This function sets the 'Created after' date to search.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param Date $createdAfterDate The 'Created after' date to filter.
	 * @return boolean True if setting works, false if not.
	 * @since 0.1.3
	 */
	public function setCreatedAfterDate($createdAfterDate) {

		$this->errorReset();

		$date = new Date($createdAfterDate);
		if($date->error()) {

			return false;
		}

		$this->date = $date;
		return true;
	}

	/**
	 * This function sets the 'Created before' date to search.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param Date $createdBeforeDate The 'Created before' date to filter.
	 * @return boolean True if setting works, false if not.
	 * @since 0.1.3
	 */
	public function setCreatedBeforeDate($createdBeforeDate) {

		$this->errorReset();

		$date = new Date($createdBeforeDate);
		if($date->error()) {

			return false;
		}

		$this->date = $date;
		return true;
	}

	/**
	 * This function sets the customer ID to search.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $customerId The customer ID to filter.
	 * @return boolean True if setting works, false if not.
	 * @since 0.1.3
	 */
	public function setCustomerId($customerId) {

		$this->errorReset();

		if (InputValidator::isEmpty($customerId)) {

			return false;
		}

		$this->customerId = $customerId;
		return true;
	}

	/**
	 * This function activates to find orders which are delivered.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function setDelivered() {

		$this->errorReset();
		$this->isDelivered = true;
	}

	/**
	 * This function activates to find orders which are dispatched.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function setDispatched() {

		$this->errorReset();
		$this->isDispatched = true;
	}

	/**
	 * This function activates to find orders which are invoiced.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function setInvoiced() {

		$this->errorReset();
		$this->isInvoiced = true;
	}

	/**
	 * This function sets the page to show.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param int $page The page number to filter.
	 * @return boolean True if setting the page works, false if not.
	 * @since 0.0.0
	 * @since 0.1.0 Use attribute unstatic.
	 * @since 0.1.2 Add error reporting.
	 */
	public function setPage($page) {

		$this->errorReset();

		if (!InputValidator::isRangedInt($page, 1)) {

			$this->errorSet("OF-4");
			Logger::warning("ep6\OrderFilter\nThe number " . $page . " as a order filter page needs to be bigger than 0.");
			return false;
		}

		$this->page = $page;
		return true;
	}

	/**
	 * This function activates to find orders which are paid.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function setPaid() {

		$this->errorReset();
		$this->isPaid = true;
	}

	/**
	 * This function activates to find orders which are pending.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function setPending() {

		$this->errorReset();
		$this->isPending = true;
	}

	/**
	 * This function sets the product ID to search.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $productId The product ID to filter.
	 * @return boolean True if setting works, false if not.
	 * @since 0.2.0
	 */
	public function setProductId($productId) {

		$this->errorReset();

		if (InputValidator::isEmpty($productId)) {

			return false;
		}

		$this->productId = $productId;
		return true;
	}

	/**
	 * This function activates to find orders which are rejected.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function setRejected() {

		$this->errorReset();
		$this->isRejected = true;
	}

	/**
	 * This function activates to find orders which are returned.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function setReturned() {

		$this->errorReset();
		$this->isReturned = true;
	}

	/**
	 * This function sets the results per page to show.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param int $resultsPerPage The results per page to filter.
	 * @return boolean True if setting the results per page works, false if not.
	 * @since 0.0.0
	 * @since 0.1.0 Use attribute unstatic.
	 * @since 0.1.2 Add error reporting.
	 */
	public function setResultsPerPage($resultsPerPage) {

		$this->errorReset();

		if (!InputValidator::isRangedInt($resultsPerPage, null, 100)) {

			$this->errorSet("OF-5");
			Logger::warning("ep6\OrderFilter\The number " . $resultsPerPage . " as a order filter results per page needs to be lower than 100.");
			return false;
		}

		$this->resultsPerPage = $resultsPerPage;

		return true;
	}

	/**
	 * This function activates to find orders which are viewed.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function setViewed() {

		$this->errorReset();
		$this->isViewed = true;
	}

	/**
	 * This function deactivates to find orders which are archived.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function unsetArchived() {

		$this->errorReset();
		$this->isArchived = false;
	}

	/**
	 * This function deactivates to find orders which are closed.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function unsetClosed() {

		$this->errorReset();
		$this->isClosed = false;
	}

	/**
	 * This function deactivates to find orders which are delivered.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function unsetDelivered() {

		$this->errorReset();
		$this->isDelivered = false;
	}

	/**
	 * This function deactivates to find orders which are dispatched.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function unsetDispatched() {

		$this->errorReset();
		$this->isDispatched = false;
	}

	/**
	 * This function deactivates to find orders which are invoiced.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function unsetInvoiced() {

		$this->errorReset();
		$this->isInvoiced = false;
	}

	/**
	 * This function deactivates to find orders which are paid.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function unsetPaid() {

		$this->errorReset();
		$this->isPaid = false;
	}

	/**
	 * This function deactivates to find orders which are pending.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function unsetPending() {

		$this->errorReset();
		$this->isPending = false;
	}

	/**
	 * This function deactivates to find orders which are rejected.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function unsetRejected() {

		$this->errorReset();
		$this->isRejected = false;
	}

	/**
	 * This function deactivates to find orders which are returned.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function unsetReturned() {

		$this->errorReset();
		$this->isReturned = false;
	}

	/**
	 * This function deactivates to find orders which are viewed.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.3
	 */
	public function unsetViewed() {

		$this->errorReset();
		$this->isViewed = false;
	}

	/**
	 * This function returns the parameter as string.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The parameter build with this order filter.
	 * @since 0.1.3
	 */
	private function getParameter() {

		$parameter = array();
		array_push($parameter, "locale=" . Locales::getLocale());
		array_push($parameter, "currency=" . Currencies::getCurrency());

		if (!InputValidator::isEmpty($this->createdAfterDate)) array_push($parameter, "createdeAfter=" . $this->createdAfterDate->getTimestamp());
		if (!InputValidator::isEmpty($this->createdBeforeDate)) array_push($parameter, "createdBefore=" . $this->createdBeforeDate->getTimestamp());
		if (!InputValidator::isEmpty($this->customerId)) array_push($parameter, "customerId=" . $this->customerId);
		if (!InputValidator::isEmpty($this->isArchived)) array_push($parameter, "archivedOn=" . $this->isArchived);
		if (!InputValidator::isEmpty($this->isClosed)) array_push($parameter, "closedOn=" . $this->isClosed);
		if (!InputValidator::isEmpty($this->isDelivered)) array_push($parameter, "deliveredOn=" . $this->isDelivered);
		if (!InputValidator::isEmpty($this->isDispatched)) array_push($parameter, "dispatchedOn=" . $this->isDispatched);
		if (!InputValidator::isEmpty($this->isInvoiced)) array_push($parameter, "invoicedOn=" . $this->isInvoiced);
		if (!InputValidator::isEmpty($this->isPaid)) array_push($parameter, "paidOn=" . $this->isPaid);
		if (!InputValidator::isEmpty($this->isPending)) array_push($parameter, "pendingOn=" . $this->isPending);
		if (!InputValidator::isEmpty($this->isRejected)) array_push($parameter, "rejectedOn=" . $this->isRejected);
		if (!InputValidator::isEmpty($this->isReturned)) array_push($parameter, "returnedOn=" . $this->isReturned);
		if (!InputValidator::isEmpty($this->isViewed)) array_push($parameter, "viewedOn=" . $this->isViewed);
		if (!InputValidator::isEmpty($this->lastUpdateDate)) array_push($parameter, "lastUpdateDate=" . $this->lastUpdateDate);
		if (!InputValidator::isEmpty($this->page)) array_push($parameter, "page=" . $this->page);
		if (!InputValidator::isEmpty($this->productId)) array_push($parameter, "productId=" . $this->productId);
		if (!InputValidator::isEmpty($this->resultsPerPage)) array_push($parameter, "resultsPerPage=" . $this->resultsPerPage);
		if (!InputValidator::isEmpty($this->sortLastUpdate)) array_push($parameter, "sortLastUpdate=" . $this->sortLastUpdate);

		return implode("&", $parameter);
	}
}
?>