<?php

require 'vendor/autoload.php';

use Kopokopo\SDK\K2;
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$options = [
    'clientId' => $_ENV['K2_CLIENT_ID'],
    'clientSecret' => $_ENV['K2_CLIENT_SECRET'],
    'apiKey' => $_ENV['K2_API_KEY'],
    'baseUrl' => $_ENV['K2_BASE_URL']
];

$K2 = new K2($options);

$router = new AltoRouter();

// $tokens = $K2->TokenService();
// $response = $tokens->getToken();

// $access_token = $response['access_token'];

// map homepage
$router->map('GET', '/', function () {
    require __DIR__.'/views/index.php';
});

$router->map('GET', '/status', function () {
    require __DIR__.'/views/status.php';
});

$router->map('GET', '/webhook/subscribe', function () {
    require __DIR__.'/views/subscribe.php';
});

$router->map('GET', '/stk', function () {
    require __DIR__.'/views/receive.php';
});

$router->map('GET', '/stk/status', function () {
    require __DIR__.'/views/stkstatus.php';
});

$router->map('GET', '/settlementaccounts', function () {
    require __DIR__.'/views/settlementaccounts.php';
});

$router->map('GET', '/transfer', function () {
    require __DIR__.'/views/transfer.php';
});

$router->map('GET', '/transfer/status', function () {
    require __DIR__.'/views/transferstatus.php';
});

$router->map('GET', '/pay', function () {
    require __DIR__.'/views/pay.php';
});

$router->map('GET', '/pay/recipients', function () {
    require __DIR__.'/views/payrecipients.php';
});

$router->map('GET', '/pay/status', function () {
    require __DIR__.'/views/paystatus.php';
});

$router->map('GET', '/merchantwallet', function () {
    require __DIR__.'/views/merchantwallet.php';
});

$router->map('GET', '/merchantbankaccount', function () {
    require __DIR__.'/views/merchantbankaccount.php';
});

$router->map('GET', '/paymobilerecipient', function () {
    require __DIR__.'/views/paymobilerecipient.php';
});

$router->map('GET', '/paybankrecipient', function () {
    require __DIR__.'/views/paybankrecipient.php';
});

$router->map('GET', '/paytillrecipient', function () {
    require __DIR__.'/views/paytillrecipient.php';
});

$router->map('GET', '/paypaybillrecipient', function () {
    require __DIR__.'/views/paypaybillrecipient.php';
});

$router->map('GET', '/polling', function () {
    require __DIR__.'/views/polling.php';
});

$router->map('GET', '/smsnotification', function () {
    require __DIR__.'/views/smsnotification.php';
});

$router->map('GET', '/token', function () {
    global $K2;

    $tokens = $K2->TokenService();
    $response = $tokens->getToken();

    $access_token = $response['data']['accessToken'];


    echo json_encode($response);
});

$router->map('GET', '/revoketoken', function () {
    global $K2;

    $tokens = $K2->TokenService();
    $tokenResponse = $tokens->getToken();

    $access_token = $tokenResponse['data']['accessToken'];

    $response = $tokens->revokeToken(['accessToken' => $access_token]);
    echo json_encode($response);
});

$router->map('GET', '/infotoken', function () {
    global $K2;

    $tokens = $K2->TokenService();
    $tokenResponse = $tokens->getToken();

    $access_token = $tokenResponse['data']['accessToken'];

    $response = $tokens->infoToken(['accessToken' => $access_token]);
    echo json_encode($response);
});

$router->map('GET', '/introspecttoken', function () {
    global $K2;

    $tokens = $K2->TokenService();
    $tokenResponse = $tokens->getToken();

    $access_token = $tokenResponse['data']['accessToken'];

    $response = $tokens->introspectToken(['accessToken' => $access_token]);
    echo json_encode($response);
});

