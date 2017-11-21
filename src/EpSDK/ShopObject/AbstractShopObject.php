<?php
declare(strict_types=1);

namespace EpSDK\ShopObject;

use EpSDK\Configuration\Configuration;
use EpSDK\Connector\AbstractConnector;
use EpSDK\Utility\Client\HTTPRequestMethod;
use EpSDK\Utility\Logger\Logger;

/**
 * This is the abstract shop object.
 *
 * @package EpSDK\ShopObject
 * @author  David Pauli <contact@dbawdy.de>
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 * @since   0.4.0
 */
abstract class AbstractShopObject
{
    /** @var string The Id of the shop object. */
    protected $id;

    /** @var string The property which is the ID in the object. */
    protected $idAttributeName = 'id';

    /** @var bool This boolean saves if the object is created new or not. */
    protected $isNew = true;

    /** @var array */
    protected $queryParams = [];

    /** @var array Allowed request methods of this object. */
    protected $requestMethods = [];

    /** @var array Space to save all attributes which are changed. */
    private $changedAttributes;

    /** @var AbstractConnector The connector of the object, would be initialized via constructor automatically. */
    private $connector;

    /** @var bool Saves if a object is deleted and cant be saved the normal way. */
    private $isActive = true;

    /**
     * The constructor for most of the Shop Objects.
     *
     * Create a function called preConstruct() or postConstruct() to execute own code in the extended class.
     *
     * @param   string  $id                 The ID to initialize. Leave it empty if you create a new object.
     * @param   array   $queryParameters
     * @since   0.4.0
     */
    public function __construct(string $id = null, array $queryParameters = null)
    {
        $queryParameters = $queryParameters ?? [];

        if (\method_exists($this, 'preConstruct')) {
            $this->preConstruct();
        }

        // Initialize the Connector
        if (empty(Configuration::get('Connector')[\get_class($this)])) {
            include __DIR__ .  '/loadConnectors.php';
        }
        $this->connector = Configuration::get('Connector')[\get_class($this)];

        foreach ($queryParameters as $parameter => $value) {
            $this->addQueryParameter($parameter, $value);
        }

        if ($id !== null) {
            $this->isNew = false;
            $this->id = $id;
            $this->retrieve();
        }

        if (\method_exists($this, 'postConstruct')) {
            $this->postConstruct();
        }
    }

    /**
     * Call a function.
     *
     * This function makes special cases on Setters.
     *
     * @param   string  $functionName
     * @param   array   $arguments
     * @return  mixed
     * @since   0.4.0
     */
    public function __call(string $functionName, array $arguments)
    {
        if (0 === \strpos($functionName, 'set')) {
            $property = \lcfirst(\substr($functionName, 3));
            // collect all changed attributes
            if (\is_string($property) && \property_exists($this, $property)) {
                if (empty($this->{$property})) {
                    $this->changedAttributes[$property] = 'add';
                } else {
                    $this->changedAttributes[$property] = 'replace';
                }
                $this->{$property} = $arguments[0];
                return $this;
            }
        } elseif (0 === \strpos($functionName, 'get')) {
            $property = \lcfirst(\substr($functionName, 3));
            // collect all changed attributes
            if (\is_string($property) && \property_exists($this, $property)) {
                return $this->{$property};
            }
        }
        return null;
    }

    /**
     * Sets an attribute.
     *
     * This function is called automatically if the object is created via REST client.
     *
     * @param   string  $name
     * @param   mixed   $value
     * @since   0.4.0
     */
    public function __set(string $name, $value)
    {
        $this->{$name} = $value;
        if ($name === $this->idAttributeName) {
            $this->id = $value;
        }
    }

    /**
     * Gets an attribute.
     *
     * This function is called automatically if the object is called via REST client.
     *
     * @param   string  $name
     * @return  mixed
     * @since   0.4.0
     */
    public function __get(string $name)
    {
        if (\property_exists($this, $name)) {
            return $this->{$name};
        }
        return null;
    }

    /**
     * Add a specific query parameter.
     *
     * @param   string  $parameter
     * @param   mixed   $value
     * @return  self
     */
    public function addObjectParameter(string $parameter, $value): self
    {
        if (\array_key_exists($parameter, $this->queryParams)) {
            $this->queryParams[$parameter] = $value;
        }
        return $this;
    }

    /**
     * This functions delete the object via connector.
     *
     * @return  bool    True, if saving works, false if not.
     * @since   0.4.0
     */
    public function delete(): bool
    {
        if (null === $this->id
            || false === \in_array(HTTPRequestMethod::DELETE, $this->requestMethods, true)
        ) {
            return false;
        }

        $this->connector->deleteObject($this);
        $this->isActive = false;
        return true;
    }

    /**
     * This is the Getter for the ID of the object.
     *
     * @return  string|null
     * @since   0.4.0
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retrieve object data again
     *
     * @return  bool    True, if saving works, false if not.
     * @since   0.4.0
     */
    public function retrieve(): bool
    {
        if (null === $this->id
            || false === $this->isActive
            || false === \in_array(HTTPRequestMethod::GET, $this->requestMethods, true)
        ) {
            return false;
        }

        return $this->connector->refreshAttributes($this, $this->calculateQueryParameter());
    }

    /**
     * This is the global save method to store the object via connector.
     *
     * @param   bool    $completeOverride   True, if the complete object should be overridden, false if only changed parts will be overridden.
     * @return  bool    True, if save works, false if not.
     * @since   0.4.0
     */
    public function save(bool $completeOverride = false): bool
    {
        if ((null === $this->id && false === $this->isNew)
            || false === $this->isActive
        ) {
            return false;
        }

        // Create new object
        if ($this->isNew) {
            if (false === \in_array(HTTPRequestMethod::POST, $this->requestMethods, true)) {
                Logger::warning('Cannot create new ShopObject of ' . \get_class($this));
                return false;
            }
            return $this->connector->create($this, $this->calculateQueryParameter());
        }

        // If try to PUT it
        if ($completeOverride) {
            if (false === \in_array(HTTPRequestMethod::PUT, $this->requestMethods, true)) {
                Logger::warning('Cannot complete update ShopObject of ' . \get_class($this));
                return false;
            }
            return $this->connector->changeObject($this);
        }

        // If try to PATCH it
        if (false === \in_array(HTTPRequestMethod::PATCH, $this->requestMethods, true)) {
            Logger::warning('Cannot partly update ShopObject of ' . \get_class($this));
            return false;
        }
        return $this->connector->changeAttributes($this, $this->changedAttributes);
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
