<?php
declare(strict_types=1);
namespace EpSDK\Utility\Client;

use EpSDK\Configuration\Configuration;
use EpSDK\Constants;
use EpSDK\Exception\ConfigurationIncompleteException;
use EpSDK\Exception\ConfigurationNotFoundException;
use EpSDK\Exception\TooManyRequestsException;
use EpSDK\Exception\WrongHTTPResponseException;
use EpSDK\Utility\Logger\Logger;

/**
 * This is the pure REST Client.
 *
 * It is used in a static way, so it is complete stateless.
 *
 * @package EpSDK\Utility\Client
 * @author  David Pauli <contact@dbawdy.de>
 * @license MIT License https://github.com/ePages-de/epages-rest-php/blob/master/LICENSE
 * @link    https://github.com/ePages-de/epages-rest-php
 * @since   0.0.0
 */
class RESTClient
{
    /**
     * Gets a single resource.
     *
     * @param   string  $pathToResource
     * @param   array   $allowedStatusCodes
     * @return  array
     * @throws  WrongHTTPResponseException
     * @throws  ConfigurationIncompleteException
     * @throws  ConfigurationNotFoundException
     * @throws  TooManyRequestsException
     * @since   0.4.0
     */
    public static function get(string $pathToResource, array $allowedStatusCodes = [HTTPStatusCode::OK]): array
    {
        $configuration = Configuration::getConfiguration('Client');
        if (empty($configuration)) {
            throw new ConfigurationNotFoundException('Cannot find configuration for RESTClient.');
        }

        if (false === isset($configuration['host']) || false === isset($configuration['shop'])) {
            throw new ConfigurationIncompleteException('Configuration of RESTClient does not have host and shop.');
        }

        $protocol = isset($configuration['isSSL']) && $configuration['isSSL']
            ? 'https://'
            : 'http://';

        $pathToShop = '/' . Constants::CLIENT_PATH_TO_REST . '/' . $configuration['shop'] . '/';

        $extraHeaders = [];
        if (isset($configuration['token'])) {
            $extraHeaders['Authorization'] = 'Bearer ' . $configuration['token'];
        }
        if (isset($configuration['userAgent'])) {
            $extraHeaders['User-Agent'] = $configuration['userAgent'];
        }

        $useSSL = isset($configuration['isSSL']) && $configuration['isSSL'];

        return \json_decode(
            self::send(
                HTTPRequestMethod::GET,
                $protocol . $configuration['host'] . $pathToShop . $pathToResource,
                $allowedStatusCodes,
                $extraHeaders,
                [],
                $useSSL
            ),
            true
        );
    }

    /**
     * Gets a single resource.
     *
     * @param   string  $pathToResource
     * @param   array   $allowedStatusCodes
     * @return  bool
     * @throws  WrongHTTPResponseException
     * @throws  ConfigurationIncompleteException
     * @throws  ConfigurationNotFoundException
     * @throws  TooManyRequestsException
     * @since   0.4.0
     */
    public static function delete(
        string $pathToResource,
        array $allowedStatusCodes = [HTTPStatusCode::NO_CONTENT]
    ): bool {
        $configuration = Configuration::getConfiguration('Client');
        if (empty($configuration)) {
            throw new ConfigurationNotFoundException('Cannot find configuration for RESTClient.');
        }

        if (false === isset($configuration['host']) || false === isset($configuration['shop'])) {
            throw new ConfigurationIncompleteException('Configuration of RESTClient does not have host and shop.');
        }

        $protocol = isset($configuration['isSSL']) && $configuration['isSSL']
            ? 'https://'
            : 'http://';

        $pathToShop = '/' . Constants::CLIENT_PATH_TO_REST . '/' . $configuration['shop'] . '/';

        $extraHeaders = [];
        if (isset($configuration['token'])) {
            $extraHeaders['Authorization'] = 'Bearer ' . $configuration['token'];
        }
        if (isset($configuration['userAgent'])) {
            $extraHeaders['User-Agent'] = $configuration['userAgent'];
        }

        $useSSL = isset($configuration['isSSL']) && $configuration['isSSL'];

        self::send(
            HTTPRequestMethod::DELETE,
            $protocol . $configuration['host'] . $pathToShop . $pathToResource,
            $allowedStatusCodes,
            $extraHeaders,
            [],
            $useSSL
        );
        return true;
    }