$router->map('POST', '/webhook/subscribe', function () {
    global $K2;

    $tokens = $K2->TokenService();
    $response = $tokens->getToken();

    // echo json_encode($response);
    // echo json_encode($response['data']);
    // echo json_encode($response['data']['accessToken']);

    $access_token = $response['data']['accessToken'];

    // echo $access_token;

    $webhooks = $K2->Webhooks();

    $options = array(
        'eventType' => $_POST['eventType'],
        'url' => $_POST['url'],
        'scope' => $_POST['scope'],
        'scopeReference' => $_POST['scope_ref'],
        'accessToken' => $access_token,
    );
    $response = $webhooks->subscribe($options);

    echo json_encode($response);
});

$router->map('POST', '/stk', function () {
    global $K2;
    $stk = $K2->StkService();

    $tokens = $K2->TokenService();
    $response = $tokens->getToken();

    $access_token = $response['data']['accessToken'];

    $options = [
        'paymentChannel' => 'M-PESA STK Push',
        'tillNumber' => '514459',
        'firstName' => $_POST['firstName'],
        'lastName' => $_POST['lastName'],
        'phoneNumber' => $_POST['phoneNumber'],
        'amount' => $_POST['amount'],
        'currency' => 'KES',
        'email' => 'example@example.com',
        'callbackUrl' => 'https://4773626d5d5c.ngrok.io/webhook',
        'accessToken' => $access_token,
    ];
    $response = $stk->initiateIncomingPayment($options);

    echo json_encode($response);
});

$router->map('POST', '/polling', function () {
    global $K2;
    $polling = $K2->PollingService();

    $tokens = $K2->TokenService();
    $response = $tokens->getToken();

    $access_token = $response['data']['accessToken'];

    $options = [
        'fromTime' => $_POST['from_time'],
        'toTime' => $_POST['to_time'],
        'scope' => $_POST['scope'],
        'scopeReference' => $_POST['scope_ref'],
        'callbackUrl' => 'https://8ad50a368ffa.ngrok.io/webhook',
        'accessToken' => $access_token,
    ];
    $response = $polling->pollTransactions($options);

    echo json_encode($response);
});

$router->map('POST', '/smsnotification', function () {
    global $K2;
    $sms_notification = $K2->SmsNotificationService();

    $tokens = $K2->TokenService();
    $response = $tokens->getToken();

    $access_token = $response['data']['accessToken'];

    $options = [
        'message' => $_POST['message'],
        'webhookEventReference' => $_POST['webhookEventReference'],
        'callbackUrl' => 'https://8ad50a368ffa.ngrok.io/webhook',
        'accessToken' => $access_token,
    ];
    $response = $sms_notification->sendTransactionSmsNotification($options);

    echo json_encode($response);
});

$router->map('POST', '/merchantwallet', function () {
    global $K2;
    $transfer = $K2->SettlementTransferService();

    $tokens = $K2->TokenService();
    $response = $tokens->getToken();

    $access_token = $response['data']['accessToken'];

    $options = [
        'firstName' => $_POST['firstName'],
        'lastName' => $_POST['lastName'],
        'phoneNumber' => $_POST['phoneNumber'],
        'network' => $_POST['network'],
        'accessToken' => $access_token,
    ];
    $response = $transfer->createMerchantWallet($options);

    echo json_encode($response);
});

$router->map('POST', '/merchantbankaccount', function () {
    global $K2;
    $transfer = $K2->SettlementTransferService();

    $tokens = $K2->TokenService();
    $response = $tokens->getToken();

    $access_token = $response['data']['accessToken'];

    $options = [
        'bankBranchRef' => $_POST['bankBranchRef'],
        'accountName' => $_POST['accountName'],
        'accountNumber' => $_POST['accountNumber'],
        'settlementMethod' => $_POST['settlementMethod'],
        'accessToken' => $access_token,
    ];
    $response = $transfer->createMerchantBankAccount($options);

    echo json_encode($response);
});

$router->map('POST', '/transfer', function () {
    global $K2;
    $transfer = $K2->SettlementTransferService();

    $tokens = $K2->TokenService();
    $response = $tokens->getToken();

    $access_token = $response['data']['accessToken'];

    $options = [
        'amount' => $_POST['amount'],
        'currency' => 'KES',
        'destinationReference' => $_POST['destinationReference'],
        'destinationType' => $_POST['destinationType'],
        'callbackUrl' => 'https://4773626d5d5c.ngrok.io/webhook',
        'accessToken' => $access_token,
    ];
    $response = $transfer->settleFunds($options);

    echo json_encode($response);
});

