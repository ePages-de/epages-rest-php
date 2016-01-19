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
 * @since 0.0.0
 * @since 0.1.0 Use a default Locale and Currency.
 * @package ep6
 * @subpackage Shopobjects\Product
 * @example examples\createProductFilter.php Create and use the product filter.
 */
class ProductFilter {

	/** @var String The REST path to the filter ressource. */
	const RESTPATH = "products";

	/** @var int The page of the product search result. */
	private $page = 1;

	/** @var int The number of results per page of the product search result. */
	private $resultsPerPage = 10;

	/** @var String|null The sort direction of the product search result. */
	private $direction;

	/** @var String The variable to sort the results of the product search result. */
	private $sort = "name";

	/** @var String|null The search string of the product search result. */
	private $q;

	/** @var String|null The category id of the product search result. */
	private $categoryID;

	/** @var String[] The product ids of the product search result. */
	private $IDs = array();

	/**
	 * This is the constructor to prefill the product filter.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.1
	 * @api
	 * @param String[] $productFilterParameter The values of a product filter.
	 */
	public function __construct($productFilterParameter = array()) {

		if (InputValidator::isArray($productFilterParameter) &&
			!InputValidator::isEmptyArray($productFilterParameter)) {
			$this->setProductFilter($productFilterParameter);
		}
	}

