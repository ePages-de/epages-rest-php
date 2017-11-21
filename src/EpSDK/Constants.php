<?php
declare(strict_types=1);

namespace EpSDK;

/**
 * This class used in a static way.
 *
 * @package EpSDK
 * @author  David Pauli <contact@dbawdy.de>
 * @since   0.4.0
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 */
class Constants
{
    // REST CLIENT
    const CLIENT_HEADERS_ACCEPT             = 'application/vnd.epages.v1+json';
    const CLIENT_HEADERS_CONTENT_TYPE       = 'application/json';
    const CLIENT_HEADERS_CONTENT_TYPE_PATCH = 'application/json-patch+json';
    const CLIENT_PATH_TO_REST               = 'rs/shops';

    // SEARCHES
    const ALL_ELEMENTS = -1;

    // FILTER PARAMETER
    const FILTER_LOCALIZATION     = 'locale';
    const FILTER_CURRENCY         = 'currency';
    const FILTER_PAGE_NUMBER      = 'page';
    const FILTER_OBJECT_PER_PAGE  = 'resultsPerPage';
    const FILTER_SORT_DIRECTION   = 'direction';
    const FILTER_SORT_CRITERIA    = 'sort';
    const FILTER_SEARCH_FOR_WORD  = 'q';
    const FILTER_CATEGORY         = 'categoryId';
    const FILTER_ID               = 'id';
    const FILTER_SEARCH_INVISIBLE = 'includeInvisible';

    // QUERY PARAMETER
    const OBJECT_PARAMETER_LOCALIZATION = 'locale';
    const OBJECT_PARAMETER_CURRENCY     = 'currency';
}
