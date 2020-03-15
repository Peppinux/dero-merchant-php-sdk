<?php
namespace DeroMerchant;

/**
 * Class with static webhook utility functions
 */
class WebhookUtil {
	/**
	 * Verify the signature of a webhook request
	 * 
	 * @param string $reqBody The body of the request
	 * @param string $reqSignature The signature of the request found in the X-Signature header
	 * @param string $webhookSecretKey The webhook secret key of the store
	 * @return bool Request validity
	 */
	public static function verifySignature($reqBody, $reqSignature, $webhookSecretKey)
	{
		return CryptoUtil::validMAC($reqBody, $reqSignature, $webhookSecretKey);
	}
}
?>