	/**
	 * Fill the product filter with a array.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.1
	 * @since 0.1.0 Use a default Locale and Currency.
	 * @api
	 * @param String[] $productFilterParameter The values of a product filter.
	 */
	public function setProductFilter($productFilterParameter) {

		if (!InputValidator::isArray($productFilterParameter) ||
			InputValidator::isEmptyArray($productFilterParameter)) {
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
				Logger::warning("Unknown attribute <i>" . $key . "</i> in product filter attribute.");
			}
		}
	}

	/**
	 * This function prints the filter in a NOTIFICATION message.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a default Locale and Currency.
	 * @api
	 */
	public function printFilter() {

		$message = array();
		if (!InputValidator::isEmpty($this->page)) array_push($message, "Page: " . $this->page);
		if (!InputValidator::isEmpty($this->resultsPerPage)) array_push($message, "Results per page: " . $this->resultsPerPage);
		if (!InputValidator::isEmpty($this->direction)) array_push($message, "Direction: " . $this->direction);
		if (!InputValidator::isEmpty($this->sort)) array_push($message, "Sort: " . $this->sort);
		if (!InputValidator::isEmpty($this->q)) array_push($message, "Search string: " . $this->q);
		if (!InputValidator::isEmpty($this->categoryID)) array_push($message, "Category ID: " . $this->categoryID);
		foreach ($this->IDs as $number => $id) {
			array_push($message, "Product id" . $number . ": " . $id);
		}
		Logger::force($message);
	}

	/**
	 * This function returns the hash code of the object to equals the object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a default Locale and Currency.
	 * @api
	 * @return String Returns the hash code of the object.
	 */
	public function hashCode() {

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
	 * This function sets the localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Deprecate because product filter now use shop configured Locale.
	 * @api
	 * @deprecated
	 * @param String $locale The localiazion to filter.
	 * @return boolean True if setting the locale works, false if not.
	 */
	public function setLocale($locale) {
		return false;
	}

	/**
	 * This function gets the localization.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Deprecate because product filter now use shop configured Locale.
	 * @api
	 * @deprecated
	 * @return String The localization of this product filter.
	 */
	public function getLocale() {
		return Locales::getLocale();
	}

	/**
	 * This function sets the currency.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Deprecate because product filter now use shop configured Currency.
	 * @api
	 * @deprecated
	 * @param String $currency The currency to filter.
	 * @return boolean True if setting the currency works, false if not.
	 */
	public function setCurrency($currency) {
		return false;
	}

	/**
	 * This function gets the currency.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Deprecate because product filter now use shop configured Currency.
	 * @api
	 * @deprecated
	 * @return String The currency of this product filter.
	 */
	public function getCurrency() {
		return Currencies::getCurrency();
	}

	/**
	 * This function sets the page to show.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @param int $page The page number to filter.
	 * @return boolean True if setting the page works, false if not.
	 */
	public function setPage($page) {
		if (!InputValidator::isRangedInt($page, 1)) {
			return false;
		}
		$this->page = $page;
		return true;
	}

	/**
	 * This function gets the page.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return int The page number of this product filter.
	 */
	public function getPage() {
		return $this->page;
	}

	/**
	 * This function sets the results per page to show.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @param int $resultsPerPage The results per page to filter.
	 * @return boolean True if setting the results per page works, false if not.
	 */
	public function setResultsPerPage($resultsPerPage) {
		if (!InputValidator::isRangedInt($resultsPerPage, null, 100)) {
			return false;
		}
		$this->resultsPerPage = $resultsPerPage;
		return true;
	}

	/**
	 * This function gets the results per page.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return int The results per page number of this product filter.
	 */
	public function getResultsPerPage() {
		return $this->resultsPerPage;
	}

	/**
	 * This function sets the direction to show.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @param String $direction The direction to filter.
	 * @return boolean True if setting the direction works, false if not.
	 */
	public function setDirection($direction) {
		if (!InputValidator::isProductDirection($direction)) {
			return false;
		}
		$this->direction = $direction;
		return true;
	}

	/**
	 * This function gets the direction.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return String The direction of this product filter.
	 */
	public function getDirection() {
		return $this->direction;
	}

	/**
	 * This function sets the order parameter to show.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @param String $sort The sort parameter to filter.
	 * @return boolean True if setting the sort parameter works, false if not.
	 */
	public function setSort($sort) {
		if (!InputValidator::isProductSort($sort)) {
			return false;
		}
		$this->sort = $sort;
		return true;
	}

	/**
	 * This function gets the sort.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return String The sort of this product filter.
	 */
	public function getSort() {
		return $this->sort;
	}

	/**
	 * This function sets the query search string to show.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @param String $q The query search string to filter.
	 * @return boolean True if setting the query search string works, false if not.
	 */
	public function setQ($q) {
		if (InputValidator::isEmpty($q)) {
			return false;
		}
		$this->q = $q;
		return true;
	}

	/**
	 * This function gets the query search string.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return String The query search string of this product filter.
	 */
	public function getQ() {
		return $this->q;
	}

	/**
	 * This function sets the category ID to show.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @param String $categoryID	The category ID to filter.
	 * @return boolean True if setting the category ID string works, false if not.
	 */
	public function setCategoryID($categoryID) {
		if (InputValidator::isEmpty($categoryID)) {
			return false;
		}
		$this->categoryID = $categoryID;
		return true;
	}

	/**
	 * This function gets the category ID string.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @return String The category ID string of this product filter.
	 */
	public function getCategoryID() {
		return $this->categoryID;
	}

	/**
	 * This function add a product ID from filter.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @param String $productID The product ID to filter.
	 * @return boolean True if setting the product ID string works, false if not.
	 */
	public function setID($productID) {
		if (InputValidator::isEmpty($productID)
			|| count($this->IDs) > 12
			|| in_array($productID, $this->IDs)) {
			return false;
		}
		array_push($this->IDs, $productID);
		return true;
	}

	/**
	 * This function delete a product ID from filter.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @param String $productID	The product ID to unset from filter.
	 * @return boolean True if unsetting the product ID string works, false if not.
	 */
	public function unsetID($productID) {
		if (InputValidator::isEmpty($productID)
			|| !in_array($productID, $this->IDs)) {
			return false;
		}
		unset($this->IDs[array_search($productID, $this->IDs)]);
		return true;
	}

	/**
	 * This function reset all product IDs from filter.
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 */
	public function resetIDs() {
		$this->IDs = array();
	}

	/**
	 * This function reset all product IDs from filter.
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a default Locale and Currency.
	 * @api
	 */
	public function resetFilter() {

		$this->page = 1;
		$this->resultsPerPage = 10;
		$this->direction = null;
		$this->sort = "name";
		$this->q = null;
		$this->categoryID = null;
		$this->IDs = array();
	}

	/**
	 * This function returns the products by using the product filter.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a default Locale.
	 * @api
	 * @return Products[] Returns an array of products.
	 */
	public function getProducts() {

		$parameter = $this->getParameter();

		// if request method is blocked
		if (!RESTClient::setRequestMethod(HTTPRequestMethod::GET)) {
			return;
		}

		$content = RESTClient::send(self::RESTPATH . "?" . $parameter);

		// if respond is empty
		if (InputValidator::isEmpty($content)) {
			return;
		}

		// if there is no results, page AND resultsPerPage element
		if (InputValidator::isEmptyArrayKey($content, "results") ||
			InputValidator::isEmptyArrayKey($content, "page") ||
			InputValidator::isEmptyArrayKey($content, "resultsPerPage")) {
		    Logger::error("Respond for " . self::RESTPATH . " can not be interpreted.");
			return;
		}

		$products = array();
		// is there any product found: load the products.
	 	if (!InputValidator::isEmptyArrayKey($content, "items") && (sizeof($content['items']) != 0)) {

			foreach ($content['items'] as $item) {

				$product = new Product($item);
				array_push($products, $product);
			}
	 	}

		return $products;
	}

	/**
	 * This function returns the parameter as string.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use a default Locale and Currency.
	 * @api
	 * @return String The parameter build with this product filter.
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