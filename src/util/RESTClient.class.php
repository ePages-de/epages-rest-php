<?php
/**
 * This file represents the REST client class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @since 0.0.1 Add HTTPRequestMethod enum.
 * @since 0.2.0 Use Guzzle REST client.
 */
namespace ep6;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
/**
 * This is the pure REST client. It is used in a static way.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @package ep6
 * @since 0.0.0
 * @since 0.0.3 Use HTTPRequestMethod enum.
 * @since 0.1.0 Add a recommended wait time for the next request.
 * @since 0.1.1 Now the object is printable via echo.
 * @since 0.1.1 Add function to change the wait time for next REST request.
 * @since 0.1.1 Set $HOST and $SHOP to default value null.
 * @since 0.1.2 Add error reporting.
 * @since 0.2.0 Add Guzzle REST client.
 * @subpackage Util
 */
class RESTClient {

	use ErrorReporting;

	/** @var String The path to the REST ressource in the shop. */
	const PATHTOREST = "rs/shops";

	/** @var int The time in ms the shop object should wait until the next request. */
	public static $NEXT_RESPONSE_WAIT_TIME = 600;

	/** @var String|null The authentification token (access token). */
	private static $AUTHTOKEN = null;

	/** @var GuzzleHttp\Cient|null The Guzzle REST client. */
	private static $CLIENT;

	/** @var HTTPRequestMethod The request method of the REST call. */
	private static $HTTP_REQUEST_METHOD = HTTPRequestMethod::GET;
	
	/** @var array The options for the Guzzle REST client. */
	private static $REQUEST_OPTIONS = array(
			"allow_redirects" => array(
				"strict" => true
				),
			"header" => array(
				"Accept" => "application/vnd.epages.v1+json",
				"Content-Type" => "application/json"
				)
			);

	/**
	 * The constructor for the main class.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String $host The epages host to connect.
	 * @param String $shop The refered ePages shop.
	 * @param String $authToken The authentificaton token to connect via REST.
	 * @param boolean $isssl True, if you use SSL, false if not. Default value is true.
	 * @since 0.0.0
	 * @since 0.0.1 Use disconnect function on wrong parameters.
	 * @since 0.1.2 Throw warning with wrong parameters.
	 * @since 0.1.2 Add error reporting.
	 * @since 0.2.0 Init Guzzle REST client.
	 */
	public static function connect($host, $shop, $authToken = null, $isssl = true) {

		self::errorReset();

		// check parameters
		if (!InputValidator::isHost($host) ||
			!InputValidator::isShop($shop)) {

			Logger::warning("ep6\RESTClient\nHost (" . $host . ") or Shop (" . $shop . ") are not valid.");
			self::disconnect();
			$error = !InputValidator::isHost($host) ? "RESTC-1" : "RESTC-2";
			self::errorSet($error);
			return false;
		}

		$protocol = $isssl ? "https" : "http";		
		$uri = $protocol . "://" . $host . "/" . self::PATHTOREST . "/" . $shop . "/";
		
		self::$AUTHTOKEN = $authToken;
		self::$CLIENT = new \GuzzleHttp\Client(['base_uri' => $uri]);

		return true;
	}

	/**
	 * Disconnects and deletes all configuration data.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.2 Add error reporting.
	 * @since 0.2.0 Add Guzzle REST client.
	 */
	public static function disconnect() {

		self::errorReset();
		self::$AUTHTOKEN = null;
		self::$CLIENT = null;

		return true;
	}

