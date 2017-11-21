<?php
declare(strict_types=1);
namespace EpSDK\ShopObjectList;

use EpSDK\ShopObject\Product;
use EpSDK\Utility\Client\HTTPRequestMethod;

/**
 * This is the product list class.
 *
 * @package EpSDK\ShopObjectList
 * @author  David Pauli <contact@dbawdy.de>
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 * @since   0.4.0
 */
class ProductList extends AbstractShopObjectList
{
    /** @var array */
    protected $queryParams = [
        'locale'           => null,
        'currency'         => null,
        'page'             => 1,
        'resultsPerPage'   => 10,
        'direction'        => null,
        'sort'             => 'name',
        'q'                => null,
        'categoryId'       => null,
        'id'               => null,
        'includeInvisible' => null
    ];

    /** @var array Allowed request methods of this object. */
    protected $requestMethods = [HTTPRequestMethod::GET];

    /** @var string */
    protected $shopObjectName = Product::class;
}
