<?php
/**
 * This file represents the product filter class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 */
namespace ep6;
/**
 * This is a product filter class to search products via the REST call "product".
 *
 * @author David Pauli <contact@david-pauli.de>
 * @package ep6
 * @since 0.0.0
 * @since 0.1.0 Use a default Locale and Currency.
 * @since 0.1.1 The object can be echoed now.
 * @since 0.1.2 Add error reporting.
 * @since 0.1.3 Add all results attribute.
 * @subpackage Shopobjects\Product
 */
class ProductFilter {

	use ErrorReporting;

	/** @var String The REST path to the filter ressource. */
	const RESTPATH = "products";

	/** @var String|null The category id of the product search result. */
	private $categoryID;

	/** @var String|null The sort direction of the product search result. */
	private $direction;

	/** @var The filter of the Product Filter. */
	private $filters = array();

	/** @var String[] The product ids of the product search result. */
	private $IDs = array();

	/** @var int The page of the product search result. */
	private $page = 1;

	/** @var String|null The search string of the product search result. */
	private $q;

	/** @var int|null The number of all results. */
	private $results = null;

	/** @var int The number of results per page of the product search result. */
	private $resultsPerPage = 10;

	/** @var String The variable to sort the results of the product search result. */
	private $sort = "name";

	/**
	 * This is the constructor to prefill the product filter.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String[] $productFilterParameter The values of a product filter.
	 * @since 0.0.1
	 */
	public function __construct($productFilterParameter = array()) {

		if (InputValidator::isArray($productFilterParameter) &&
			!InputValidator::isEmptyArray($productFilterParameter)) {

			$this->setProductFilter($productFilterParameter);
		}
	}

	/**
	 * Prints the Product Filter object as a string.
	 *
	 * This function returns the setted values of the Product attribute object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The Product attribute as a string.
	 * @since 0.1.1
	 */
	public function __toString() {

		return "<strong>Page:</strong> " . $this->page . "<br/>" .
				"<strong>Results per page:</strong> " . $this->resultsPerPage . "<br/>" .
				"<strong>Direction:</strong> " . $this->direction . "<br/>" .
				"<strong>Sort:</strong> " . $this->sort . "<br/>" .
				"<strong>Search string:</strong> " . $this->q . "<br/>" .
				"<strong>Category ID:</strong> " . $this->categoryID . "<br/>" .
				"<strong>Product IDs:</strong> " . print_r($this->IDs) . "<br/>";
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
			$this->errorSet("PF-10");
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
	 * This function gets the category ID string.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The category ID string of this product filter.
	 * @since 0.0.0
	 * @since 0.1.0 Use attribute unstatic.
	 * @since 0.1.2 Add error reporting.
	 */
	public function getCategoryID() {

		$this->errorReset();

		return $this->categoryID;
	}

	/**
	 * This function gets the direction.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The direction of this product filter.
	 * @since 0.0.0
	 * @since 0.1.0 Use attribute unstatic.
	 * @since 0.1.2 Add error reporting.
	 */
	public function getDirection() {

		$this->errorReset();

		return $this->direction;
	}

	/**
	 * This function gets the page.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return int The page number of this product filter.
	 * @since 0.0.0
	 * @since 0.1.0 Use attribute unstatic.
	 * @since 0.1.2 Add error reporting.
	 */
	public function getPage() {

		$this->errorReset();

		return $this->page;
	}

	/**
	 * This function returns the products by using the product filter.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a default Locale.
	 * @since 0.1.1 Unstatic every attributes.
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Get all results.
	 * @since 0.2.0 Set error message for empty responses to notify.
	 * @return Product[] Returns an array of products.
	 */
	public function getProducts() {

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

			$this->errorSet("PF-8");
		    Logger::notify("ep6\ProductFilter\nREST respomd for getting products is empty.");
			return;
		}

		// if there is no results, page AND resultsPerPage element
		if (InputValidator::isEmptyArrayKey($content, "results") ||
			InputValidator::isEmptyArrayKey($content, "page") ||
			InputValidator::isEmptyArrayKey($content, "resultsPerPage")) {

			$this->errorSet("PF-9");
		    Logger::error("ep6\ProductFilter\nRespond for " . self::RESTPATH . " can not be interpreted.");
			return;
		}

		$this->results = $content['results'];

		$products = array();

		// is there any product found: load the products.
	 	if (!InputValidator::isEmptyArrayKey($content, "items") && (sizeof($content['items']) != 0)) {

			foreach ($content['items'] as $item) {

				$product = new Product($item);

				// go to every filter
				foreach ($this->filters as $filter) {

					switch ($filter->getAttribute()) {

						case 'stocklevel':
							$value = array();
							$value["stocklevel"] = $product->getStocklevel();
							break;

						case 'price':
							$value = array();
							$value["price"] = $product->getPrice()->getAmount();
							break;

						case 'category':
							$value = array();
							$value["category"] = $product->getCategories();
							break;

						default:
							$value = $item;
							break;

					}

					if (!InputValidator::isEmptyArrayKey($value, $filter->getAttribute()) || $filter->getOperator() == FilterOperation::UNDEF) {

						if (!InputValidator::isArray($value[$filter->getAttribute()])) {

							if (!$filter->isElementInFilter($value)) {
								continue 2;
							}
						}
					}
					else {
						continue 2;
					}
				}

				array_push($products, $product);
			}
	 	}

		return $products;
	}

