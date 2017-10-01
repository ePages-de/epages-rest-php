<?php
declare(strict_types=1);
namespace EpSDK\ShopObjectList;

use EpSDK\Configuration\Configuration;
use EpSDK\Connector\AbstractConnector;
use EpSDK\ShopObject\AbstractShopObject;
use EpSDK\Utility\Client\HTTPRequestMethod;
use EpSDK\Utility\Logger\Logger;

/**
 * This is the abstract shop object list.
 *
 * @package EpSDK\ShopObjectList
 * @author  David Pauli <contact@dbawdy.de>
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 * @since   0.4.0
 */
abstract class AbstractShopObjectList
{
    /** @var array */
    protected $queryParams = [];

    /** @var array Allowed request methods of this object. */
    protected $requestMethods = [];

    /** @var string */
    protected $shopObjectName;

    /** @var AbstractConnector The connector of the object, would be initialized via constructor automatically. */
    private $connector;

    /**
     * The constructor for most of the Shop Objects.
     *
     * Create a function called preConstruct() or postConstruct() to execute own code in the extended class.
     *
     * @param   array   $queryParameters
     * @since   0.4.0
     */
    public function __construct(array $queryParameters = null)
    {
        $queryParameters = $queryParameters ?? [];

        if (\method_exists($this, 'preConstruct')) {
            $this->preConstruct();
        }

        // Initialize the Connector.
        $this->connector = Configuration::getConfiguration('Connector')[$this->shopObjectName];

        foreach ($queryParameters as $parameter => $value) {
            $this->addQueryParameter($parameter, $value);
        }

        if (\method_exists($this, 'postConstruct')) {
            $this->postConstruct();
        }
    }

    /**
     * Add a specific query parameter.
     *
     * @param   string  $parameter
     * @param   mixed   $value
     * @return  self
     */
    public function addQueryParameter(string $parameter, $value): self
    {
        if (\array_key_exists($parameter, $this->queryParams)) {
            $this->queryParams[$parameter] = $value;
        }
        return $this;
    }

    /**
     * @param   int $maximumNumberOfElements
     * @return  AbstractShopObject[]
     */
    public function fetchObjects(int $maximumNumberOfElements = null): array
    {
        if (false === \in_array(HTTPRequestMethod::GET, $this->requestMethods, true)) {
            Logger::warning('Cannot retrieve ShopObjects of ' . \get_class($this));
            return [];
        }
        return $this->connector->retrieveObjects($this->calculateQueryParameter(), $maximumNumberOfElements);
    }

    /**
     * Calculates allowed query parameters with ignore null values.
     *
     * @return  array
     */
    private function calculateQueryParameter(): array
    {
        $queryParameters = [];
        foreach ($this->queryParams as $parameter => $value) {
            if (null !== $value) {
                $queryParameters[$parameter] = $value;
            }
        }
        return $queryParameters;
    }
}
