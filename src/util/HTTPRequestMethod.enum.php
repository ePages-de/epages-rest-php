<?php
namespace ep6;
/**
 * The HTTP request 'enum'.
 *
 * This are the possible HTTP request methods.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.1
 * @package ep6
 * @subpackage Util\RESTClient
 */
abstract class HTTPRequestMethod {
	/** @var String Use this for a GET request. **/
	const GET = "GET";
	/** @var String Use this for a POST request. **/
	const POST = "POST";
	/** @var String Use this for a PUT request. **/
	const PUT = "PUT";
	/** @var String Use this for a DELETE request. **/
	const DELETE = "DELETE";
	/** @var String Use this for a PATCH request. **/
	const PATCH = "PATCH";
}
?>