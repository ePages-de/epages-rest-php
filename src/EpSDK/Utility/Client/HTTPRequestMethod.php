<?php
declare(strict_types=1);
namespace EpSDK\Utility\Client;

/**
 * The HTTP Request 'enum'.
 *
 * This are the possible HTTP Request Methods.
 *
 * @package EpSDKClient
 * @author  David Pauli <contact@dbawdy.de>
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 * @since   0.0.1
 */
abstract class HTTPRequestMethod
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    const PATCH = 'PATCH';
}