	/**
	 * This send function sends a special command to the REST server with additional parameter.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String command The path which is requested in the REST client.
	 * @param String[] postfields Add specific parameters to the REST server.
	 * @return mixed[] The returned elements as array.
	 * @since 0.0.0
	 * @since 0.0.1 Use HTTPRequestMethod enum.
	 * @since 0.1.0 Allow empty message body if the status code is 204.
	 * @since 0.1.2 Restructure the logging message and fix the PATCH call.
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Remove isRESTCommand function.
	 * @since 0.2.0 Add Guzzle REST client.
	 */
	public static function send($command = "", $postfields = array()) {

		self::errorReset();

		if (!InputValidator::isArray($postfields)) {

			Logger::warning("ep6\RESTClient\nCommand (" . $command . ") or postfields (" . $postfields . ") are not valid.");
			self::errorSet("RESTC-5");
			return null;
		}

		if (is_null(self::$CLIENT)) {

			Logger::warning("ep6\RESTClient\nClient is not connected.");
			self::errorSet("RESTC-6");
			return null;
		}

		// add authentification if there is a token
		if (InputValidator::isAuthToken(self::$AUTHTOKEN)) {

			self::$REQUEST_OPTIONS["header"]["Authorization"] = "Bearer " . self::$AUTHTOKEN;
		}
	
		// add user agent header
		self::$REQUEST_OPTIONS["header"]["User-Agent"] = "epages-rest-php";

		// add body if there is data
		if (!InputValidator::isEmptyArray($postfields)) {

			self::$REQUEST_OPTIONS["json"] = $postfields;
		}
		
		try {

			$response = self::$CLIENT->request(self::$HTTP_REQUEST_METHOD, $command, self::$REQUEST_OPTIONS);

			Logger::notify("ep6\RESTClient:\n" . Psr7\str($response));
		}
		catch (RequestException $e) {
		
		 	$information = $e->hasResponse() ? ": " . Psr7\str($e->getResponse()) : ".";
		 
			Logger::error("ep6\RESTClient\nREST request can't send because of transfering problems." . $information);
			self::errorSet("RESTC-11");
			return null;
		}
		catch (ConnectException $e) {

		 	$information = $e->hasResponse() ? ": " . Psr7\str($e->getResponse()) : ".";
		 
			Logger::error("ep6\RESTClient\nREST request can't send because of networking error." . $information);
			self::errorSet("RESTC-12");
			return null;
		}
		catch (ClientException $e) {

			Logger::error("ep6\RESTClient\nGet wrong response: " . Psr7\str($e->getResponse()));
			self::errorSet("RESTC-8");
			return null;
		}
		catch (ServerException $e) {

			Logger::error("ep6\RESTClient\nGet wrong response: " . Psr7\str($e->getResponse()));
			self::errorSet("RESTC-8");
			return null;
		}
		catch (TooManyRedirectsException $e) {

			Logger::error("ep6\RESTClient\nThere were too many redirects via REST: " . Psr7\str($e->getResponse()));
			self::errorSet("RESTC-13");
			return null;
		}

		return JSONHandler::parseJSON($response->getBody());
	}

	/**
	 * This send function sends a special command to the REST server.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String command The path which is requested in the REST client.
	 * @param String locale The localization to get.
	 * @param mixed[] postfields Add specific parameters to the REST server.
	 * @return String The returned JSON object or null if something goes wrong.
	 * @since 0.0.0
	 * @since 0.1.2 Throw warning with wrong parameters.
	 * @since 0.1.2 Add error reporting.
	 */
	public static function sendWithLocalization($command, $locale, $postfields = array()) {

		self::errorReset();

		// check parameters
		if (!InputValidator::isLocale($locale)) {

			Logger::warning("ep6\RESTClient\nLocale (" . $locale . ") is not valid.");
			self::errorSet("RESTC-3");
			return null;
		}

		return self::send($command . "?locale=" . $locale, $postfields);
	}

	/**
	 * Sets another request method.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param HTTPRequestMethod method The request method the REST client should use.
	 * @return boolean True, if it works, false if not.
	 * @since 0.0.0
	 * @since 0.1.0 Use HTTPRequestMethod enum.
	 * @since 0.1.2 Throw warning with wrong parameters.
	 * @since 0.1.2 Add error reporting.
	 */
	public static function setRequestMethod($method) {

		self::errorReset();

		if (!InputValidator::isRequestMethod($method)) {

			Logger::warning("ep6\RESTClient\nRequest method (" . $method . ") is not valid.");
			self::errorSet("RESTC-9");
			return false;
		}

		self::$HTTP_REQUEST_METHOD = $method;
		return true;
	}

	/**
	 * Change the time to wait with the next request.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param int time The time in ms every reload needs to wait until get new information.
	 * @return boolean True if the change works, false if not.
	 * @since 0.1.1
	 * @since 0.1.2 Throw warning with wrong parameters.
	 * @since 0.1.2 Add error reporting.
	 */
	public static function setRequestWaitTime($time) {

		self::errorReset();

		if (!InputValidator::isRangedInt($time, 0)) {

			Logger::warning("ep6\RESTClient\nRequest time (" . $time . ") is not valid.");
			self::errorSet("RESTC-10");
			return false;
		}

		self::$NEXT_RESPONSE_WAIT_TIME = $time;

		return true;
	}
}
?>