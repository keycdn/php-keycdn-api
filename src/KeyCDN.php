<?php
/**
 * Library for the KeyCDN API
 *
 * @author Tobias Moser
 * @version 0.1
 */
class KeyCDN {
	/**
	 * @var string
	 */
	private $username;

	/**
	 * @var string
	 */
	private $password;

	/**
	 * @var string
	 */
	private $endpoint;

	/**
	 * @param string $username
	 * @param string $password
	 * @param string|null $endpoint
	 */
	public function __construct($username, $password, $endpoint = null) {
		if($endpoint === null) {
			$endpoint = 'https://www.keycdn.com';
		}

		$this->setUsername($username);
		$this->setPassword($password);
		$this->setEndpoint($endpoint);
	}

	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @param string $username
	 * @return $this
	 */
	public function setUsername($username) {
		$this->username = (string) $username;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param string $password
	 * @return $this
	 */
	public function setPassword($password) {
		$this->password = (string) $password;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEndpoint() {
		return $this->endpoint;
	}

	/**
	 * @param string $endpoint
	 * @return $this
	 */
	public function setEndpoint($endpoint) {
		$this->endpoint = (string) $endpoint;
		return $this;
	}

	/**
	 * @param string $selectedCall
	 * @param array $params
	 * @return string
	 * @throws Exception
	 */
	public function get($selectedCall, array $params = array()) {
		return $this->execute($selectedCall, 'GET', $params);
	}

	/**
	 * @param string $selectedCall
	 * @param array $params
	 * @return string
	 * @throws Exception
	 */
	public function post($selectedCall, array $params = array()) {
		return $this->execute($selectedCall, 'POST', $params);
	}

	/**
	 * @param string $selectedCall
	 * @param array $params
	 * @return string
	 * @throws Exception
	 */
	public function put($selectedCall, array $params = array()) {
		return $this->execute($selectedCall, 'PUT', $params);
	}

	/**
	 * @param string $selectedCall
	 * @param array $params
	 * @return string
	 * @throws Exception
	 */
	public function delete($selectedCall, array $params = array()) {
		return $this->execute($selectedCall, 'DELETE', $params);
	}

	/**
	 * @param string $selectedCall
	 * @param $methodType
	 * @param array $params
	 * @return string
	 * @throws Exception
	 */
	private function execute($selectedCall, $methodType, array $params) {
		$endpoint = rtrim($this->endpoint, '/') . '/' . ltrim($selectedCall, '/');

		// start with curl and prepare accordingly
		$ch = curl_init();

		// create basic auth information
		curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);

		// return transfer as string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// set curl timeout
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);

		// retrieve headers
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLINFO_HEADER_OUT, 1);

		// set request type
		if (!in_array($methodType, array('POST', 'GET'))) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $methodType);
		}

		$queryStr = http_build_query($params);
		// send query-str within url or in post-fields
		if (in_array($methodType, array('POST', 'PUT', 'DELETE'))) {
			$reqUri = $endpoint;
			curl_setopt($ch, CURLOPT_POSTFIELDS, $queryStr);
		} else {
			$reqUri = $endpoint . '?' . $queryStr;
		}

		// url
		curl_setopt($ch, CURLOPT_URL, $reqUri);

		// make the request
		$result = curl_exec($ch);
		$headers = curl_getinfo($ch);
		$curlError = curl_error($ch);

		curl_close($ch);

		// get json_output out of result (remove headers)
		$jsonOutput = substr($result, $headers['header_size']);

		// error catching
		if (!empty($curlError) || empty($jsonOutput)) {
			throw new Exception("KeyCDN-Error: {$curlError}, Output: {$jsonOutput}");
		}

		return $jsonOutput;
	}
}
