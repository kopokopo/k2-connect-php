# Kopokopo PHP SDK

[![Latest Stable Version](https://img.shields.io/packagist/v/kopokopo/k2-connect-php)](https://packagist.org/packages/kopokopo/k2-connect-php)

This is a module to assist php developers in consuming Kopokopo's API

## Installation

You can install the PHP SDK via composer.

The recommended way to install the SDK is with Composer.

```
composer require kopokopo/k2-connect-php
```

## Initialisation

The package should be configured with your client id and client secret which you can get from your account on the kopokopo's app

```php
//Store your client id and client secret as environment variables

//Including the kopokopo sdk
use Kopokopo\SDK\K2;

// do not hard code these values
$options = [
    'clientId' => 'YOUR_CLIENT_ID', 
    'clientSecret' => 'YOUR_CLIENT_SECRET',
    'apiKey' => 'YOUR_API_KEY',
    'baseUrl' => 'https://sandbox.kopokopo.com'
];

$K2 = new K2($options);
```

### After initialization, you can get instances of offered services as follows:

- [Tokens](#tokenservice) : `$tokens = $K2->TokenService();`
- [Webhooks](#webhooks) : `$webhooks = $K2->Webhooks();`
- [STK PUSH](#stkservice) : `$stk = $K2->StkService();`
- [Pay](#payservice) : `$pay = $K2->PayService();`
- [Settlement Transfer](#settlementtransferservice) : `$transfer = $K2->SettlementTransferService();`
- [PollingService](#pollingservice) : `$polling = $K2->PollingService();`
- [SmsNotificationService](#smsnotificationservice) : `$sms_notification = $K2->SmsNotificationService();`

## Usage

### Tokens

You will need to pass an access token when sending data to Kopokopo's API.

This will return `accessToken` and `expiresIn` values

```php
use Kopokopo\SDK\K2;

// Do not hard code these values
$options = [
  'clientId' => 'YOUR_CLIENT_ID', 
  'clientSecret' => 'YOUR_CLIENT_SECRET',
  'apiKey' => 'YOUR_API_KEY',
  'baseUrl' => 'https://sandbox.kopokopo.com'
];

$K2 = new K2($options);

// Get one of the services
$tokens = $K2->TokenService();

// Use the service
$result = $tokens->getToken();

//print the result
print_r($result);
```

### Webhooks

- Consuming

```php
// TODO: review this
$router->map('POST', '/webhook', function () {
    global $K2;
    global $response;

    $webhooks = $K2->Webhooks();

    $json_str = file_get_contents('php://input');
    var_dump($json_str);

    $response = $webhooks->webhookHandler($json_str, $_SERVER['HTTP_X_KOPOKOPO_SIGNATURE']);

    echo json_encode($response);
});
```

- Subscription

```php
$webhooks = $K2->Webhooks();

//To subscribe to a webhook
$response = $webhooks->subscribe([
    'eventType' => 'buygoods_transaction_received',
    'url' => 'http://localhost:8000/webhook',
    'scope' => 'till',
    'scopeReference' => '000000',
    'accessToken' => 'my_access_token'
]);

print_r($response);
```

### STK PUSH

```php
$stk = $K2->StkService();
$result = $stk->initiateIncomingPayment([
                'paymentChannel' => 'M-PESA STK Push',
                'tillNumber' => 'K000000',
                'firstName' => 'Jane',
                'lastName' => 'Doe',
                'phoneNumber' => '0712345678',
                'amount' => 3455,
                'email' => 'example@example.com',
                'callbackUrl' => 'http://localhost:8000/test',
                'accessToken' => 'myRand0mAcc3ssT0k3n',
            ]);
print_r($result);
```

For other usage examples check out the [example app](https://github.com/NicoNjora/k2-connect-php-example).

## Services

The only supported ISO currency code at the moment is: `KES`

### `TokenService`

- `$TokenService->getToken()` to get an access token.

  - The response will have the following structure
  ```php
  [ 'status' => 'success',
    'data' => [
      'accessToken' => 'GT6576QGYdYh8i5s8DnxUQVphFewh-8eiO2',
      'tokenType' => 'Bearer',
      'expiresIn' => 3600,
      'createdAt' => '2021-04-06T13:49:50+03:00'
    ]
  ]
  ```

NB: The access token is required to send subsequent requests

- `$TokenService->revokeToken(['accessToken' => 'myRand0mAcc3ssT0k3n'])` to revoke an access token.

NB: The access token cannot be used to send subsequent requests

- `$TokenService->introspectToken(['accessToken' => 'myRand0mAcc3ssT0k3n'])` to introspect a token.

- `$TokenService->infoToken(['accessToken' => 'myRand0mAcc3ssT0k3n'])` to get more information on a token


### `StkService`

- `$StkService->initiateIncomingPayment([ stkOptions ])`: `stkOptions`: An array of arrays containing the following keys:

  - `tillNumber`: Your online payments short code from Kopo Kopo's Dashboard `REQUIRED`
  - `firstName`: Customer's first name
  - `lastName`: Customer's last name
  - `phoneNumber`: Phone number to pull money from. `REQUIRED`
  - `email`: Customer's email address
  - `currency`: 3-digit ISO format currency code. `REQUIRED`
  - `amount`: Amount to charge. `REQUIRED`
  - `callbackUrl`: Url that the [result](#responsesandresults) will be posted to `REQUIRED`
  - `paymentChannel`: Payment channel. Default is: `"M-PESA STK Push"`. `REQUIRED`
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`
  - `metadata`: It is a hash containing a maximum of 5 key value pairs

- `$StkService->getStatus([location ])`:

  - `location`: The request location you get when you send a request
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`

For more information, please read <https://api-docs.kopokopo.com/#receive-payments-from-m-pesa-users-via-stk-push>

### `PayService`

- `PayService->addPayRecipient([ payRecipientOptions ])`: `payRecipientOptions`: An array of arrays containing the following keys:

  - `type`: Recipient type `REQUIRED`
    - Mobile Wallet Recipient(`mobile_wallet`)
      - `firstName`: Pay recipient's first name `REQUIRED`
      - `lastName`: Pay recipient's last name `REQUIRED`
      - `phoneNumber`: Pay recipient's phone number `REQUIRED`
      - `email`: Pay recipient's email number
      - `network`: Pay recipient's network `REQUIRED`
    - Bank Account Recipient(`bank_account`)
      - `accountName`: Pay recipient's account name `REQUIRED`
      - `accountNumber`: Pay recipient's account number `REQUIRED`
      - `bankBranchRef`: Bank branch reference from the kopokopo dashboard `REQUIRED`
      - `settlementMethod`: Settlement method `REQUIRED`      
    - External Till Recipient(`till`)
      - `tillNumber`: Pay recipient's till number `REQUIRED`
      - `tillName`: Pay recipient's till name `REQUIRED`
    - Paybill(`paybill`)
      - `paybillName`: Pay recipient's paybill name `REQUIRED`
      - `paybillNumber`: Pay recipient's paybill number `REQUIRED`
      - `paybillAccountNumber`: Pay recipient's account number `REQUIRED`
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`

- `PayService->sendPay([ payOptions ])`: `payOptions`: An array of arrays containing the following keys:

  - `destinationType`: The recipient type. `REQUIRED`
  - `destinationReference`: The recipient reference. `REQUIRED`
  - `currency`: 3-digit ISO format currency code. `REQUIRED`
  - `amount`: Amount to charge. `REQUIRED`
  - `callbackUrl`: Url that the [result](#responsesandresults) will be posted to `REQUIRED`
  - `description`: Payment description `REQUIRED`
  - `tags`: Tags associated with the payment
  - `category`: Category that the payment belongs to
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`
  - `metadata`: It is a hash containing a maximum of 5 key value pairs

- `PayService->getStatus([ location ])`:

  - `location`: The request location you get when you send a request
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`

For more information, please read <https://api-docs.kopokopo.com/#send-money-pay>

### `SettlementTransferService`

- `SettlementTransferService->createMerchantBankAccount([ accountOpts ])`: `accountOpts`: An array of arrays containing the following keys:

  - `accountName`: Settlement Account Name `REQUIRED`
  - `bankBranchRef`: Settlement Bank Branch Reference `REQUIRED`
  - `accountNumber`: Settlement account number `REQUIRED`
  - `settlementMethod`: Settlement method `REQUIRED`
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`

- `SettlementTransferService->createMerchantWallet([ accountOpts ])`: `accountOpts`: An array of arrays containing the following keys:

  - `phoneNumber`: Phone number to settle to `REQUIRED`
  - `network`: Mobile money network to settle to `REQUIRED`
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`

- `SettlementTransferService->settleFunds([ settleOpts ])`: `settleOpts`: An array of arrays containing the following keys:

  - `destinationType`: The destination type `REQUIRED FOR A TARGETED TRANSFER`
  - `destinationReference`: The destination reference `REQUIRED FOR A TARGETED TRANSFER`
  - `currency`: 3-digit ISO format currency code. `REQUIRED FOR A TARGETED TRANSFER`
  - `amount`: Amount to settle. `REQUIRED FOR A TARGETED TRANSFER` PS: If not included the whole balance will be settled.
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`

- `SettlementTransferService->getStatus([ location ])`:

  - `location`: The request location you get when you send a request
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`

For more information, please read [api-docs#transfer](https://api-docs.kopokopo.com/#transfer-to-your-account-s)

### `PollingService`

- `PollingService->pollTransactions([ pollingOpts ])`: `pollingOpts`: An array of arrays containing the following keys:

  - `fromTime`: The starting time of the polling request
  - `toTime`: The end time of the polling request
  - `scope`: The scope of the polling request
  - `scopeReference`: The scope reference `REQUIRED for the 'Till' scope`  
  - `callbackUrl`: Url that the [result](#responsesandresults) will be posted to `REQUIRED`
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`  

- `PollingService->getStatus([ statusOpts ])`: `statusOpts`: An array of arrays containing the following keys:

  - `location`: The location url you got from the request `REQUIRED`
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`

This works the same for all requests that you get a location response.

For more information, please read [api-docs#polling](https://api-docs.kopokopo.com/#polling)

### `SmsNotificationService`

- `SmsNotificationService->sendTransactionSmsNotification([ transactionNotificationOpts ])`: `transactionNotificationOpts`: An array of arrays containing the following keys:

  - `webhookEventReference`: The webhook event reference for a buygoods_transaction_received webhook.
  - `message`: The message to be sent
  - `callbackUrl`: Url that the [result](#responsesandresults) will be posted to `REQUIRED`
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`  

- `SmsNotificationService->getStatus([ statusOpts ])`: `statusOpts`: An array of arrays containing the following keys:

  - `location`: The location url you got from the request `REQUIRED`
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`

This works the same for all requests that you get a location response.

For more information, please read [api-docs#transaction-sms-notifications](https://api-docs.kopokopo.com/#transaction-sms-notifications)


### Responses and Results

- All the post requests are asynchronous apart from `TokenService`. This means that the result will be posted to your custom callback url when the request is complete. The immediate response of the post requests contain the `location` url of the request you have sent which you can use to query the status.

Note: The asynchronous results are processed like webhooks.

- To access the different parts of the response or webhook payload passed, use the following keys to access:
  
#### Token Response

- getToken() successful response

  - `acessToken`
  - `tokenType`
  - `expiresIn`
  - `createdAt`

- introspectToken() successful response

  - `active`
  - `scope`
  - `clientId`
  - `tokenType`
  - `exp` - expiring time
  - `iat` - initiated at

- infoToken() successful response

  - `scope`
  - `expiresIn`
  - `resourceOwnerId`
  - `applicationId`
  - `tokenType`
  - `createdAt`

#### Webhooks

- Buygoods Received

  - `id`
  - `topic`
  - `createdAt`
  - `eventType`
  - `resourceId`
  - `reference`
  - `originationTime`
  - `senderPhoneNumber`
  - `amount`
  - `currency`
  - `tillNumber`
  - `system`
  - `status`
  - `senderFirstName`
  - `senderMiddleName`
  - `senderLastName`
  - `linkSelf`
  - `linkResource`

- B2b transaction received

  - `id`
  - `topic`
  - `createdAt`
  - `eventType`
  - `resourceId`
  - `reference`
  - `originationTime`
  - `sendingTill`
  - `amount`
  - `currency`
  - `tillNumber`
  - `system`
  - `status`
  - `linkSelf`
  - `linkResource`

- Merchant to merchant transaction received

  - `id`
  - `topic`
  - `createdAt`
  - `eventType`
  - `resourceId`
  - `originationTime`
  - `sendingMerchant`
  - `amount`
  - `currency`
  - `status`
  - `linkSelf`
  - `linkResource`

- Buygoods transaction reversed

  - `id`
  - `topic`
  - `createdAt`
  - `eventType`
  - `resourceId`
  - `reference`
  - `originationTime`
  - `senderPhoneNumber`
  - `amount`
  - `currency`
  - `tillNumber`
  - `system`
  - `status`
  - `senderFirstName`
  - `senderMiddleName`
  - `senderLastName`
  - `linkSelf`
  - `linkResource`

- Transfer completed webhook

  - `id`
  - `topic`
  - `createdAt`
  - `eventType`  
  - `resourceId`
  - `originationTime`
  - `amount`
  - `currency`
  - `status`
  - `disbursements`
  - `linkSelf`
  - `linkResource`
  - `destinationReference`
  - `destinationType`
  - if destination type is bank:

    - `settlementMethod`
    - `bankBranchRef`
    - `accountName`
    - `accountNumber`

  - if destination type is mobile wallet:

    - `firstName`
    - `lastName`
    - `phoneNumber`
    - `network`

- Customer created webhook

  - `id`
  - `topic`
  - `createdAt`
  - `eventType`
  - `firstName`
  - `middleName`
  - `lastName`
  - `phoneNumber`
  - `linkSelf`
  - `linkResource`

#### Results

- Settlement Transfer result

  - `id`
  - `type`
  - `createdAt`
  - `status`
  - `transferBatches`
  - `amount`
  - `currency`
  - `linkSelf`
  - `callbackUrl`

- Payment result

  - `id`
  - `type`
  - `status`
  - `createdAt`
  - `transferBatches`
  - `amount`
  - `currency`
  - `metadata`
  - `linkSelf`
  - `callbackUrl`

- Stk Push Result

  - Successful result

    - `id`
    - `type`
    - `initiationTime`
    - `status`
    - `eventType`
    - `resourceId`
    - `reference`
    - `originationTime`
    - `senderPhoneNumber`
    - `amount`
    - `currency`
    - `tillNumber`
    - `system`
    - `senderFirstName`
    - `senderMiddleName`
    - `senderLastName`
    - `resourceStatus`
    - `errors`
    - `metadata`
    - `linkSelf`    
    - `callbackUrl`

  - Unsuccessful result

    - `id`
    - `type`
    - `initiationTime`
    - `status`
    - `eventType`
    - `resource`
    - `errors`
    - `metadata`
    - `linkSelf`
    - `callbackUrl`

- Polling Result
  - `id`
  - `type`
  - `status`
  - `fromTime`
  - `toTime`
  - `scope`
  - `scopeReference`
  - `transactions`
  - `linkSelf`
  - `callbackUrl`

- Transaction SMS Notification Result
  - `id`
  - `type`
  - `status`
  - `message`
  - `webhookEventReference`
  - `linkSelf`
  - `callbackUrl`

#### Status Payloads

- Webhook Subscription Status
  - `id`
  - `type`
  - `eventType`
  - `webhookUri`
  - `status`
  - `scope`
  - `scopeReference`

- Merchant Bank Account Status
  - `id`
  - `type`
  - `accountNumber`
  - `accountName`
  - `bankBranchRef`
  - `settlementMethod`
  - `status`
  - `accountReference`

- Merchant Mobile Wallet Status
  - `id`
  - `type`
  - `firstName`
  - `lastName`
  - `phoneNumber`
  - `network`
  - `status`
  - `accountReference`

- Settlement Transfer Status
  - This payload is similar to `SettlementTransferResult` payload

- Payment Status
  - This payload is similar to `PaymentResult` payload

- Pay Recipient Status
  - `id`
  - `type`
  - `recipientType`
  - `status`
  - `recipientReference`

  - If `recipientType == "Bank Account"`

    - `accountNumber`
    - `accountName`
    - `bankBranchRef`
    - `settlementMethod`
  
  - If `recipientType == "Mobile Wallet"`

    - `firstName`
    - `lastName`
    - `phoneNumber`
    - `network`
    - `email`
  
  - If `recipientType == "Till"`

    - `tillNumber`
    - `tillName`

  - If `recipientType == "Paybill"`

    - `paybillName`
    - `paybillNumber`
    - `paybillAccountNumber`

- Stk Push Status

  - Successful request
    - This payload is simialr to the successful result
  - Failed request
    - This payload is similar to failed result
  - Pending request
    - `id`
    - `type`
    - `initiationTime`
    - `status`
    - `eventType`
    - `resource`
    - `errors`
    - `metadata`
    - `linkSelf`
    - `callbackUrl`

- Polling Status
  - This payload is the same as the `Polling` result payload

- Transaction SMS Notification Status
  - This payload is the same as the `Transaction SMS Notification` result payload

#### Error responses

- `errorCode`
- `errorMessage`

- Token Error Responses
  - `error`
  - `errorDescription`


For more information on the expected payloads and error codes, please read the [api docs](https://api-docs.kopokopo.com)

## Author

[Nicollet Njora](https://github.com/NicoNjora)

## Contributions

We welcome those with open arms just make a pull request and we will review.

### Development

Run all tests:

```bash
$ composer install
$ php vendor/bin/phpunit tests --testdox
```

### Issues

If you find a bug, please file an issue on [our issue tracker on GitHub](https://github.com/kopokopo/k2-connect-php/issues).

## License

k2-connect-php is MIT licensed. See [LICENSE](https://github.com/kopokopo/k2-connect-php/blob/master/LICENSE) for details.

## Change log