	/**
	 * This function gets the query search string.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The query search string of this product filter.
	 * @since 0.0.0
	 * @since 0.1.0 Use attribute unstatic.
	 * @since 0.1.2 Add error reporting.
	 */
	public function getQ() {

		$this->errorReset();

		return $this->q;
	}

	/**
	 * This function gets all results.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return int The results of the request.
	 * @since 0.1.3
	 */
	public function getResults() {

		$this->errorReset();

		return $this->results;
	}

	/**
	 * This function gets the results per page.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return int The results per page number of this product filter.
	 * @since 0.0.0
	 * @since 0.1.0 Use attribute unstatic.
	 * @since 0.1.2 Add error reporting.
	 */
	public function getResultsPerPage() {

		$this->errorReset();

		return $this->resultsPerPage;
	}

	/**
	 * This function gets the sort.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The sort of this product filter.
	 * @since 0.0.0
	 * @since 0.1.0 Use attribute unstatic.
	 * @since 0.1.2 Add error reporting.
	 */
	public function getSort() {

		$this->errorReset();

		return $this->sort;
	}

	/**
	 * This function returns the hash code of the object to equals the object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String Returns the hash code of the object.
	 * @since 0.0.0
	 * @since 0.1.0 Use a default Locale and Currency.
	 * @since 0.1.2 Add error reporting.
	 */
	public function hashCode() {

		$this->errorReset();

		$message = $this->page
			. $this->resultsPerPage
			. $this->direction
			. $this->sort
			. $this->q
			. $this->categoryID;

		foreach ($this->IDs as $id) {

			$message .= $id;
		}

		return hash("sha512", $message);
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
		$this->direction = null;
		$this->sort = "name";
		$this->q = null;
		$this->categoryID = null;
		$this->IDs = array();
	}

	/**
	 * This function reset all product IDs from filter.
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use attribute unstatic.
	 * @since 0.1.2 Add error reporting.
	 */
	public function resetIDs() {

		$this->errorReset();
		$this->IDs = array();
	}

	/**
	 * This function sets the category ID to show.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $categoryID The category ID to filter.
	 * @return boolean True if setting the category ID string works, false if not.
	 * @since 0.0.0
	 * @since 0.1.0 Use attribute unstatic.
	 * @since 0.1.2 Add error reporting.
	 */
	public function setCategoryID($categoryID) {

		$this->errorReset();

		if (InputValidator::isEmpty($categoryID)) {

			return false;
		}

		$this->categoryID = $categoryID;
		return true;
	}

	/**
	 * This function sets the direction to show.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $direction The direction to filter.
	 * @return boolean True if setting the direction works, false if not.
	 * @since 0.0.0
	 * @since 0.1.0 Use attribute unstatic.
	 * @since 0.1.2 Add error reporting.
	 */
	public function setDirection($direction) {

		$this->errorReset();

		if (!InputValidator::isProductDirection($direction)) {

			$this->errorSet("PF-5");
			Logger::warning("The direction " . $direction . " as a product filter direction has not a valid value.");
			return false;
		}

		$this->direction = $direction;
		return true;
	}

	/**
	 * This function add a product ID from filter.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $productID The product ID to filter.
	 * @return boolean True if setting the product ID string works, false if not.
	 * @since 0.0.0
	 * @since 0.1.0 Use attribute unstatic.
	 * @since 0.1.2 Add error reporting.
	 */
	public function setID($productID) {

		$this->errorReset();

		if (InputValidator::isEmpty($productID)
			|| count($this->IDs) > 12
			|| in_array($productID, $this->IDs)) {

			if (count($this->IDs) > 12) {

				$this->errorSet("PF-7");
				Logger::warning("ep6\ProductFilter\nThere are already 12 product IDs to filter. To add more delete one.");
			}

			return false;
		}

		array_push($this->IDs, $productID);
		return true;
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

			$this->errorSet("PF-3");
			Logger::warning("ep6\ProductFilter\nThe number " . $page . " as a product filter page needs to be bigger than 0.");
			return false;
		}

