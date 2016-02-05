<?php
/**
 * This file represents the REST client class.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @since 0.0.1 Add HTTPRequestMethod enum.
 */
namespace ep6;
/**
 * This is the pure REST client. It is used in a static way.
 *
 * @author David Pauli <contact@david-pauli.de>
 * @since 0.0.0
 * @since 0.0.3 Use HTTPRequestMethod enum.
 * @since 0.1.0 Add a recommended wait time for the next request.
 * @since 0.1.1 Now the object is printable via echo.
 * @since 0.1.1 Add function to change the wait time for next REST request.
 * @since 0.1.1 Set $HOST and $SHOP to default value null.
 * @package ep6
 * @subpackage Util
 */
class RESTClient {

	/** @var String|null The ePages host to connect. */
	private static $HOST = null;

	/** @var String|null The refered ePages ahop. */
	private static $SHOP = null;

	/** @var String|null The authentification token (access token). */
	private static $AUTHTOKEN = null;

	/** @var boolean|null You use https or http? Default is true. */
	private static $ISSSL = true;

	/** @var boolean Boolean to log whether the client is connected or not. */
	private static $ISCONNECTED = false;

	/** @var HTTPRequestMethod The request method of the REST call. */
	private static $HTTP_REQUEST_METHOD = HTTPRequestMethod::GET;

	/** @var String The path to the REST ressource in the shop. */
	const PATHTOREST = "rs/shops";

	/** @var String The accepted value of the response. */
	const HTTP_ACCEPT = "application/vnd.epages.v1+json";

	/** @var String The content type of the request. */
	const HTTP_CONTENT_TYPE = "application/json";

	/** @var int The time in ms the shop object should wait until the next request. */
	public static $NEXT_RESPONSE_WAIT_TIME = 600;

	/**
	 * The constructor for the main class.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use disconnect function on wrong parameters.
	 * @api
	 * @param String $host The epages host to connect.
	 * @param String $shop The refered ePages shop.
	 * @param String $authToken The authentificaton token to connect via REST.
	 * @param boolean $isssl True, if you use SSL, false if not. Default value is true.
	 */
	public static function connect($host, $shop, $authToken, $isssl) {

		// check parameter
		if (!InputValidator::isHost($host) ||
			!InputValidator::isShop($shop)) {
			self::disconnect();
			return false;
		}

		self::$HOST = $host;
		self::$SHOP = $shop;
		self::$ISSSL = $isssl;
		self::$AUTHTOKEN = $authToken;
		self::$ISCONNECTED = true;

		return true;
	}

	/**
	 * This function prints the status of the REST client in a FORCE Logger message.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.1 Echo the object itself to see all values setted.
	 * @deprecated Echo the object itself to see all values setted.
	 */
	public static function printStatus() {

		Logger::force(self);
	}

	/**
	 * This send function sends a special command to the REST server.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 * @param String command The path which is requested in the REST client.
	 * @param String locale The localization to get.
	 * @param mixed[] postfields Add specific parameters to the REST server.
	 * @return String The returned JSON object or null if something goes wrong.
	 */
	public static function sendWithLocalization($command, $locale, $postfields = array()) {

		// cheeck parameters
		if (!InputValidator::isLocale($locale)) {
			return null;
		}
		return self::send($command . "?locale=" . $locale, $postfields);
	}

