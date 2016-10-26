<?php
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
	const HTTP_CONTENT_TYPE_JSON = "application/json";

	/** @var String The path to the REST ressource in the shop. */
	const PATHTOREST = "rs/shops";

	/** @var String The user agent. */
	const USER_AGENT = "ePages REST SDK";

	/** @var int The time in ms the shop object should wait until the next request. */
	public static $NEXT_RESPONSE_WAIT_TIME = 600;

	/** @var String|null The authentification token (access token). */
	private static $AUTHTOKEN = null;
	
	/** @var String|null The response content. */
	private static $CONTENT;
	
	/** @var String|null The content type of the response. */
	private static $CONTENT_TYPE;

	/** @var mixed[] The saved cookies. */
	private static $COOKIES = array();
	
	/** @var mixed[] The headers of the response. */
	private static $HEADERS;

	/** @var String|null The ePages host to connect. */
	private static $HOST = null;

	/** @var HTTPRequestMethod The request method of the REST call. */
	private static $HTTP_REQUEST_METHOD = HTTPRequestMethod::GET;

	/** @var int|null The last response code. */
	private static $HTTP_RESPONSE_CODE;

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
		self::disconnect();

		// check parameters
		if (!InputValidator::isHost($host) ||
			!InputValidator::isShop($shop)) {

			Logger::warning("ep6\RESTClient\nHost (" . $host . ") or Shop (" . $shop . ") are not valid.");
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
	 * @since 0.2.1 Reset headers and cookies too.
	 */
	public static function disconnect() {

		self::errorReset();
		self::$HOST = "";
		self::$SHOP = "";
		self::$AUTHTOKEN = null;
		self::$ISCONNECTED = false;
		self::$ISSSL = true;
		self::$COOKIES = array();
		self::$HEADERS = array();

		return true;
	}

	/**
	 * Explodes a header into each element.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String header The header to parse.
	 * @since 0.2.1
	 */
	private static function explodeHeader($header) {
		
		$skipped_first = false;

		foreach(preg_split("/((\r?\n)|(\r\n?))/", $header) as $headerLine) {

			# skip the first line of header, its the status code
			if (!$skipped_first) {
				$skipped_first = true;
				continue;
			}
			
			if (!strpos($headerLine, ":")) {
				continue;
			}
			
			list($key, $value) = explode(":", $headerLine, 2);
			$value = trim($value);
			
			switch ($key) {
				case "Cookies":
					self::setCookie($key, $value);
					break;
				default:
					self::$HEADERS[$key] = $value;
			}
		}
	}

	/**
	 * Explodes a response into body and header.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String response The response to parse.
	 * @return String[] An array with two elements: header and body. If there is no body, the array has only one element.
	 * @since 0.2.1
	 */
	private static function explodeResponse($response) {

		if (strpos($response, "\n\n")) {
			return explode("\n\n", $response, 2);
		}
		elseif (strpos($response, "\r\n\r\n")) {
			return explode("\r\n\r\n", $response, 2);
		}
		elseif (strpos($response, "\r\r")) {
			return explode("\r\r", $response, 2);
		}
		else {
			return array($response, null);
		}
	}

	/**
	 * Returns the actual body.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return String The body.
	 * @since 0.2.1
	 */
	public static function getContent() {

		return self::$CONTENT;
	}

	/**
	 * Gets a specific Cookie value from header of response.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String cookieKey The key of requested cookie.
	 * @return String|null The value of the cookie or null of cookie is not set.
	 * @since 0.2.1
	 */
	public static function getCookie($cookieKey) {

		if (!InputValidator::isEmptyArrayKey(self::$COOKIES, $cookieKey)) {
			return self::$COOKIES[$cookieKey];
		}
		Logger::notify("pogo\RESTClient:\nRequested cookie is not set.");
		return;
	}

	/**
	 * Gets the Cookies from header of response.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return mixed[] All cookies as array.
	 * @since 0.2.1
	 */
	public static function getCookies() {

		return self::$COOKIES;
	}

	/**
	 * Gets a specific Header value from response.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String headerKey The key of requested cookie.
	 * @return String|null The value of the cookie or null of cookie is not set.
	 * @since 0.2.1
	 */
	public static function getHeader($headerKey) {

		if (!InputValidator::isEmptyArrayKey(self::$HEADERS, $headerKey)) {
			return self::$HEADERS[$headerKey];
		}
		Logger::notify("pogo\RESTClient:\nRequested header is not set.");
		return;
	}

	/**
	 * Returns the actual JSON body as an array.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return mixed[] The body.
	 * @since 0.2.1
	 */
	public static function getJSONContent() {

		return JSONHandler::parseJSON(self::$CONTENT);
	}

	/**
	 * Returns if the last response was 200 OK.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if the last response was OK.
	 * @since 0.2.1
	 */
	public static function isResponseOK() {

		return self::$HTTP_RESPONSE_CODE == 200;
	}

	/**
	 * Returns if the last response was 302 Found.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @return boolean True if the last response was Found.
	 * @since 0.2.1
	 */
	public static function isResponseFound() {

		return self::$HTTP_RESPONSE_CODE == 302;
	}

	/**
	 * This send function sends a special command to the REST server with additional parameter.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String command The path which is requested in the REST client.
	 * @param String[] $postParameter Add specific parameters to the REST server.
	 * @since 0.0.0
	 * @since 0.0.1 Use HTTPRequestMethod enum.
	 * @since 0.1.0 Allow empty message body if the status code is 204.
	 * @since 0.1.2 Restructure the logging message and fix the PATCH call.
	 * @since 0.1.2 Add error reporting.
	 * @since 0.1.3 Remove isRESTCommand function.
	 * @since 0.2.1 Refactor the complete send method.
	 */
	public static function send($command = "", $postParameter = array()) {

		self::errorReset();

		if (!InputValidator::isArray($postParameter)) {

			Logger::warning("ep6\RESTClient\Post parameter (" . $postParameter . ") are not valid.");
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
				"Content-Type: " . self::HTTP_CONTENT_TYPE_JSON,
				"User-Agent: " . self::USER_AGENT);

		// add authentification if there is a token
		if (InputValidator::isAuthToken(self::$AUTHTOKEN)) {
			
			array_push($headers, "Authorization: Bearer " . self::$AUTHTOKEN);
		}
			
		# parse cookies
		if (!InputValidator::isEmptyArray(self::$COOKIES)) {
			$cookiesValues = array();
			foreach (self::$COOKIES as $key => $value) {
				array_push($cookiesValues, $key . "=" . $value);
			}
			array_push($headers, "Cookie: " . implode("; ", $cookiesValues));
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
		curl_setopt($curl, CURLOPT_HEADER, 1);									// get the header

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
				$JSONpostfield = JSONHandler::createJSON($postParameter);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTREDIR, 0);	// don't post on redirects
				curl_setopt($curl, CURLOPT_POSTFIELDS, $JSONpostfield);
				break;

			case HTTPRequestMethod::PUT:
				$JSONpostfield = JSONHandler::createJSON($postParameter);
				array_push($headers, "Content-Length: " . strlen($JSONpostfield));
				curl_setopt($curl, CURLOPT_POSTFIELDS, $JSONpostfield);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
				break;

			case HTTPRequestMethod::DELETE:
				$JSONpostfield = JSONHandler::createJSON($postParameter);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
				curl_setopt($curl, CURLOPT_POSTFIELDS, $JSONpostfield);
				break;

			case HTTPRequestMethod::PATCH:
				$JSONpostfield = "[" . JSONHandler::createJSON($postParameter) . "]";
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
				curl_setopt($curl, CURLOPT_POSTFIELDS, $JSONpostfield);
				break;
		}

		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		$response = curl_exec($curl);
		$info = curl_getinfo($curl);
		$error = curl_error($curl);
		curl_close($curl);
		
		# get header and body
		list($header, $body) = self::explodeResponse($response);
		$header = trim($header);
		$content = trim($body);

		$logMessage = "Request:\n"
					. "Parameters: " . http_build_query($postParameter) . "\n"
					. $info["request_header"]
					. "Response:\n"
					. "Size (Header/Request): " . $info["header_size"] . "/" . $info["request_size"] . " Bytes\n"
					. "Time (Total/Namelookup/Connect/Pretransfer/Starttransfer/Redirect): " . $info["total_time"] . " / " . $info["namelookup_time"] . " / " . $info["connect_time"] . " / " . $info["pretransfer_time"] . " / " . $info["starttransfer_time"] . " / " . $info["redirect_time"] . " seconds\n"
					. $response . "\n";;
		Logger::notify("ep6\RESTClient:\n" . $logMessage);

		# parse header, response code and body
		self::explodeHeader($header);
		self::$HTTP_RESPONSE_CODE = (int) $info["http_code"];
		if (!InputValidator::isEmpty($content)) {

			self::$CONTENT = $content;
		}
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
	 * Sets a specific cookie to the request header.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @param String cookieKey The key of the cookie.
	 * @param String cookieValue The value of the cookie.
	 * @since 0.2.1
	 */
	public static function setCookie($cookieKey, $cookieValue) {

		self::$COOKIES[$cookieKey] = $cookieValue;
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

	/**
	 * This function resets all environment variables, like headers from old response.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.2.1
	 */
	public static function resetLastResponse() {

		self::unsetCookies();
		self::$CONTENT = null;
		self::$CONTENT_TYPE = null;
		self::$DATE = null;
		self::$HTTP_RESPONSE_CODE = 0;
		self::$HEADERS = array();
	}

	/**
	 * Unsets all cookies.
	 *
	 * @author David Pauli <contact@david-pauli.de>
	 * @since 0.2.1
	 */
	public static function unsetCookies() {

		self::$COOKIES = array();
	}
}
?>