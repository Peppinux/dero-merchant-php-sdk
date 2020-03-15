<?php
namespace DeroMerchant;

/**
 * Client with default scheme, host and API version
 */
class DefaultClient extends Client {
	private $defaultScheme = 'https';
	private $defaultHost = 'merchant.dero.io';
	private $defaultAPIVersion = 'v1';
	
	/**
	 * @param string $apiKey
	 * @param string $secretKey
	 */
	public function __construct($apiKey, $secretKey) {
		parent::__construct($this->defaultScheme, $this->defaultHost, $this->defaultAPIVersion, $apiKey, $secretKey);
	}
}
?>
