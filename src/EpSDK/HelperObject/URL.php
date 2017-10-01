<?php
declare(strict_types=1);
namespace EpSDK\HelperObject;

/**
 * This is the URL class which is used for URLs.
 *
 * @package EpSDK\HelperObject
 * @author  David Pauli <contact@david-pauli.de>
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 * @since   0.1.3
 */
class URL
{
    /** @var string This is the path to the URL. */
    private $URL;

    /**
     * To create a new URL object use this constructor with the original URL.
     *
     * @param   string  $url    The path of the URL.
     * @since   0.1.3
     */
    public function __construct(string $url = null)
    {
        $url = $url ?? '';
        $this->URL = $url;
    }

    /**
     * Prints the URL object as a string.
     *
     * @return  string  The URL as a string.
     * @since   0.1.3
     */
    public function __toString()
    {
        return $this->URL;
    }
}
