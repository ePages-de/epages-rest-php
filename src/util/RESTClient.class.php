<?php
namespace ep6;
/**
 * This is the pure REST client. It is used in a static way.
 *
 * To connect or reconnect use:
 *   RESTClient::connect(HOSTNAME, SHOPNAME, AUTHTOKEN, ISSSL);
 *
 * To send a command use:
 *   RESTClient::send("products");
 *
 * To change the request method use:
 *   RESTClient::setRequestMethod("GET");
 */
class RESTClient {

	/**
	 * The ePages host to connect.
	 */
	private static $HOST;

	/**
	 * The refered ePages ahop.
	 */
	private static $SHOP;

	/**
	 * The authentification token (access token).
	 */
	private static $AUTHTOKEN;

	/**
	 * You use https or http? Default is true.
	 */
	private static $ISSSL;

	/**
	 * Boolean to log whether the client is connected or not.
	 */
	private static $ISCONNECTED = false;

	/**
	 * The request method of the REST call.
	 */
	private static $HTTP_REQUEST_METHOD = "GET";

	/**
	 * The path to the REST ressource in the shop.
	 */
	const PATHTOREST = "rs/shops";

	/**
	 * The accepted value of the response.
	 */
	const HTTP_ACCEPT = "application/vnd.epages.v1+json";

	/**
	 * The content type of the request.
	 */
	const HTTP_CONTENT_TYPE = "application/json";

	/**
	 * The constructor for the main class.
	 *
	 * @param String	$host		The ePages host to connect.
	 * @param String	$shop		The refered ePages shop.
	 * @param String	$authToken	The authentificaton token to connect via REST.
	 * @param boolean	$isssl		True, if you use SSL, false if not. Default value is true.
	 */
	public static function connect($host, $shop, $authToken, $isssl) {

		// check parameter
		if (!InputValidator::isHost($host) || !InputValidator::isShop($shop) || !InputValidator::isAuthToken($authToken)) {
			self::$ISCONNECTED = false;
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
	 */
	public static function printStatus() {

		if (!self::$ISCONNECTED) {
			Logger::force("The status of the REST client:<br/>" . 
				"<strong>You are not connected.</strong>");
		}
		else {
			Logger::force("The status of the REST client:<br/>" .
				"<strong>host</strong>: <i>" . self::$HOST . "</i><br/>" .
				"<strong>shop</strong>: <i>" . self::$SHOP . "</i><br/>" .
				"<strong>authToken</strong>: <i>" . self::$AUTHTOKEN . "</i>");
		}
	}

	/**
	 * This send function sends a special command to the REST server.
	 *
	 * @param String	command		The path which is requested in the REST client.
	 * @param String	locale		The localization to get.
	 * @param String	postfields	Add specific parameters to the REST server.
	 * @return String	The returned JSON object.
	 */
	public static function sendWithLocalization($command, $locale, $postfields = array()) {
		
		// cheeck parameters
		if (!InputValidator::isRESTCommand($command) && !InputValidator::isLocale($locale) && !InputValidator::isArray($postfields)) {
			return null;
		}
		return self::send($command . "?locale=" . $locale, $postfields);
	}

	/**
	 * This send function sends a special command to the REST server with additional parameter.
	 *
	 * @param String	command		The path which is requested in the REST client.
	 * @param String	postfields	Add specific parameters to the REST server.
	 * @return array	The returned elements as array.
	 */
	public static function send($command, $postfields = array()) {
		
		if (!InputValidator::isRESTCommand($command)) {
			return null;
		}
		if (!self::$ISCONNECTED) {
			return null;
		}
		if (!InputValidator::isArray($postfields)) {
			return null;
		}

		$protocol = self::$ISSSL ? "https" : "http";
		$url = $protocol . "://" . self::$HOST . "/" . self::PATHTOREST . "/" . self::$SHOP . "/" . $command;
		
		$headers = array(
			"Accept: " . self::HTTP_ACCEPT,
			"Authorization: Bearer " . self::$AUTHTOKEN,
			"Content-Type: " . self::HTTP_CONTENT_TYPE);

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
			case "GET":
				curl_setopt($curl, CURLOPT_HTTPGET, 1);
				break;
			case "POST":
				$JSONpostfield = JSONHandler::createJSON($postfields);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTREDIR, 0);	// don't post on redirects
				curl_setopt($curl, CURLOPT_POSTFIELDS, $JSONpostfield);
				break;
			case "PUT":
				$JSONpostfield = JSONHandler::createJSON($postfields);
				array_push($headers, "Content-Length: " . strlen($JSONpostfield));
				curl_setopt($curl, CURLOPT_POSTFIELDS, $JSONpostfield);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
				break;
			case "DELETE":
				$JSONpostfield = JSONHandler::createJSON($postfields);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
				curl_setopt($curl, CURLOPT_POSTFIELDS, $JSONpostfield);
				break;
			case "PATCH":
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

		if (!$response) {
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
	 * @param String	method	The request method the REST client should use.
	 * @return boolean	True, if it works, false if not.
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
	 */
	public static function disconnect() {
		
		self::$HOST = "";
		self::$SHOP = "";
		self::$AUTHTOKEN = "";
		self::$ISCONNECTED = false;
		return true;
	}
}
?>