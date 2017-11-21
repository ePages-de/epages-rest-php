<?php
declare(strict_types=1);

namespace EpSDK\Connector;

use EpSDK\HelperObject\HTML;
use EpSDK\HelperObject\Image;
use EpSDK\HelperObject\Price\PriceInfo;
use EpSDK\HelperObject\URL;
use EpSDK\HelperObject\Weight;
use EpSDK\ShopObject\AbstractShopObject;
use EpSDK\ShopObject\Product;

/**
 * Class ProductConnector
 *
 * @package EpSDK\Connector
 * @author  David Pauli <contact@dbawdy.de>
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 * @since   0.4.0
 */
class ProductConnector extends AbstractConnector
{
    /** @var array */
    protected $arrayAttributeTypes = [
        'images' => Image::class
    ];

    /** @var array All attributes and their types. */
    protected $attributeTypes = [
        'shortDescription' => HTML::class,
        'description'      => HTML::class,
        'priceInfo'        => PriceInfo::class,
        'deliveryWeight'   => Weight::class,
        'availabilityText' => HTML::class,
        'sfUrl'            => URL::class
    ];

    /** @var string */
    protected $pathToObject = 'products';

    /** @var string */
    protected $shopObject = Product::class;

    /**
     * Returns an object as array to create.
     *
     * @param   AbstractShopObject|Product  $shopObject
     * @return  array
     */
    protected function asCreateArray(AbstractShopObject $shopObject): array
    {
        return [
            'productNumber'     =>  $shopObject->getProductNumber(),
            'name'              =>  $shopObject->getName(),
            'shortDescription'  =>  $shopObject->getShortDescription(),
            'description'       =>  $shopObject->getdescription(),
            'manufacturer'      =>  $shopObject->getManufacturer(),
            'price'             =>  $shopObject->getPriceInfo()
                ? $shopObject->getPriceInfo()->getPrice()->getAmount()
                : 0.0,
            'essentialFeatures' =>  $shopObject->getEssentialFeatures(),
            'upc'               =>  $shopObject->getUpc(),
            'ean'               =>  $shopObject->getEan(),
            'deliveryPeriod'    =>  $shopObject->getDeliveryPeriod(),
            'searchKeywords'    =>  $shopObject->getSearchKeywords(),
            'visible'           =>  $shopObject->getVisible(),
            'taxClassId'        =>  $shopObject->getPriceInfo()
                ? $shopObject->getPriceInfo()->getTaxClass()->getId()
                : '',
            'stocklevel'        =>  $shopObject->getStocklevel(),
            'depositPrice'      =>  $shopObject->getPriceInfo()
                ? $shopObject->getPriceInfo()->getDepositPrice()->getAmount()
                : 0.0,
            'manufacturerPrice' =>  $shopObject->getPriceInfo()
                ? $shopObject->getPriceInfo()->getManufacturerPrice()->getAmount()
                : 0.0
        ];
    }
}
