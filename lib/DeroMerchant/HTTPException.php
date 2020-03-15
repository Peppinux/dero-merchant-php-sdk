<?php
namespace DeroMerchant;

/**
 * Custom Exception thrown by the Client if something goes wrong when sending a request to the API
 */
class HTTPException extends \Exception {
	private $description;
	private $url;

	/**
	 * Create an HTTPException
	 * 
	 * @param string $description Type of the error
	 * @param string $url URL of the API resource hit when the error occured
	 * @param int $code Error code
	 */
	public function __construct($description, $url, $code = 0, $message = null, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);

		$this->description = $description;
		$this->url = $url;
	}

	/**
	 * @return string $url
	 */
	public function getURL()
	{
		return $url;
	}

	/**
	 * Get a formatted message of the exception
	 * 
	 * @return string message
	 */
	public function __toString()
	{
		return 'DeroMerchant Client: ' . $this->description . ' error ' . $this->code . ($this->message ? ': ' : '') . $this->message . '. Resource URL: ' . $this->url; 
	}
}
?>