    /**
     * Post a single resource.
     *
     * @param   string  $pathToResource
     * @param   array   $object
     * @param   array   $allowedStatusCodes
     * @return  array
     * @throws  WrongHTTPResponseException
     * @throws  ConfigurationIncompleteException
     * @throws  ConfigurationNotFoundException
     * @throws  TooManyRequestsException
     * @since   0.4.0
     */
    public static function patch(
        string $pathToResource,
        array $object,
        array $allowedStatusCodes = [HTTPStatusCode::OK]
    ): array {
        $configuration = Configuration::getConfiguration('Client');
        if (empty($configuration)) {
            throw new ConfigurationNotFoundException('Cannot find configuration for RESTClient.');
        }

        if (false === isset($configuration['host']) || false === isset($configuration['shop'])) {
            throw new ConfigurationIncompleteException('Configuration of RESTClient does not have host and shop.');
        }

        $protocol = isset($configuration['isSSL']) && $configuration['isSSL']
            ? 'https://'
            : 'http://';

        $pathToShop = '/' . Constants::CLIENT_PATH_TO_REST . '/' . $configuration['shop'] . '/';

        $extraHeaders = [];
        if (isset($configuration['token'])) {
            $extraHeaders['Authorization'] = 'Bearer ' . $configuration['token'];
        }
        if (isset($configuration['userAgent'])) {
            $extraHeaders['User-Agent'] = $configuration['userAgent'];
        }

        $useSSL = isset($configuration['isSSL']) && $configuration['isSSL'];

        return \json_decode(
            self::send(
                HTTPRequestMethod::PATCH,
                $protocol . $configuration['host'] . $pathToShop . $pathToResource,
                $allowedStatusCodes,
                $extraHeaders,
                $object,
                $useSSL
            ),
            true
        );
    }

    /**
     * Post a single resource.
     *
     * @param   string  $pathToResource
     * @param   array   $object
     * @param   array   $allowedStatusCodes
     * @return  array
     * @throws  WrongHTTPResponseException
     * @throws  ConfigurationIncompleteException
     * @throws  ConfigurationNotFoundException
     * @throws  TooManyRequestsException
     * @since   0.4.0
     */
    public static function post(
        string $pathToResource,
        array $object,
        array $allowedStatusCodes = [HTTPStatusCode::CREATED]
    ): array {
        $configuration = Configuration::getConfiguration('Client');
        if (empty($configuration)) {
            throw new ConfigurationNotFoundException('Cannot find configuration for RESTClient.');
        }

        if (false === isset($configuration['host']) || false === isset($configuration['shop'])) {
            throw new ConfigurationIncompleteException('Configuration of RESTClient does not have host and shop.');
        }

        $protocol = isset($configuration['isSSL']) && $configuration['isSSL']
            ? 'https://'
            : 'http://';

        $pathToShop = '/' . Constants::CLIENT_PATH_TO_REST . '/' . $configuration['shop'] . '/';

        $extraHeaders = [];
        if (isset($configuration['token'])) {
            $extraHeaders['Authorization'] = 'Bearer ' . $configuration['token'];
        }
        if (isset($configuration['userAgent'])) {
            $extraHeaders['User-Agent'] = $configuration['userAgent'];
        }

        $useSSL = isset($configuration['isSSL']) && $configuration['isSSL'];

        return \json_decode(
            self::send(
                HTTPRequestMethod::POST,
                $protocol . $configuration['host'] . $pathToShop . $pathToResource,
                $allowedStatusCodes,
                $extraHeaders,
                $object,
                $useSSL
            ),
            true
        );
    }

