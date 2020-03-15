# DERO Merchant PHP SDK
Library with bindings for the [DERO Merchant REST API](https://merchant.dero.io/docs) for accepting DERO payments on a PHP backend.

## Requirements
- A store registered on your [DERO Merchant Dashboard](https://merchant.dero.io/dashboard) to receive an API Key and a Secret Key, required to send requests to the API.
- A web server running on **PHP v5.6.0 or higher**.
  - cURL extension
  - JSON extension

## Installation
**Using Composer**

`composer require peppinux/dero-merchant-php-sdk`

**Manual**

Download the [latest realease](https://github.com/Peppinux/dero-merchant-php-sdk/releases) and extract it inside your project folder.

## Usage
### Import
**Using Composer**

`require __DIR__ . '/vendor/autoload.php';`

**Manual**

`require __DIR__ . '/path/to/extracted/dero-merchant-php-sdk/require.php';`

### Setup
```php
$dmClient = new \DeroMerchant\Client(
    'https', // Scheme
    'merchant.dero.io', // Host
    'v1', // API Version
    'API_KEY_OF_YOUR_STORE_GOES_HERE', // API Key
    'SECRET_KEY_OF_YOUR_STORE_GOES_HERE' // Secret Key
);
// OR
$dmClient = new \DeroMerchant\DefaultClient(
    'API_KEY_OF_YOUR_STORE_GOES_HERE',
    'SECRET_KEY_OF_YOUR_STORE_GOES_HERE'
);

try
{
    $dmClient->ping();
}
catch(\Exception $e)
{
    // Server is offline OR bad Scheme/Host/API Version were provided.
    // Handle exception.
}
```

### Create a Payment
```php
try
{
    // $res = $dmClient->createPayment('USD', 1); // USD value will be converted to DERO
    // $res = $dmClient->createPayment('EUR', 100); // Same thing goes for EUR and other currencies supported by the CoinGecko API V3
    $res = $dmClient->createPayment('DERO', 10);

    print_r($res);
    /*
        Array 
        ( 
            [paymentID] => e7baca2f8c620e910bbfcdcbec1606512fa01d96a473edcab76d40ab55cc6a88 
            [status] => pending 
            [currency] => DERO 
            [currencyAmount] => 10 
            [exchangeRate] => 1 
            [deroAmount] => 10.000000000000 
            [atomicDeroAmount] => 10000000000000 
            [integratedAddress] => dETiaFw6kkrSQ8BByamH8P9iNUCfYsLnUHTL9KftUBRZZEt44i86djtWr9sMpudU955wnLMwcv2YuNGDuTbQwrwDe2tRw1PDmRmHQGwKZ3LdZ54gfgcpcSgo5LPf1S2FQkTE8kJ5Wy3YAW 
            [creationTime] => 2020-02-20T19:08:24.271749Z 
            [ttl] => 60 
        ) 
    */
}
catch(\Exception $e)
{
    // Handle exception.
}
```

### Get a Payment from its ID
```php
try
{
    $paymentID = 'e7baca2f8c620e910bbfcdcbec1606512fa01d96a473edcab76d40ab55cc6a88';
    $res = $dmClient->getPayment($paymentID);
    
    print_r($res);
    /*
        Array 
        ( 
            [paymentID] => e7baca2f8c620e910bbfcdcbec1606512fa01d96a473edcab76d40ab55cc6a88 
            [status] => pending 
            [currency] => DERO 
            [currencyAmount] => 10 
            [exchangeRate] => 1 
            [deroAmount] => 10.000000000000 
            [atomicDeroAmount] => 10000000000000 
            [integratedAddress] => dETiaFw6kkrSQ8BByamH8P9iNUCfYsLnUHTL9KftUBRZZEt44i86djtWr9sMpudU955wnLMwcv2YuNGDuTbQwrwDe2tRw1PDmRmHQGwKZ3LdZ54gfgcpcSgo5LPf1S2FQkTE8kJ5Wy3YAW 
            [creationTime] => 2020-02-20T19:08:24.271749Z 
            [ttl] => 53 
        ) 
    */
}
catch(\Exception $e)
{
    // Handle exception.
}
```

### Get an array of Payments from their IDs
```php
try
{
    $paymentIDs = array(
        'e7baca2f8c620e910bbfcdcbec1606512fa01d96a473edcab76d40ab55cc6a88',
        '38ad8cf0c5da388fe9b5b44f6641619659c99df6cdece60c6e202acd78e895b1'
    );
    $res = $dmClient->getPayments($paymentIDs);

    print_r($res);
    /*
        Array
        ( 
            [0] => Array 
            ( 
                [paymentID] => 38ad8cf0c5da388fe9b5b44f6641619659c99df6cdece60c6e202acd78e895b1
                [status] => paid 
                [currency] => DERO 
                [currencyAmount] => 10 
                [exchangeRate] => 1 
                [deroAmount] => 10.000000000000 
                [atomicDeroAmount] => 10000000000000 
                [integratedAddress] => dETiaFw6kkrSQ8BByamH8P9iNUCfYsLnUHTL9KftUBRZZEt44i86djtWr9sMpudU955wnLMwcv2YuNGDuTbQwrwDe2tRbFua6e8dW1xcFY6wPTBwHDPNN2eC4gdDNzhJWUL79pD2Tn2ksE 
                [creationTime] => 2020-01-16T16:49:59.131189Z 
                [ttl] => 0
            ) 
            [1] => Array 
            ( 
                [paymentID] => e7baca2f8c620e910bbfcdcbec1606512fa01d96a473edcab76d40ab55cc6a88
                [status] => pending 
                [currency] => DERO 
                [currencyAmount] => 10 
                [exchangeRate] => 1 
                [deroAmount] => 10.000000000000 
                [atomicDeroAmount] => 10000000000000 
                [integratedAddress] => dETiaFw6kkrSQ8BByamH8P9iNUCfYsLnUHTL9KftUBRZZEt44i86djtWr9sMpudU955wnLMwcv2YuNGDuTbQwrwDe2tRw1PDmRmHQGwKZ3LdZ54gfgcpcSgo5LPf1S2FQkTE8kJ5Wy3YAW 
                [creationTime] => 2020-02-20T19:08:24.271749Z 
                [ttl] => 49
            )
        ) 
    */
}
catch(\Exception $e)
{
    // Handle exception.
}
```

### Get an array of filtered Payments
_Not detailed because this endpoint was created for an internal usecase._
```php
try
{
    $res = $dmClient->getFilteredPayments($limit = null, $page = null, $sortBy = null, $orderBy = null, $statusFilter = null, $currencyFilter = null);

    print_r($res);
}
catch(\Exception $e)
{
    // Handle exception.
}
```

### Get Pay helper page URL
```php
$paymentID = 'e7baca2f8c620e910bbfcdcbec1606512fa01d96a473edcab76d40ab55cc6a88';
$payURL = $dmClient->getPayHelperURL($paymentID);

print($payURL); // https://merchant.dero.io/pay/e7baca2f8c620e910bbfcdcbec1606512fa01d96a473edcab76d40ab55cc6a88
```

### Verify Webhook Signature
When using Webhooks to receive Payment status updates, it is highly suggested to verify the HTTP requests are actually sent by the DERO Merchant server thorugh the X-Signature header.

**Example using no frameworks.** File **dero_merchant_webhook_example.php**:
```php
<?php
    require __DIR__ . '/vendor/autoload.php'; // Using Composer
    // OR
    // require __DIR__ . '/path/to/extracted/dero-merchant-php-sdk/require.php'; // Manual

    $webhookSecretKey = 'THE_WEBHOOK_SECRET_KEY_OF_YOUR_STORE_GOES_HERE';

    $reqBody = file_get_contents('php://input');
    $reqSignature = $_SERVER['HTTP_X_SIGNATURE'];
    
    $valid = \DeroMerchant\WebhookUtil::verifySignature($reqBody, $reqSignature, $webhookSecretKey);

	if($valid === TRUE)
	{
        // Request can be trusted.

        $payload = json_decode($reqBody, true);
        /*
            Array
            (
                [paymentID] => e7baca2f8c620e910bbfcdcbec1606512fa01d96a473edcab76d40ab55cc6a88
                [status] => paid
            )
        */
    }
    else
    {
        // DON'T trust the request.
    }
?>
```