	/**
	 * This send function sends a special command to the REST server with additional parameter.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.0.1 Use HTTPRequestMethod enum.
	 * @since 0.1.0 Allow empty message body if the status code is 204.
	 * @api
	 * @param String command The path which is requested in the REST client.
	 * @param String[] postfields Add specific parameters to the REST server.
	 * @return mixed[] The returned elements as array.
	 */
	public static function send($command, $postfields = array()) {

		if (!InputValidator::isRESTCommand($command) ||
			!self::$ISCONNECTED ||
			!InputValidator::isArray($postfields)) {
			return null;
		}

		$protocol = self::$ISSSL ? "https" : "http";
		$url = $protocol . "://" . self::$HOST . "/" . self::PATHTOREST . "/" . self::$SHOP . "/" . $command;

		$headers = array(
			"Accept: " . self::HTTP_ACCEPT,
			"Content-Type: " . self::HTTP_CONTENT_TYPE);

		if (InputValidator::isAuthToken(self::$AUTHTOKEN)) {
			array_push($headers, "Authorization: Bearer " . self::$AUTHTOKEN);
		}

		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_FAILONERROR, 1);								// show full errors
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 0);							// connection can be opened
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 0);							// no new connection required
		curl_setopt($curl, CURLOPT_NOBODY, 0);									// show body
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);							// get response as string
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);							// no connection timeout
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS, 0);						// no connection timeout
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_NONE);		// cURL will choose the http version
		curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);			// understand ipv4 and ipv6

		if (self::$ISSSL) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);						// don't check the peer ssl cerrificate
			curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
			curl_setopt($curl, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTPS);
			curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_DEFAULT);	// default ssl version
		}
		else {
			curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTP);
			curl_setopt($curl, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTP);
		}

		switch (self::$HTTP_REQUEST_METHOD) {
			case HTTPRequestMethod::GET:
				curl_setopt($curl, CURLOPT_HTTPGET, 1);
				break;
			case HTTPRequestMethod::POST:
				$JSONpostfield = JSONHandler::createJSON($postfields);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTREDIR, 0);	// don't post on redirects
				curl_setopt($curl, CURLOPT_POSTFIELDS, $JSONpostfield);
				break;
			case HTTPRequestMethod::PUT:
				$JSONpostfield = JSONHandler::createJSON($postfields);
				array_push($headers, "Content-Length: " . strlen($JSONpostfield));
				curl_setopt($curl, CURLOPT_POSTFIELDS, $JSONpostfield);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
				break;
			case HTTPRequestMethod::DELETE:
				$JSONpostfield = JSONHandler::createJSON($postfields);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
				curl_setopt($curl, CURLOPT_POSTFIELDS, $JSONpostfield);
				break;
			case HTTPRequestMethod::PATCH:
				$JSONpostfield = JSONHandler::createJSON($postfields);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
				curl_setopt($curl, CURLOPT_POSTFIELDS, $JSONpostfield);
				break;
		}

		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		$response = curl_exec($curl);
		$info = curl_getinfo($curl);
		$error = curl_error($curl);
		curl_close($curl);

		$logMessage = self::$HTTP_REQUEST_METHOD . " " . $info["url"] . "<br/>"
					. "<strong>Response</strong>: " . $info["http_code"] . ": <pre>" . htmlspecialchars($response) . "</pre><br/>"
					. "<strong>Content-Type</strong>: " . $info["content_type"] . "<br/>"
					. "<strong>Size</strong> (Header/Request): " . $info["header_size"] . "/" . $info["request_size"] . " Bytes<br/>"
					. "<strong>Time</strong> (Total/Namelookup/Connect/Pretransfer/Starttransfer/Redirect): " . $info["total_time"] . " / " . $info["namelookup_time"] . " / " . $info["connect_time"] . " / " . $info["pretransfer_time"] . " / " . $info["starttransfer_time"] . " / " . $info["redirect_time"] . " seconds<br/>";
		Logger::notify("<strong>HTTP-SEND</strong>:<br/>" . $logMessage);

		// if message body is empty this is allowed with 204
		if (!$response && $info["http_code"]!="204") {
			Logger::error("Error with send REST client: " .$error);
			return null;
		}
		elseif (!in_array($info["http_code"], array("200", "201", "204"))) {
			Logger::warning("Get wrong response: " . $info["http_code"]);
			return null;
		}

		return JSONHandler::parseJSON($response);
	}

	/**
	 * Sets another request method.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.0 Use HTTPRequestMethod enum.
	 * @api
	 * @param HTTPRequestMethod method The request method the REST client should use.
	 * @return boolean True, if it works, false if not.
	 */
	public static function setRequestMethod($method) {
		if (!InputValidator::isRequestMethod($method)) {
			return false;
		}
		self::$HTTP_REQUEST_METHOD = $method;
		return true;
	}

	/**
	 * Disconnects and deletes all configuration data.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @api
	 */
	public static function disconnect() {

		self::$HOST = "";
		self::$SHOP = "";
		self::$AUTHTOKEN = null;
		self::$ISCONNECTED = false;
		self::$ISSSL = true;
		return true;
	}

	/**
	 * Prints the REST client as a string.
	 *
	 * This function returns the setted values of the REST client object.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.1
	 * @return String The REST client as a string.
	 */
	public function __toString() {

		if (!self::$ISCONNECTED) {
			return "<strong>You are not connected.</strong>";
		}
		else {
			return "<strong>Host</strong>: <i>" . self::$HOST . "</i><br/>" .
				"<strong>Shop</strong>: <i>" . self::$SHOP . "</i><br/>" .
				"<strong>AuthToken</strong>: <i>" . self::$AUTHTOKEN . "</i>";
		};
	}

	/**
	 * Change the time to wait with the next request.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.1.1
	 * @param int time The time in ms every reload needs to wait until get new information.
	 * @return boolean True if the change works, false if not.
	 * @api
	 */
	public static function setRequestWaitTime($time) {

		if (!InputValidator::isRangedInt($time, 0)) {
			return false;
		}

		self::$NEXT_RESPONSE_WAIT_TIME = $time;
		return true;
	}
}

/**
 * The HTTP request 'enum'.
 *
 * This are the possible HTTP request methods..
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