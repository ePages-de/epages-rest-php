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
 * @package ep6
 * @since 0.0.0
 * @since 0.0.3 Use HTTPRequestMethod enum.
 * @since 0.1.0 Add a recommended wait time for the next request.
 * @since 0.1.1 Now the object is printable via echo.
 * @since 0.1.1 Add function to change the wait time for next REST request.
 * @since 0.1.1 Set $HOST and $SHOP to default value null.
 * @since 0.1.2 Add error reporting.
 * @subpackage Util
 */
class RESTClient {

	use ErrorReporting;
	
	/** @var String The accepted value of the response. */
	const HTTP_ACCEPT = "application/vnd.epages.v1+json";

	/** @var String The content type of the request. */
	const HTTP_CONTENT_TYPE = "application/json";

	/** @var String The path to the REST ressource in the shop. */
	const PATHTOREST = "rs/shops";

	/** @var int The time in ms the shop object should wait until the next request. */
	public static $NEXT_RESPONSE_WAIT_TIME = 600;

	/** @var String|null The authentification token (access token). */
	private static $AUTHTOKEN = null;

	/** @var String|null The ePages host to connect. */
	private static $HOST = null;

	/** @var HTTPRequestMethod The request method of the REST call. */
	private static $HTTP_REQUEST_METHOD = HTTPRequestMethod::GET;

	/** @var boolean Boolean to log whether the client is connected or not. */
	private static $ISCONNECTED = false;

	/** @var boolean|null You use https or http? Default is true. */
	private static $ISSSL = true;

	/** @var String|null The refered ePages ahop. */
	private static $SHOP = null;

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

		self::$HOST = $host;
		self::$SHOP = $shop;
		self::$ISSSL = $isssl;
		self::$AUTHTOKEN = $authToken;
		self::$ISCONNECTED = true;

		return true;
	}

	/**
	 * Disconnects and deletes all configuration data.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.0.0
	 * @since 0.1.2 Add error reporting.
	 */
	public static function disconnect() {

		self::errorReset();
		self::$HOST = "";
		self::$SHOP = "";
		self::$AUTHTOKEN = null;
		self::$ISCONNECTED = false;
		self::$ISSSL = true;

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
	 */
	public static function send($command = "", $postfields = array()) {

		self::errorReset();
		$JSONpostfield = "";

		if (!InputValidator::isArray($postfields)) {

			Logger::warning("ep6\RESTClient\nCommand (" . $command . ") or postfields (" . $postfields . ") are not valid.");
			self::errorSet("RESTC-5");
			return null;
		}

		if (!self::$ISCONNECTED) {

			Logger::warning("ep6\RESTClient\nClient is not connected.");
			self::errorSet("RESTC-6");
			return null;
		}

		$protocol = self::$ISSSL ? "https" : "http";
		$url = $protocol . "://" . self::$HOST . "/" . self::PATHTOREST . "/" . self::$SHOP . "/" . $command;

		$headers = array(
				"Accept: " . self::HTTP_ACCEPT,
				"Content-Type: " . self::HTTP_CONTENT_TYPE);

		// add authentification if there is a token
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
		curl_setopt($curl, CURLINFO_HEADER_OUT, 1);								// save the header in the log

		if (self::$ISSSL) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);						// don't check the peer ssl cerrificate
			curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
			curl_setopt($curl, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTPS);
			curl_setopt($curl, CURLOPT_SSLVERSION, 0);							// default ssl version
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
				$JSONpostfield = "[" . JSONHandler::createJSON($postfields) . "]";
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
				curl_setopt($curl, CURLOPT_POSTFIELDS, $JSONpostfield);
				break;
		}

		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		$response = curl_exec($curl);
		$info = curl_getinfo($curl);
		$error = curl_error($curl);
		curl_close($curl);

		$logMessage = "Request:\n"
					. "Parameters: " . $JSONpostfield . "\n"
					. $info["request_header"]
					. "Response:\n"
					. $info["http_code"] . ": " . $response . "\n"
					. "Content-Type: " . $info["content_type"] . "\n"
					. "Size (Header/Request): " . $info["header_size"] . "/" . $info["request_size"] . " Bytes\n"
					. "Time (Total/Namelookup/Connect/Pretransfer/Starttransfer/Redirect): " . $info["total_time"] . " / " . $info["namelookup_time"] . " / " . $info["connect_time"] . " / " . $info["pretransfer_time"] . " / " . $info["starttransfer_time"] . " / " . $info["redirect_time"] . " seconds\n";
		Logger::notify("ep6\RESTClient:\n" . $logMessage);

		// if message body is empty this is allowed with 204
		if (!$response && $info["http_code"] != "204") {
			Logger::error("ep6\RESTClient\nError with send REST client: " . $error);
			self::errorSet("RESTC-7");
			return null;
		}
		elseif (!in_array($info["http_code"], array("200", "201", "204"))) {
			Logger::warning("ep6\RESTClient\nGet wrong response: " . $info["http_code"]);
			self::errorSet("RESTC-8");
			return null;
		}

		return JSONHandler::parseJSON($response);
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