		$this->page = $page;
		return true;
	}

	/**
	 * Fill the product filter with an array.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param mixed[] $productFilterParameter The Product Filter Parameter as an array.
	 * @since 0.0.1
	 * @since 0.1.0 Use a default Locale and Currency.
	 * @since 0.1.2 Add error reporting.
	 */
	public function setProductFilter($productFilterParameter) {

		$this->errorReset();

		if (!InputValidator::isArray($productFilterParameter) ||
			InputValidator::isEmptyArray($productFilterParameter)) {

			$this->errorSet("PF-1");
			Logger::warning("ep6\ProductFilter\nProduct filter parameter " . $productFilterParameter . " to create product filter is invalid.");
			return;
		}

		foreach ($productFilterParameter as $key => $parameter) {

			if($key == "page") {

				$this->setPage($parameter);
			}
			else if($key == "resultsPerPage") {

				$this->setResultsPerPage($parameter);
			}
			else if($key == "direction") {

				$this->setDirection($parameter);
			}
			else if($key == "sort") {

				$this->setSort($parameter);
			}
			else if($key == "q") {

				$this->setQ($parameter);
			}
			else if($key == "categoryID") {

				$this->setCategoryID($parameter);
			}
			else {

				$this->errorSet("PF-2");
				Logger::warning("ep6\ProductFilter\nUnknown attribute <i>" . $key . "</i> in product filter attribute.");
			}
		}
	}

	/**
	 * This function sets the query search string to show.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $q The query search string to filter.
	 * @return boolean True if setting the query search string works, false if not.
	 * @since 0.0.0
	 * @since 0.1.0 Use attribute unstatic.
	 * @since 0.1.2 Add error reporting.
	 */
	public function setQ($q) {

		$this->errorReset();

		if (InputValidator::isEmpty($q)) {

			return false;
		}

		$this->q = $q;

		return true;
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

			$this->errorSet("PF-4");
			Logger::warning("ep6\ProductFilter\The number " . $resultsPerPage . " as a product filter results per page needs to be lower than 100.");
			return false;
		}

		$this->resultsPerPage = $resultsPerPage;

		return true;
	}

	/**
	 * This function sets the order parameter to show.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $sort The sort parameter to filter.
	 * @return boolean True if setting the sort parameter works, false if not.
	 * @since 0.0.0
	 * @since 0.1.0 Use attribute unstatic.
	 * @since 0.1.2 Add error reporting.
	 */
	public function setSort($sort) {

		$this->errorReset();

		if (!InputValidator::isProductSort($sort)) {

			$this->errorSet("PF-6");
			Logger::warning("ep6\ProductFilter\nThe parameter " . $sort . " as a product filter sort has not a valid value.");
			return false;
		}

		$this->sort = $sort;

		return true;
	}

	/**
	 * This function delete a product ID from filter.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $productID	The product ID to unset from filter.
	 * @return boolean True if unsetting the product ID string works, false if not.
	 * @since 0.0.0
	 * @since 0.1.0 Use attribute unstatic.
	 * @since 0.1.2 Add error reporting.
	 */
	public function unsetID($productID) {

		$this->errorReset();

		if (InputValidator::isEmpty($productID)
			|| !in_array($productID, $this->IDs)) {

			return false;
		}

		unset($this->IDs[array_search($productID, $this->IDs)]);

		return true;
	}

	/**
	 * This function returns the parameter as string.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The parameter build with this product filter.
	 * @since 0.0.0
	 * @since 0.1.0 Use a default Locale and Currency.
	 */
	private function getParameter() {

		$parameter = array();
		array_push($parameter, "locale=" . Locales::getLocale());
		array_push($parameter, "currency=" . Currencies::getCurrency());

		if (!InputValidator::isEmpty($this->page)) array_push($parameter, "page=" . $this->page);
		if (!InputValidator::isEmpty($this->resultsPerPage)) array_push($parameter, "resultsPerPage=" . $this->resultsPerPage);
		if (!InputValidator::isEmpty($this->direction)) array_push($parameter, "direction=" . $this->direction);
		if (!InputValidator::isEmpty($this->sort)) array_push($parameter, "sort=" . $this->sort);
		if (!InputValidator::isEmpty($this->q)) array_push($parameter, "q=" . $this->q);
		if (!InputValidator::isEmpty($this->categoryID)) array_push($parameter, "categoryId=" . $this->categoryID);

		foreach ($this->IDs as $number => $id) {

			array_push($parameter, "id=" . $id);
		}

		return implode("&", $parameter);
	}
}
?>