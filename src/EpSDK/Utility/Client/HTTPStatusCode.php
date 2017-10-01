<?php
declare(strict_types=1);
namespace EpSDK\Utility\Client;

/**
 * The HTTP Request 'enum'.
 *
 * This are the possible HTTP Status Codes.
 *
 * @package EpSDKClient
 * @author  David Pauli <contact@dbawdy.de>
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 * @since   0.3.0
 */
abstract class HTTPStatusCode
{
    const OK                =   200;
    const CREATED           =   201;
    const NO_CONTENT        =   204;
    const FOUND             =   302;
    const TOO_MANY_REQUEST  =   429;
}
