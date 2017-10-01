<?php
declare(strict_types=1);
namespace EpSDK\ShopObject;

use EpSDK\Constants;
use EpSDK\HelperObject\HTML;
use EpSDK\HelperObject\Image;
use EpSDK\HelperObject\Price\PriceInfo;
use EpSDK\HelperObject\URL;
use EpSDK\HelperObject\Weight;
use EpSDK\Utility\Client\HTTPRequestMethod;
use EpSDK\Utility\Logger\Logger;

/**
 * This is the epages product object.
 *
 * @package EpSDK\ShopObject
 * @author  David Pauli <contact@david-pauli.de>
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 * @since   0.0.0
 * @method  Product setMinStocklevel(int $minstocklevel)
 * @method  Product setName(string $name)
 * @method  Product setProductNumber(string $productNumber)
 * @method  string getName()
 */
class Product extends AbstractShopObject
{
    /** @var string */
    protected $idAttributeName = 'productId';

    /** @var string */
    protected $productId;

    /** @var array */
    protected $queryParams = [
        Constants::OBJECT_PARAMETER_LOCALIZATION    =>  null,
        Constants::OBJECT_PARAMETER_CURERNCY        =>  null
    ];

    /** @var array Allowed request methods of this object. */
    protected $requestMethods = [
        HTTPRequestMethod::POST,
        HTTPRequestMethod::GET,
        HTTPRequestMethod::PATCH,
        HTTPRequestMethod::DELETE
    ];

    /** @var string */
    protected $name;

    /** @var bool */
    protected $visible;

    /** @var string */
    protected $productVariationType;

    /** @var string */
    protected $manufacturerProductNumber;

    protected $productLength;

    protected $productWidth;

    protected $productHeight;

    protected $productVariationSelection;

    /** @var HTML */
    protected $shortDescription;

    /** @var string */
    protected $deliveryPeriod;

    /** @var HTML */
    protected $description;

    /** @var string */
    protected $productImage;

    /** @var Image[] */
    protected $images;

    /** @var PriceInfo */
    protected $priceInfo;

    /** @var bool */
    protected $forSale;

    /** @var bool */
    protected $specialOffer;

    /** @var Weight */
    protected $deliveryWeight;

    protected $shippingMethodsRestrictedTo;

    /** @var HTML */
    protected $availabilityText;

    /** @var string */
    protected $availability;

    protected $energyLabelsString;

    protected $energyLabelSourceFile;

    protected $productDataSheet;

    /** @var URL */
    protected $sfUrl;

    /** @var string */
    protected $productNumber;

    /** @var string */
    protected $manufacturer;

    protected $upc;

    protected $ean;

    /** @var string */
    protected $essentialFeatures;

    /** @var string[] */
    protected $searchKeywords;

    /** @var int */
    protected $stocklevel;

    /** @var int */
    protected $minStocklevel;

    /**
     * This is the global save method to store the object via connector.
     *
     * @param   bool    $completeOverride   True, if the complete object should be overridden, false if only changed parts will be overridden.
     * @return  bool    True, if save works, false if not.
     * @since   0.4.0
     */
    public function save(bool $completeOverride = false): bool
    {
        if ($this->isNew && empty($this->productNumber)) {
            Logger::notify('Cannot save new Product, productNumber is required.');
            return false;
        }
        return parent::save($completeOverride);
    }
}
