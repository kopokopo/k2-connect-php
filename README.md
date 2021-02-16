# DISCLAIMER:

This is still in development. To connect to the current kopokopo's api check out it's documentation on <https://app.kopokopo.com/push_api>

# Kopokopo PHP SDK

This is a module to assist php developers in consuming Kopokopo's API

## Installation

You can install the PHP SDK via composer.

The recommended way to install the SDK is with Composer.

```
composer require kopokopo/kopokopo
```

## Initialisation

The package should be configured with your client id and client secret which you can get from your account on the kopokopo's app

```dotenv
Configuration At your project root, create a .env file and in it set the KOPOKOPO_CLIENT_ID , KOPOKOPO_CLIENT_SECRET, KOPOKOPO_API_KEY and KOPOKOPO_ENV as follows

KOPOKOPO_CLIENT_ID=
KOPOKOPO_CLIENT_SECRET=
KOPOKOPO_API_KEY=
KOPOKOPO_ENV= // sandbox or live
```

Or Instantiate  as follows

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
- [Transfer](#transferservice) : `$transfer = $K2->TransferService();`

## Usage

### Tokens

You will need to pass an access token when sending data to Kopokopo's API.

This will return an `access_token` and `expires_in` values

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
    'accessToken' => 'my_access_token'
]);

print_r($response);
```

### STK PUSH

```php
$stk = $K2->StkService();
$result = $stk->initiateIncomingPayment([
                'paymentChannel' => 'M-PESA STK Push',
                'tillNumber' => '13432',
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

- `getToken()` to get an access token.

  - The response will contain: `token_type`, `expires_in`, `created_at` and `access_token`

NB: The access token is required to send subsequent requests

### `StkService`

- `$StkService->initiateIncomingPayment([ stkOptions ])`: `stkOptions`: An array of arrays containing the following keys:

  - `tillNumber`: Your online payments short code from Kopo Kopo's Dashboard `REQUIRED`
  - `firstName`: Customer's first name
  - `lastName`: Customer's last name
  - `phoneNumber`: Phone number to pull money from. `REQUIRED`
  - `email`: Customer's email address
  - `currency`: 3-digit ISO format currency code. `REQUIRED`
  - `amount`: Amount to charge. `REQUIRED`
  - `callbackUrl`: Amount to charge. `REQUIRED`
  - `paymentChannel`: Payment channel. Default is: `"M-PESA STK Push"`. `REQUIRED`
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`
  - `metadata`: It is a hash containing a maximum of 5 key value pairs

- `$StkService->getStatus([location ])`:

  - `location`: The request location you get when you send a request

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
    - Kopo Kopo Merchant(`kopo_kopo_merchant`)
      - `tillNumber`: Pay recipient's till number `REQUIRED`
      - `aliasName`: Pay recipient's alias name `REQUIRED`
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`

- `PayService->sendPay([ payOptions ])`: `payOptions`: An array of arrays containing the following keys:

  - `destinationType`: The recipient type. `REQUIRED`
  - `destinationReference`: The recipient reference. `REQUIRED`
  - `currency`: 3-digit ISO format currency code. `REQUIRED`
  - `amount`: Amount to charge. `REQUIRED`
  - `callbackUrl`: Amount to charge. `REQUIRED`
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`
  - `metadata`: It is a hash containing a maximum of 5 key value pairs

- `PayService->getStatus([ location ])`:

  - `location`: The request location you get when you send a request

For more information, please read <https://api-docs.kopokopo.com/#send-money-pay>

### `TransferService`

- `TransferService->createMerchantBankAccount([ accountOpts ])`: `accountOpts`: An array of arrays containing the following keys:

  - `accountName`: Settlement Account Name `REQUIRED`
  - `bankBranchRef`: Settlement Bank Branch Reference `REQUIRED`
  - `accountNumber`: Settlement account number `REQUIRED`
  - `settlementMethod`: Settlement method `REQUIRED`
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`

- `TransferService->createMerchantWallet([ accountOpts ])`: `accountOpts`: An array of arrays containing the following keys:

  - `phoneNumber`: Phone number to settle to `REQUIRED`
  - `network`: Mobile money network to settle to `REQUIRED`
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`

- `TransferService->settleFunds([ settleOpts ])`: `settleOpts`: An array of arrays containing the following keys:

  - `destinationType`: The destination type `REQUIRED FOR A TARGETED TRANSFER`
  - `destinationReference`: The destination reference `REQUIRED FOR A TARGETED TRANSFER`
  - `currency`: 3-digit ISO format currency code. `REQUIRED FOR A TARGETED TRANSFER`
  - `amount`: Amount to settle. `REQUIRED FOR A TARGETED TRANSFER` PS: If not included the whole balance will be settled.
  - `accessToken`: Gotten from the [`TokenService`](#tokenservice) response `REQUIRED`

- `TransferService->getStatus([ location ])`:

  - `location`: The request location you get when you send a request

For more information, please read [api-docs#transfer](https://api-docs.kopokopo.com/#transfer-to-your-account-s)

### Responses and Results

- All the post requests are asynchronous apart from `TokenService`. This means that the result will be posted to your custom callback url when the request is complete. The immediate response of the post requests contain the `location` url of the request you have sent which you can use to query the status.

Note: The asynchronous results are processed like webhooks.

- To access the different parts of the response or webhook payload passed, use the following keys to access:

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
  - `destinationType`
  - `destinationReference`
  - `amount`
  - `currency`
  - `linkSelf`
  - `callbackUrl`

- Pay result

  - `topic`
  - `status`
  - `reference`
  - `originationTime`
  - `destination`
  - `amount`
  - `currency`
  - `metadata`
  - `linkSelf`

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
    - `linkResource`
    - `linkSelf`    
    - `callbackUrl`

  - Unsuccessful result

    - `id`
    - `type`
    - `initiationTime`
    - `status`
    - `eventType`
    - `resource`
    - `errorsCode`
    - `errorsDescription`
    - `metadata`
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

  - If `recipientType == "Kopo Kopo Merchant"`

    - `tillNumber`
    - `aliasName`

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
