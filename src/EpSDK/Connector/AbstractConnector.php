<?php
declare(strict_types=1);
namespace EpSDK\Connector;

use EpSDK\Constants;
use EpSDK\ShopObject\AbstractShopObject;
use EpSDK\Utility\Client\RESTClient;
use EpSDK\Utility\Logger\Logger;
use Exception;

/**
 * The abstract connector to use the REST client handle objects.
 *
 * @package EpSDK\Connector
 * @author  David Pauli <contact@dbawdy.de>
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 * @since   0.4.0
 */
class AbstractConnector
{
    /** @var array All attributes which will returns special type in array. */
    protected $arrayAttributeTypes = [];

    /** @var array All attributes and their types. */
    protected $attributeTypes = [];

    /** @var string REST path to object. */
    protected $pathToObject = '';

    /** @var string */
    protected $shopObject;

    /**
     * Change some attributes of the object.
     *
     * @param   AbstractShopObject  $shopObject
     * @param   array               $attributesToChange
     * @return  bool
     * @since   0.4.0
     */
    public function changeAttributes(AbstractShopObject $shopObject, array $attributesToChange): bool
    {
        $payload = [];
        foreach ($attributesToChange as $attribute => $operation) {
            $payload[] = [
                'op'    =>  $operation,
                'path'  =>  '/' . $attribute,
                'value' =>  $shopObject->{$attribute}
            ];
        }

        // first create the object with minimum needed attributes
        try {
            $arrayObject = RESTClient::patch($this->pathToObject . '/' . $shopObject->getId(), $payload);
        } catch (Exception $exception) {
            Logger::error('Client problem with patching object: ' . $exception->getMessage());
            return false;
        }

        return $this->setArrayToObject($shopObject, $arrayObject);
    }

    /**
     * Override the complete object.
     *
     * @param   AbstractShopObject  $shopObject
     * @return  bool
     * @since   0.4.0
     */
    public function changeObject(AbstractShopObject $shopObject): bool
    {
        return true;
    }