$router->map('POST', '/paymobilerecipient', function () {
    global $K2;
    $transfer = $K2->PayService();

    $tokens = $K2->TokenService();
    $response = $tokens->getToken();

    $access_token = $response['data']['accessToken'];

    $options = [
        'type' => 'mobile_wallet',
        'firstName' => $_POST['firstName'],
        'lastName' => $_POST['lastName'],
        'phoneNumber' => $_POST['phoneNumber'],
        'network' => $_POST['network'],
        'accessToken' => $access_token,
    ];
    $response = $transfer->addPayRecipient($options);

    echo json_encode($response);
});

$router->map('POST', '/paybankrecipient', function () {
    global $K2;
    $transfer = $K2->PayService();

    $tokens = $K2->TokenService();
    $response = $tokens->getToken();

    $access_token = $response['data']['accessToken'];

    $options = [
        'type' => 'bank_account',
        'bankBranchRef' => $_POST['bankBranchRef'],
        'accountName' => $_POST['accountName'],
        'accountNumber' => $_POST['accountNumber'],
        'settlementMethod' => $_POST['settlementMethod'],
        'accessToken' => $access_token,
    ];
    $response = $transfer->addPayRecipient($options);

    echo json_encode($response);
});

$router->map('POST', '/paytillrecipient', function () {
    global $K2;
    $transfer = $K2->PayService();

    $tokens = $K2->TokenService();
    $response = $tokens->getToken();

    $access_token = $response['data']['accessToken'];

    $options = [
        'type' => 'till',
        'tillName' => $_POST['tillName'],
        'tillNumber' => $_POST['tillNumber'],
        'accessToken' => $access_token,
    ];
    $response = $transfer->addPayRecipient($options);

    echo json_encode($response);
});

$router->map('POST', '/paypaybillrecipient', function () {
    global $K2;
    $transfer = $K2->PayService();

    $tokens = $K2->TokenService();
    $response = $tokens->getToken();

    $access_token = $response['data']['accessToken'];

    $options = [
        'type' => 'paybill',
        'paybillName' => $_POST['paybillName'],
        'paybillNumber' => $_POST['paybillNumber'],
        'paybillAccountNumber' => $_POST['paybillAccountNumber'],
        'accessToken' => $access_token,
    ];
    $response = $transfer->addPayRecipient($options);

    echo json_encode($response);
});

$router->map('POST', '/pay', function () {
    global $K2;
    $pay = $K2->PayService();

    $tokens = $K2->TokenService();
    $response = $tokens->getToken();

    $access_token = $response['data']['accessToken'];

    $options = [
        'destinationType' => $_POST['destinationType'],
        'destinationReference' => $_POST['destinationReference'],
        'description' => $_POST['description'],
        'category' => '',
        'tags' => '',
        'amount' => $_POST['amount'],
        'currency' => 'KES',
        'accessToken' => $access_token,
        'callbackUrl' => 'https://4773626d5d5c.ngrok.io/webhook',
    ];
    $response = $pay->sendPay($options);

    echo json_encode($response);
});

$router->map('POST', '/webhook', function () {
    global $K2;
    global $response;

    $webhooks = $K2->Webhooks();

    $json_str = file_get_contents('php://input');

    $response = $webhooks->webhookHandler($json_str, $_SERVER['HTTP_X_KOPOKOPO_SIGNATURE']);

    echo json_encode($response);
    // print("POST Details: " .$json_str);
    // print_r($json_str);
});

$router->map('POST', '/status', function () {
    global $K2;

    $tokens = $K2->TokenService();
    $response = $tokens->getToken();

    $access_token = $response['data']['accessToken'];

    $webhooks = $K2->Webhooks();

    $options = array(
        'location' => $_POST['location'],
        'accessToken' => $access_token,
    );
    $response = $webhooks->getStatus($options);

    echo json_encode($response);
});

$router->map('GET', '/webhook/resource', function () {
    global $response;
    echo $response;
    echo $response;
});

$match = $router->match();
if ($match && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
}
