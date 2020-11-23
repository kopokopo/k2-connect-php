<?php
    include 'layout.php';
?>
<div class="container">

<?php

require 'vendor/autoload.php';

use Kopokopo\SDK\K2;

$K2_CLIENT_ID = 'your_client_id';
$K2_CLIENT_SECRET = '10af7ad062a21d9c841877f87b7dec3dbe51aeb3';

$K2 = new K2($K2_CLIENT_ID, $K2_CLIENT_SECRET);

$transfer = $K2->TransferService();

$response = $transfer->getStatus([
    'location' => 'my_request_id',
    'accessToken' => 'myRand0mAcc3ssT0k3n', ]);

print_r($response);

?>