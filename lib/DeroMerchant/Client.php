<?php
namespace DeroMerchant;

/**
 * Dero Merchant Client. Has methods to interact with the Dero Merchant REST API.
 */
class Client
{
	private $scheme;
	private $host;
	private $apiVersion;
	private $baseURL;
	public $connTimeout = 10;

	private $apiKey;
	private $secretKey;

	/**
	 * Create a Client
	 * 
	 * @param string $scheme
	 * @param string $host
	 * @param string $apiVersion
	 * @param string $apiKey
	 * @param string $secretKey
	 */
	public function __construct($scheme, $host, $apiVersion, $apiKey, $secretKey) {
		$this->scheme = $scheme;
		$this->host = $host;
		$this->apiVersion = $apiVersion;
		$this->baseURL = $scheme . '://' . $host . '/api/' . $apiVersion;

		$this->apiKey = $apiKey;
		$this->secretKey = $secretKey;
	}

	/**
	 * Send a request to the API
	 * 
	 * @param string $method
	 * @param string $endpoint
	 * @param array $queryParams
	 * @param array $body
	 * @param bool $signBody
	 * @return array Response body
	 * @throws \DeroMerchant\HTTPException
	 */
	public function sendRequest($method, $endpoint, $queryParams = null, $payload = null, $signBody = FALSE) {
		$url = $this->baseURL . $endpoint;
		
		if($queryParams != null)
		{
			$url = $url . '?' . http_build_query($queryParams);
		}

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connTimeout);
		
		if($method !== 'GET')
		{
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		}

		$headers = array(
			'X-API-Key: ' . $this->apiKey
		);

		if($payload != null)
		{
			$jsonPayload = json_encode($payload);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);

			array_push($headers,
				'Content-Type: application/json',
				'Accept: application/json'
			);

			if($signBody === TRUE)
			{
				$signature = CryptoUtil::signMessage($jsonPayload, $this->secretKey);
				array_push($headers, 'X-Signature: ' . $signature);
			}
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_USERAGENT, 'DeroMerchant_Client_PHP/1.0');

		$res = curl_exec($ch);

		$curlErrCode = curl_errno($ch);
		if($curlErrCode !== 0)
		{
			throw new HTTPException('cURL', $url, $curlErrCode, curl_error($ch));
		}

		$httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
		
		curl_close($ch);

		$jsonRes = json_decode($res, true);

		if($httpCode < 200 || $httpCode > 299)
		{
			$jsonErrCode = json_last_error();
			if($jsonErrCode === 0 && array_key_exists('error', $jsonRes))
			{
				throw new HTTPException('API', $url, $jsonRes['error']['code'], $jsonRes['error']['message']);
			} else
			{
				throw new HTTPException('HTTP', $url, $httpCode);
			}
		}

		return $jsonRes;
	}

	/**
	 * Ping the API
	 * 
	 * @return array Ping response body
	 */
	public function ping()
	{
		return $this->sendRequest('GET', '/ping');
	}

	/**
	 * Create a new payment
	 * 
	 * @param string $currency
	 * @param float $amount
	 * @return array Payment
	 */
	public function createPayment($currency, $amount)
	{
		$payload = array(
			'currency' => $currency,
			'amount' => $amount
		);
		return $this->sendRequest('POST', '/payment', null, $payload, true);
	}

	/**
	 * Get a payment from its ID
	 * 
	 * @param string $paymentID
	 * @return array Payment
	 */
	public function getPayment($paymentID)
	{
		$endpoint = '/payment/' . $paymentID;

		return $this->sendRequest('GET', $endpoint);
	}

	/**
	 * Get an array of payments from their IDs
	 * 
	 * @param array $paymentIDs
	 * @return array Payments
	 */
	public function getPayments($paymentIDs)
	{
		return $this->sendRequest('POST', '/payments', null, $paymentIDs);
	}

	/**
	 * Get filtered payments
	 * 
	 * @param int $limit
	 * @param int $page
	 * @param string $sortBy
	 * @param string $orderBy
	 * @param string $statusFilter
	 * @param string $currencyFilter
	 * @return array Response
	 */
	public function getFilteredPayments($limit = null, $page = null, $sortBy = null, $orderBy = null, $statusFilter = null, $currencyFilter = null)
	{
		$queryParams = array(
			'limit' => $limit,
			'page' => $page,
			'sort_by' => $sortBy,
			'order_by' => $orderBy,
			'status' => $statusFilter,
			'currency' => $currencyFilter
		);

		return $this->sendRequest('GET', '/payments', $queryParams);
	}

	/**
	 * Get Pay helper URL of Payment ID
	 * 
	 * @param string $paymentID
	 * @return string Pay helper URL
	 */
	public function getPayHelperURL($paymentID)
	{
		return $this->scheme . '://' . $this->host . '/pay/' . $paymentID;
	}
}
?>