    /**
     * Deletes a specific object.
     *
     * @param   AbstractShopObject  $shopObject
     * @return  bool
     * @since   0.4.0
     */
    public function deleteObject(AbstractShopObject $shopObject): bool
    {
        // first create the object with minimum needed attributes
        try {
            RESTClient::delete($this->pathToObject . '/' . $shopObject->getId());
        } catch (Exception $exception) {
            Logger::error('Client problem with deleting object: ' . $exception->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Sends a new created object to server.
     *
     * @param   AbstractShopObject  $shopObject
     * @param   array               $parameters
     * @return  bool
     * @since   0.4.0
     */
    public function create(AbstractShopObject $shopObject, array $parameters): bool
    {
        $queryParameter = $this->mergeParameters($parameters);

        // first create the object with minimum needed attributes
        try {
            $arrayObject = RESTClient::post($this->pathToObject . $queryParameter, $this->asCreateArray($shopObject));
        } catch (Exception $exception) {
            Logger::error('Client problem with creating object: ' . $exception->getMessage());
            return false;
        }
        if (false === $this->setCreateArrayToObject($shopObject, $arrayObject)) {
            Logger::warning('Problem with mapping data from freshly created object.');
            return false;
        }

        $shopObject->{'id'} = $arrayObject['id'];

        $attributesToOverride = [];
        foreach ($arrayObject as $attribute => $value) {
            $attributesToOverride[] = $attribute;
        }

        // then set other attributes
        return $this->changeAttributes($shopObject, $attributesToOverride);
    }

    /**
     * This function updates a complete object with refreshing it full via REST.
     *
     * @param   AbstractShopObject  $shopObject
     * @param   array               $parameters
     * @return  bool
     * @since   0.4.0
     */
    public function refreshAttributes(AbstractShopObject $shopObject, array $parameters): bool
    {
        $queryParameter = $this->mergeParameters($parameters);

        try {
            $arrayObject = RESTClient::get($this->pathToObject . '/' . $shopObject->getId() . $queryParameter);
        } catch (Exception $exception) {
            Logger::error('Client problem with refreshing attribute: ' . $exception->getMessage());
            return false;
        }
        return $this->setArrayToObject($shopObject, $arrayObject);
    }

    /**
     * This function gets an array of ShopObjects.
     *
     * @param   array   $parameters
     * @param   int     $maximumNumberOfElements
     * @return  array
     * @since   0.4.0
     */
    public function retrieveObjects(array $parameters, int $maximumNumberOfElements = null): array
    {
        $collectedObjects = [];

        $items = [];
        $retry = true;
        do {
            $queryParameter = $this->mergeParameters($parameters);
            try {
                $arrayResponse = RESTClient::get($this->pathToObject . $queryParameter);
            } catch (Exception $exception) {
                Logger::error('Client problem with retrieving array of ShopObjects: ' . $exception->getMessage());
                return $collectedObjects;
            }
            $items = \array_merge($items, $arrayResponse['items']);

            // Number of elements does not matter
            if (null === $maximumNumberOfElements) {
                $retry = false;
            } else {
                // if user wants all elements
                $maximumNumberOfElements = $maximumNumberOfElements === Constants::ALL_ELEMENTS
                    ? $arrayResponse['results']
                    : $maximumNumberOfElements;

                // if more elements are requested than found:
                $maximumNumberOfElements = $maximumNumberOfElements > $arrayResponse['results']
                    ? $arrayResponse['results']
                    : $maximumNumberOfElements;

                Logger::force($maximumNumberOfElements);

                $numberOfItems = \count($items);
                if ($numberOfItems === $maximumNumberOfElements) {
                    $retry = false;
                } elseif($numberOfItems > $maximumNumberOfElements) {
                    $items = \array_slice($items, $maximumNumberOfElements);
                    $retry = false;
                } else {
                    $parameters['page'] = $arrayResponse['page'] + 1;
                }
            }
        } while ($retry);

        foreach ($items as $item) {
            $newObject = new $this->shopObject();
            $this->setArrayToObject($newObject, $item);
            $collectedObjects[] = $newObject;
        }
        return $collectedObjects;
    }

    /**
     * Maps an array of objects.
     *
     * @param   array   $inputArray
     * @param   string  $object
     * @return  array
     * @since   0.4.0
     */
    protected function mapObjectsArray(array $inputArray, string $object): array
    {
        $objectsArray = [];
        foreach ($inputArray as $input) {
            $objectsArray[] = new $object($input);
        }
        return $objectsArray;
    }

    /**
     * Merge an array with parameters to a query string.
     *
     * @param   array   $parameters
     * @return  string
     */
    private function mergeParameters(array $parameters): string
    {
        $queryParameter = '';
        foreach ($parameters as $parameter => $value) {
            $queryParameter .= empty($queryParameter) ? '?' : '&';
            $queryParameter .= $parameter . '=' . $value;
        }
        return $queryParameter;
    }

    /**
     * Sets attributes to object.
     *
     * @param   AbstractShopObject  $shopObject
     * @param   array               $arrayObject
     * @return  bool
     */
    private function setCreateArrayToObject(AbstractShopObject $shopObject, array $arrayObject): bool
    {
        foreach ($this->asCreateArray($shopObject) as $attribute) {
            if (isset($this->arrayAttributeTypes[$attribute])) {
                $shopObject->{$attribute} = $this->mapObjectsArray(
                    $arrayObject[$attribute],
                    $this->arrayAttributeTypes[$attribute]
                );
            } elseif (isset($this->attributeTypes[$attribute])) {
                $shopObject->{$attribute} = new $this->attributeTypes[$attribute]($arrayObject[$attribute]);
            }
        }
        return true;
    }

    /**
     * Sets attributes to object.
     *
     * @param   AbstractShopObject  $shopObject
     * @param   array               $arrayObject
     * @return  bool
     */
    private function setArrayToObject(AbstractShopObject $shopObject, array $arrayObject): bool
    {
        foreach ($arrayObject as $attribute => $value) {
            if (isset($this->arrayAttributeTypes[$attribute])) {
                $value = $this->mapObjectsArray($value, $this->arrayAttributeTypes[$attribute]);
            } elseif (isset($this->attributeTypes[$attribute])) {
                $value = new $this->attributeTypes[$attribute]($value);
            }
            $shopObject->{$attribute} = $value;
        }
        return true;
    }

    /**
     * Returns an object as array to create.
     *
     * @param   AbstractShopObject  $shopObject
     * @return  array
     */
    protected function asCreateArray(AbstractShopObject $shopObject): array
    {
        return [];
    }
}
