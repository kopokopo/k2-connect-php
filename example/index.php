<?php

require 'vendor/autoload.php';

use Kopokopo\SDK\K2;

$K2_CLIENT_ID = 'onDPKB6ZY_KT4hrUnsWJEuCXW3VvGHnI_XZv5dmsxPQ';
$K2_CLIENT_SECRET = 'A1Wqj1_9KKAn1oAa3G9eCkqXwzM0GT9BgEMsjiXq0Zc';
$BASE_URL = 'http://localhost:3000';

$K2 = new K2($K2_CLIENT_ID, $K2_CLIENT_SECRET, $BASE_URL);

$router = new AltoRouter();

// $tokens = $K2->TokenService();
// $response = $tokens->getToken();

// $access_token = $response['access_token'];

// map homepage
$router->map('GET', '/', function () {
    require __DIR__.'/views/index.php';
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

$router->map('POST', '/webhook/subscribe', function () {
    global $K2;

    $tokens = $K2->TokenService();
    $response = $tokens->getToken();

    // echo json_encode($response);
    // echo json_encode($response['data']);
    // echo json_encode($response['data']->access_token);

    $access_token = $response['data']->access_token;

    // echo $access_token;

    $webhooks = $K2->Webhooks();

    $options = array(
        'eventType' => $_POST['eventType'],
        'url' => $_POST['url'],
        'scope' => 'company',
        'scopeReference' => '4',
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

    $access_token = $response['data']->access_token;

    $options = [
        'paymentChannel' => 'M-PESA STK Push',
        'shortCode' => '514459',
        'firstName' => $_POST['firstName'],
        'lastName' => $_POST['lastName'],
        'phoneNumber' => $_POST['phoneNumber'],
        'amount' => $_POST['amount'],
        'currency' => 'KES',
        'email' => 'example@example.com',
        'callbackUrl' => 'https://webhook.site/675d4ef4-0629-481f-83cd-d101f55e4bc8',
        'accessToken' => $access_token,
    ];
    $response = $stk->initiateIncomingPayment($options);

    echo json_encode($response);
});

$router->map('POST', '/transfer', function () {
    global $K2;
    $transfer = $K2->TransferService();

    $tokens = $K2->TokenService();
    $response = $tokens->getToken();

    $access_token = $response['data']->access_token;

    $options = [
        'amount' => $_POST['amount'],
        'currency' => 'KES',
        'destinationReference' => $_POST['destinationReference'],
        'destinationType' => $_POST['destinationType'],
        'accessToken' => $access_token,
    ];
    $response = $transfer->settleFunds($options);

    echo json_encode($response);
});

$router->map('POST', '/pay', function () {
    global $K2;
    $pay = $K2->PayService();

    $tokens = $K2->TokenService();
    $response = $tokens->getToken();

    $access_token = $response['data']->access_token;

    $options = [
        'destinationType' => $_POST['destinationType'],
        'destinationReference' => $_POST['destinationReference'],
        'amount' => $_POST['amount'],
        'currency' => 'KES',
        'accessToken' => $access_token,
        'callbackUrl' => 'http://localhost:8000/webhook',
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