    /**
     * This send function sends a special command to the REST server with additional parameter.
     *
     * @param   string      $method
     * @param   string      $path
     * @param   array       $acceptedStatusCodes
     * @param   string[]    $additionalHeader
     * @param   string[]    $postParameter
     * @param   bool        $isSSL
     * @return  string
     * @throws  WrongHTTPResponseException
     * @throws  TooManyRequestsException
     * @since   0.0.0
     */
    private static function send(
        string $method,
        string $path,
        array $acceptedStatusCodes,
        array $additionalHeader = [],
        array $postParameter = [],
        bool $isSSL = true
    ): string {
        $headers = [
            'Accept: ' . Constants::CLIENT_HEADERS_ACCEPT,
            'Content-Type: ' . Constants::CLIENT_HEADERS_CONTENT_TYPE,
        ];

        foreach ($additionalHeader as $headerName => $value) {
            $headers[] = $headerName . ': ' . $value;
        }

        $curl = \curl_init($path);

        \curl_setopt($curl, \CURLOPT_BINARYTRANSFER, true);
        \curl_setopt($curl, \CURLOPT_CRLF, true);
        \curl_setopt($curl, \CURLOPT_FORBID_REUSE, true);
        \curl_setopt($curl, \CURLOPT_FRESH_CONNECT, true);
        \curl_setopt($curl, \CURLOPT_HEADER, true);
        \curl_setopt($curl, \CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($curl, \CURLINFO_HEADER_OUT, true);

        if ($isSSL) {
            \curl_setopt($curl, \CURLOPT_CERTINFO, true);
            \curl_setopt($curl, \CURLOPT_PROTOCOLS, \CURLPROTO_HTTPS);
            \curl_setopt($curl, \CURLOPT_REDIR_PROTOCOLS, \CURLPROTO_HTTPS);
        } else {
            \curl_setopt($curl, \CURLOPT_PROTOCOLS, \CURLPROTO_HTTP);
            \curl_setopt($curl, \CURLOPT_REDIR_PROTOCOLS, \CURLPROTO_HTTP);
        }

        switch ($method) {
            case HTTPRequestMethod::POST:
                \curl_setopt($curl, \CURLOPT_POST, true);
                $JSONPostField = \json_encode($postParameter);
                \curl_setopt($curl, \CURLOPT_POSTFIELDS, $JSONPostField);
                \curl_setopt($curl, \CURLOPT_POSTREDIR, 0);
                break;
            case HTTPRequestMethod::PUT:
                \curl_setopt($curl, \CURLOPT_PUT, true);
                $JSONPostField = \json_encode($postParameter);
                \curl_setopt($curl, \CURLOPT_POSTFIELDS, $JSONPostField);
                break;
            case HTTPRequestMethod::DELETE:
                $JSONPostField = \json_encode($postParameter);
                \curl_setopt($curl, \CURLOPT_POSTFIELDS, $JSONPostField);
                \curl_setopt($curl, \CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            case HTTPRequestMethod::PATCH:
                $JSONPostField = '[' . \json_encode($postParameter) . ']';
                \curl_setopt($curl, \CURLOPT_POSTFIELDS, $JSONPostField);
                \curl_setopt($curl, \CURLOPT_CUSTOMREQUEST, 'PATCH');
                break;
        }

        \curl_setopt($curl, \CURLOPT_HTTPHEADER, $headers);

        $response = \curl_exec($curl);
        $info = \curl_getinfo($curl);
        \curl_close($curl);

        // get body
        $body = \explode("\r\n\r\n", $response, 2)[1];
        $content = \trim($body);

        $logMessage = "Request:\n"
            . 'Parameters: ' . \http_build_query($postParameter) . "\n"
            . $info['request_header']
            . 'Response ' .  (int) $info['http_code'] . ":\n"
            . 'Size (Header/Request): ' . $info['header_size'] . '/' . $info['request_size'] . " Bytes\n"
            . 'Time (Total/NameLookup/Connect/PreTransfer/StartTransfer/Redirect): ' . $info['total_time']
            . ' / ' . $info['namelookup_time'] . ' / ' . $info['connect_time'] . ' / '
            . $info['pretransfer_time'] . ' / ' . $info['starttransfer_time'] . ' / ' . $info['redirect_time']
            . " seconds\n"
            . $response . "\n";
        Logger::notify("RESTClient:\n" . $logMessage);

        if (HTTPStatusCode::TOO_MANY_REQUEST === (int) $info['http_code']) {
            throw new TooManyRequestsException('You made to many requests.');
        }

        if (false === \in_array((int) $info['http_code'], $acceptedStatusCodes, true)) {
            throw new WrongHTTPResponseException('Response is not valid: ' . $response);
        }

        // parse header, response code and body
        return $content;
    }
}
