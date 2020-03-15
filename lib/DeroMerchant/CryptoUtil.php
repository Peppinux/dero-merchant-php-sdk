<?php
	namespace DeroMerchant;

	/**
	 * Class with static utility crypto functions for internal use.
	 */
	class CryptoUtil {
		/**
		 * @param string $message
		 * @param string $key
		 * @return string hash
		 */
		public static function signMessage($message, $key)
		{
			return hash_hmac('sha256', $message, hex2bin($key));
		}

		/**
		 * @param string $message
		 * @param string $messageMAC
		 * @param string $key
		 * @return bool valid
		 */
		public static function validMAC($message, $messageMAC, $key)
		{
			$signedMessage = self::signMessage($message, $key);
			return hash_equals($messageMAC, $signedMessage);
		}
	}
?>
